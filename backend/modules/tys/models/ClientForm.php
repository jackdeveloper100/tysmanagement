<?php
namespace backend\modules\tys\models;

use backend\components\Form;
use common\models\Client;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ClientForm extends Form
{
    public $id;
    public $name;
    public $is_default;

    /**
     * @var UploadedFile
     */
    public $image;

    public function setModel(ActiveRecord $model, $load = true)
    {
        parent::setModel($model, $load);
        $this->id = $this->model->id;
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_default'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => Client::class, 'filter' => ['id' => $this->id]],
            [
                ['image'],
                'image',
                'extensions' => 'png',
                'mimeTypes' => 'image/png',
                'skipOnEmpty' => true,
                'minHeight' => 30
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'is_default' => 'Is Default',
            'soft_delete' => 'Soft Delete'
        ];
    }

    public function attributeHints()
    {
        return [
            'image' => 'Only png images allowed'
        ];
    }

    public function getIsNewRecord()
    {
        return empty($this->id);
    }

    public function save()
    {
        if ($this->validate()) {
            $attributes = $this->getAttributes();
            $this->model->setAttributes($attributes);
            if ($this->model->save()) {
                $this->saveImage();
                return true;
            }
            $this->addErrors($this->model->getErrors());
            \Yii::$app->session->setFlash('error', Html::errorSummary($this));
        }
        return false;
    }

    public function saveImage()
    {
        if (!empty($this->image) && !$this->image->hasError) {
            $this->model->clearLogo();
            FileHelper::createDirectory(\Yii::getAlias('@assets/clients/logos/'));
            $this->image->saveAs(\Yii::getAlias('@assets/clients/logos/') . $this->model->id . '.png');
        }
    }
}