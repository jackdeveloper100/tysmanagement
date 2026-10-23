<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "artwork_item".
 *
 * @property integer $id
 * @property integer $comp_id
 * @property string $name
 * @property string $file_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ArtworkComp $comp
 * @property ArtworkItemState[] $artworkItemStates
 */
class ArtworkItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artwork_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comp_id', 'name'], 'required'],
            [['comp_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'file_name'], 'string', 'max' => 255],
            [
                ['comp_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ArtworkComp::class,
                'targetAttribute' => ['comp_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comp_id' => 'Comp ID',
            'name' => 'Name',
            'file_name' => 'File Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComp()
    {
        return $this->hasOne(ArtworkComp::class, ['id' => 'comp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtworkItemStates()
    {
        return $this->hasMany(ArtworkItemState::class, ['item_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->comp->updateTimestamp();
    }

    public function beforeDelete()
    {
        foreach ($this->artworkItemStates as $item) {
            $item->delete();
        }
        return parent::beforeDelete();
    }

    public function updateTimestamp()
    {
        $this->updated_at = new Expression('NOW()');
        $this->save(false, ['updated_at']);
    }

    private $_latest_state_cache;

    /**
     * @return ArtworkItemState
     */
    public function getLatestState()
    {
        if (!$this->_latest_state_cache) {
            $this->_latest_state_cache = ArtworkItemState::find()
                ->where(['item_id' => $this->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->limit(1)
                ->one();
        }
        return $this->_latest_state_cache;
    }

    public function getPreview($width, $height)
    {
        return $this->getLatestState()->getPreview($width, $height);
    }
}