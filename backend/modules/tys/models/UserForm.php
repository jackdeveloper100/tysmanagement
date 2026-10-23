<?php

namespace backend\modules\tys\models;

use backend\components\Form;
use common\models\User;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * @property User $model
 */
class UserForm extends Form
{
    public $id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $title;
    public $phone;
    public $cell;
    public $hourly_rate;
    public $status;
    public $admin;
    public $slack_username;

    public $role;

    public function setModel(ActiveRecord $model, $load = true)
    {
        parent::setModel($model, $load);
        $this->id = $this->model->id;
        $this->password = null;
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'title', 'slack_username', 'role'], 'required'],
            [['hourly_rate'], 'number'],
            [['admin', 'status'], 'integer'],
            [['first_name', 'last_name', 'email', 'title', 'slack_username'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 65],
            [['phone'], 'string', 'max' => 64],
            [['cell'], 'string', 'max' => 10],
            [['email'], 'unique', 'targetClass' => User::class, 'filter' => ['id' => $this->id]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'auth_key' => 'Auth Key',
            'phone' => 'Phone',
            'cell' => 'Cell',
            'hourly_rate' => 'Hourly Rate',
            'title' => 'Title',
            'admin' => 'Admin',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
            unset($attributes['password']);
            $this->model->setAttributes($attributes);
            if (!empty($this->password)) {
                $this->model->setPassword($this->password);
            }
            if ($this->model->save()) {
                $manager = \Yii::$app->getAuthManager();
                $manager->revokeAll($this->model->id);
                $manager->assign($manager->getRole($this->role), $this->model->id);
                return true;
            }
            $this->addErrors($this->model->getErrors());
            \Yii::$app->session->setFlash('error', Html::errorSummary($this));
        }
        return false;
    }
}
