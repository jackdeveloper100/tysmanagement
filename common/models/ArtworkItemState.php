<?php

namespace common\models;

use alexBond\thumbler\Thumbler;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\ImageManagerStatic as Image;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "artwork_item_state".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $file_name
 * @property string $original_file_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ArtworkItem $item
 */
class ArtworkItemState extends \yii\db\ActiveRecord
{
    private $filesFolder = 'artworkItems';
    private $previewsFolder = 'artworkItemsPreviews';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artwork_item_state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'file_name', 'original_file_name'], 'required'],
            [['item_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['file_name', 'original_file_name'], 'string', 'max' => 255],
            [['file_name'], 'unique'],
            [
                ['item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ArtworkItem::class,
                'targetAttribute' => ['item_id' => 'id']
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
            'item_id' => 'Item ID',
            'file_name' => 'File Name',
            'original_file_name' => 'Original File Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(ArtworkItem::class, ['id' => 'item_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\ArtworkItemStateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\ArtworkItemStateQuery(get_called_class());
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->cleanFile();
        $this->cleanPreviews();
        if (count($this->item->artworkItemStates) == 0) {
            $this->item->delete();
        }
    }

    public function cleanFile()
    {
        if (is_file($this->getFilePath())) {
            unlink($this->getFilePath());
        }
    }

    public function cleanPreviews()
    {
        if (is_file(Yii::getAlias('@assets') . DIRECTORY_SEPARATOR . $this->previewsFolder . DIRECTORY_SEPARATOR . $this->item_id . DIRECTORY_SEPARATOR . $this->id . '.jpg')) {
            unlink(Yii::getAlias('@assets') . DIRECTORY_SEPARATOR . $this->previewsFolder . DIRECTORY_SEPARATOR . $this->item_id . DIRECTORY_SEPARATOR . $this->id . '.jpg');
        }
        Yii::$app->thumbler->clearImageCache($this->previewsFolder . DIRECTORY_SEPARATOR . $this->item_id . DIRECTORY_SEPARATOR . $this->id . '.jpg');
    }

    public function generateFileName($original)
    {
        $this->file_name = Yii::$app->security->generateRandomString(16) . '.' . pathinfo($original,
                PATHINFO_EXTENSION);
    }

    public function getFilePath($full = true)
    {
        FileHelper::createDirectory(Yii::getAlias('@assets') . DIRECTORY_SEPARATOR . $this->filesFolder . DIRECTORY_SEPARATOR . $this->item_id);
        return ($full ? Yii::getAlias('@assets') . DIRECTORY_SEPARATOR : '') . $this->filesFolder . DIRECTORY_SEPARATOR . $this->item_id . DIRECTORY_SEPARATOR . $this->file_name;
    }

    public function getExt()
    {
        return pathinfo($this->getFilePath(), PATHINFO_EXTENSION);
    }

    public function getMimeType()
    {
        return mime_content_type($this->getFilePath());
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->item->updateTimestamp();
    }

    public function getPreview($width, $height)
    {
        $previewFilename = Yii::getAlias('@assets') . DIRECTORY_SEPARATOR . $this->previewsFolder . DIRECTORY_SEPARATOR . $this->item_id . DIRECTORY_SEPARATOR . $this->id . '.jpg';
        if (is_file($previewFilename)) {
            return getenv('ASSETS_URL') . '/cache/' . Yii::$app->thumbler->resize(
                $this->previewsFolder . DIRECTORY_SEPARATOR . $this->item_id . DIRECTORY_SEPARATOR . $this->id . '.jpg',
                $width,
                $height,
                Thumbler::METHOD_CROP_CENTER
            );
        } else {
            FileHelper::createDirectory(Yii::getAlias('@assets') . DIRECTORY_SEPARATOR . $this->previewsFolder . DIRECTORY_SEPARATOR . $this->item_id);
            if ($this->getMimeType() == 'application/pdf') {
                $image = new \Imagick($this->getFilePath() . '[0]');
                $image->setImageFormat("jpeg");
                $image->writeImage($previewFilename);
            } else {
                try {
                    $image = Image::make($this->getFilePath());
                    $image->save($previewFilename);
                } catch (NotSupportedException $e) {
                    \Yii::info($e);
                } catch (NotReadableException $e) {
                    \Yii::info($e);
                }
            }
            if (is_file($previewFilename)) {
                return getenv('ASSETS_URL') . '/cache/' . Yii::$app->thumbler->resize(
                    $this->previewsFolder . DIRECTORY_SEPARATOR . $this->item_id . DIRECTORY_SEPARATOR . $this->id . '.jpg',
                    $width,
                    $height,
                    Thumbler::METHOD_CROP_CENTER
                );
            }
        }
        return getenv('ASSETS_URL') . '/defaults/artitem.png';
    }
}