<?php
namespace backend\modules\tys\models;

use backend\components\Form;
use common\models\Client;

class HostingForm extends Form
{
    public $provider;
    public $auto_renew;
    public $expiration_date;

    public function rules()
    {
        return [
            [['provider', 'expiration_date'], 'required'],
            [['auto_renew'], 'integer'],
            [['expiration_date'], 'safe'],
            [['provider'], 'string', 'max' => 255],
        ];
    }

    public function beforeValidate()
    {
        $this->expiration_date = date('Y-m-d', strtotime($this->expiration_date));
        return parent::beforeValidate();
    }

    public function getIsNewRecord()
    {
        return $this->model->isNewRecord;
    }

    public function save()
    {
        if ($this->validate()) {
            $this->model->setAttributes($this->getAttributes());
            return $this->model->save();
        }
        return false;
    }
}
