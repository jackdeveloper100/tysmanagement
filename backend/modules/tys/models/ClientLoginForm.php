<?php

namespace backend\modules\tys\models;

use backend\components\Form;
use common\models\ClientLogin;
use common\models\Job;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * @property ClientLogin $model
 */
class ClientLoginForm extends Form
{
    public $id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $all_project_access;

    public $projects = [];

    public function setModel(ActiveRecord $model, $load = true)
    {
        parent::setModel($model, $load);
        $this->id = $this->model->id;
        $this->password = null;
        foreach ($this->model->projects as $item) {
            $this->projects[$item->id] = $item->id;
        }
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email'], 'required'],
            [
                ['password'],
                'required',
                'when' => function ($model) {
                    return $model->isNewRecord;
                },
                'enableClientValidation' => false
            ],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 65],
            [['all_project_access'], 'boolean'],
            [['projects'], 'safe'],
            [['email'], 'unique', 'targetClass' => ClientLogin::class, 'filter' => ['id' => $this->id]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getIsNewRecord()
    {
        return $this->model->isNewRecord;
    }

    public function save()
    {
        if ($this->validate()) {
            $attributes = $this->getAttributes();
            unset($attributes['password']);
            $this->model->setAttributes($attributes);
            if (!empty($this->password)) {
                $this->model->setPassword($this->password);
            }
            if ($this->model->save()) {
                $this->saveProjects();
                return true;
            }
            $this->addErrors($this->model->getErrors());
            \Yii::$app->session->setFlash('error', Html::errorSummary($this));
        }
        return false;
    }

    private function saveProjects()
    {
        $this->model->unlinkAll('projects', true);
        foreach ($this->projects as $key => $item) {
            $model = Job::findOne($key);
            if ($model) {
                $this->model->link('projects', $model);
            }
        }
    }
}
