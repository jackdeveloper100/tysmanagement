<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "artwork_comp".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Job $project
 * @property ArtworkItem[] $artworkItems
 */
class ArtworkComp extends \yii\db\ActiveRecord
{
    private $imagesFolder = 'compPoster';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artwork_comp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'name'], 'required'],
            [['project_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [
                ['project_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Job::class,
                'targetAttribute' => ['project_id' => 'id']
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
            'project_id' => 'Project ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Job::class, ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtworkItems()
    {
        return $this->hasMany(ArtworkItem::class, ['comp_id' => 'id']);
    }

    public function beforeDelete()
    {
        foreach ($this->artworkItems as $item) {
            $item->delete();
        }
        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->cleanImage();
    }

    public function getImagePath($full = true)
    {
        FileHelper::createDirectory(Yii::getAlias('@assets') . DIRECTORY_SEPARATOR . $this->imagesFolder);
        return ($full ? Yii::getAlias('@assets') . DIRECTORY_SEPARATOR : '') . $this->imagesFolder . DIRECTORY_SEPARATOR . $this->id . '.jpg';
    }

    public function cleanImage()
    {
        if (is_file($this->getImagePath())) {
            unlink($this->getImagePath());
        }
        Yii::$app->thumbler->clearImageCache($this->getImagePath(false));
    }

    public function getImage($width, $height)
    {
        if (is_file($this->getImagePath())) {
            return getenv('ASSETS_URL') . '/cache/' . Yii::$app->thumbler->resize($this->getImagePath(false), $width,
                $height);
        } else {
            return getenv('ASSETS_URL') . '/defaults/artcomp.png';
        }
    }

    public function updateTimestamp()
    {
        $this->updated_at = new Expression('NOW()');
        $this->save(false, ['updated_at']);
    }
}