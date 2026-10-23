<?php
namespace backend\modules\tracker\models;

use common\models\ArtworkComp;
use yii\base\Model;
use yii\web\UploadedFile;

class ArtworkCompForm extends Model
{
    public $name;
    public $description;
    public $status;

    /**
     * @var UploadedFile
     */
    public $image;

    /**
     * @var ArtworkComp
     */
    public $model;

    public function setModel(ArtworkComp $model)
    {
        $this->model = $model;
        $this->setAttributes($model->getAttributes());
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description'], 'string'],
            [['status'], 'integer'],
            [
                ['image'],
                'image',
                'extensions' => 'png,jpg',
                'mimeTypes' => 'image/png,image/jpeg,image/jpg',
                'skipOnEmpty' => true
            ]
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $this->model->name = $this->name;
            $this->model->description = $this->description;
            if ($this->status != null) {
                $this->model->status = $this->status;
            }
            if ($this->model->save()) {
                $this->saveImage();
                return $this->model->id;
            }
        }
        return false;
    }

    private function saveImage()
    {
        if ($this->image && !$this->image->hasError) {
            $this->model->cleanImage();
            $this->image->saveAs($this->model->getImagePath());
        }
    }
}
