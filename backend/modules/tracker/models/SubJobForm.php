<?php

namespace backend\modules\tracker\models;

use backend\components\Form;
use common\models\Client;
use common\models\Job;
use common\models\User;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;

class SubJobForm extends Form
{
    public $id;
    public $job_id;
    public $job_number;
    public $sce_job_number;
    public $soft_deleted;
    public $status;

    public function setModel(ActiveRecord $model, $load = true)
    {
        parent::setModel($model, $load);
        $this->id = $this->model->id;
    }

    public function rules()
    {
        return [
            [['job_id', 'job_number'], 'required'],
            [['job_id', 'soft_deleted', 'sce_job_number', 'status'], 'integer'],
            [['job_number'], 'string', 'max' => 255],
            [
                ['job_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Job::class,
                'targetAttribute' => ['job_id' => 'id']
            ],
            [['soft_deleted'], 'default', 'value' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job ID',
            'status' => 'Status',
            'job_number' => 'Job Number',
            'sce_job_number' => 'SCE Job',
            'soft_deleted' => 'Deleted'
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
        }
        return false;
    }
}