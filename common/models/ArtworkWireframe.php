<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "artwork_wireframe".
 *
 * @property integer $id
 * @property integer $comp_id
 * @property string $name
 * @property string $link
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ArtworkWireframe $comp
 * @property ArtworkWireframe[] $artworkWireframes
 */
class ArtworkWireframe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artwork_wireframe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comp_id', 'name', 'link'], 'required'],
            [['comp_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'link'], 'string', 'max' => 255],
            [
                ['comp_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ArtworkWireframe::class,
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
            'link' => 'Link',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComp()
    {
        return $this->hasOne(ArtworkWireframe::class, ['id' => 'comp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtworkWireframes()
    {
        return $this->hasMany(ArtworkWireframe::class, ['comp_id' => 'id']);
    }
}