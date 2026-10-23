<?php
namespace backend\modules\tracker\models;

use backend\components\Form;

class LinkForm extends Form
{
    public $name;
    public $url;

    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['name', 'url'], 'string', 'max' => 255]
        ];
    }

    public function getIsNewRecord()
    {
        $this->model->isNewRecord;
    }

    public function save()
    {
        $this->model->setAttributes($this->getAttributes());
        return $this->model->save();
    }
}
