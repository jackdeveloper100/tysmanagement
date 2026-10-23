<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "artcomp_version".
 *
 * @property integer $id
 * @property integer $artcomp_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ArtworkComp $artcomp
 * @property ArtcompVersionState[] $artcompVersionStates
 */
class ArtcompVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artcomp_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['artcomp_id', 'name'], 'required'],
            [['artcomp_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [
                ['artcomp_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ArtworkComp::class,
                'targetAttribute' => ['artcomp_id' => 'id']
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
            'artcomp_id' => 'Artcomp ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtcomp()
    {
        return $this->hasOne(ArtworkComp::class, ['id' => 'artcomp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtcompVersionStates()
    {
        return $this->hasMany(ArtcompVersionState::class, ['artcomp_version_id' => 'id']);
    }
}