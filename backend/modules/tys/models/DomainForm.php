<?php
namespace backend\modules\tys\models;

use backend\components\Form;
use common\models\Client;

class DomainForm extends Form
{
    public $client_id;
    public $domain;
    public $registrar;
    public $auto_renew;
    public $expiration_date;

    public function rules()
    {
        return [
            [['client_id', 'domain', 'registrar', 'expiration_date'], 'required'],
            [['client_id', 'auto_renew'], 'integer'],
            [['expiration_date'], 'safe'],
            [['domain', 'registrar'], 'string', 'max' => 255],
            [
                ['client_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::class,
                'targetAttribute' => ['client_id' => 'id']
            ],
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
