<?php
namespace backend\components;

use yii\base\Model;
use yii\db\ActiveRecord;

abstract class Form extends Model
{
    /**
     * @var ActiveRecord
     */
    public $model;

    public abstract function getIsNewRecord();

    public function setModel(ActiveRecord $model, $load = true)
    {
        $this->model = $model;
        if ($load) {
            $this->load($model->getAttributes(), '');
        }
    }
}
