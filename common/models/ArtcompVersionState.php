<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "artcomp_version_state".
 *
 * @property integer $id
 * @property integer $artcomp_version_id
 * @property integer $artwork_item_id
 * @property integer $artwork_item_state_id
 *
 * @property ArtcompVersion $artcompVersion
 * @property ArtworkItem $artworkItem
 * @property ArtworkItemState $artworkItemState
 */
class ArtcompVersionState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artcomp_version_state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['artcomp_version_id', 'artwork_item_id', 'artwork_item_state_id'], 'required'],
            [['artcomp_version_id', 'artwork_item_id', 'artwork_item_state_id'], 'integer'],
            [
                ['artcomp_version_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ArtcompVersion::class,
                'targetAttribute' => ['artcomp_version_id' => 'id']
            ],
            [
                ['artwork_item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ArtworkItem::class,
                'targetAttribute' => ['artwork_item_id' => 'id']
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
            'artcomp_version_id' => 'Artcomp Version ID',
            'artwork_item_id' => 'Artwork Item ID',
            'artwork_item_state_id' => 'Artwork Item State ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtcompVersion()
    {
        return $this->hasOne(ArtcompVersion::class, ['id' => 'artcomp_version_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtworkItem()
    {
        return $this->hasOne(ArtworkItem::class, ['id' => 'artwork_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtworkItemState()
    {
        return $this->hasOne(ArtworkItemState::class, ['id' => 'artwork_item_state_id']);
    }
}