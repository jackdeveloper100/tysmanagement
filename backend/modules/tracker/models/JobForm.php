<?php

namespace backend\modules\tracker\models;

use backend\components\Form;
use common\models\Client;
use common\models\Job;
use common\models\User;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class JobForm extends Form
{
    public $id;
    public $internal_id;
    public $name;
    public $manager_id;
    public $accounting_notes;
    public $sce_job_number;
    public $budget_hours;
    public $client_id;
    public $status;
    public $internal;

    public $slack_channel;
    public $docs_link;
    public $jira_link;

    public function setModel(ActiveRecord $model, $load = true)
    {
        parent::setModel($model, $load);
        $this->id = $this->model->id;
        if (empty($this->client_id)) {
            if ($model = Client::findOne(['is_default' => true])) {
                $this->client_id = $model->id;
            }
        }
    }

    public function rules()
    {
        $model = $this->model;
        return [
            [['name', 'manager_id', 'client_id', 'internal'], 'required'],
            [
                'internal_id',
                'required',
                'when' => function ($form) use ($model) {
                    return !$model->isNewRecord;
                }
            ],
            [['manager_id', 'client_id', 'sce_job_number', 'status'], 'integer'],
            [['internal'], 'boolean'],
            [['budget_hours'], 'number'],
            [['accounting_notes'], 'string'],
            [
                ['internal_id', 'name', 'slack_channel', 'docs_link', 'jira_link'],
                'string',
                'max' => 255
            ],
            [
                ['internal_id'],
                'unique',
                'targetClass' => Job::class,
                'filter' => ['id' => $this->id]
            ],
            [
                ['client_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::class,
                'targetAttribute' => ['client_id' => 'id']
            ],
            [
                ['manager_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['manager_id' => 'id']
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
            'internal_id' => 'TYS ID',
            'name' => 'Name',
            'manager_id' => 'Manager',
            'accounting_notes' => 'Accounting Notes',
            'sce_job_number' => 'SCE Job Number',
            'budget_hours' => 'Budget Hours',
            'client_id' => 'Client',
            'internal' => 'Internal',
            'status' => 'Status',
            'soft_deleted' => 'Deleted',
            'creator_id' => 'Creator',
            'created_at' => 'Created At'
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
                return true;
            }
            $this->addErrors($this->model->getErrors());
            \Yii::$app->session->setFlash('error', Html::errorSummary($this));
        } else {
            \Yii::info(json_encode($this->getAttributes()));
        }
        return false;
    }
}