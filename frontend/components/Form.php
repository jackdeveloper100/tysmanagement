<?php
namespace frontend\components;

use yii\base\Model;
use yii\db\ActiveRecord;

abstract class Form extends Model
{
    /**
     * @var ActiveRecord
     */
    protected $_model;

    public function __construct(ActiveRecord $model, $config = [])
    {
        $this->_model = $model;
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();
        $this->_model->loadDefaultValues();
        $this->setAttributes($this->_model->getAttributes());
    }

    public function getModel()
    {
        return $this->_model;
    }

    public abstract function getIsNewRecord();
}
