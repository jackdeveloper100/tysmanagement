<?php
namespace backend\modules\tracker\models;

use backend\components\Form;
use common\models\enum\TaskItem;
use common\models\Job;
use common\models\SubJob;
use common\models\Task;
use common\models\User;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;

class TaskForm extends Form
{
    public $id;
    public $user_id;
    public $job_id;
    public $sub_job_id;
    public $task_item_id;
    public $description;
    public $date;
    public $hours;

    public function setModel(ActiveRecord $model, $load = true)
    {
        parent::setModel($model, !$model->isNewRecord);
        $this->id = $this->model->id;
        $this->user_id = $this->model->user_id;
        if ($this->model->isNewRecord) {
            /**
             * @var $m Task
             */
            $m = Task::find()->where(['user_id' => \Yii::$app->user->id])->orderBy(['id' => SORT_DESC])->one();
            if ($m) {
                $this->task_item_id = $m->task_item_id;
            }
        }
    }

    public function rules()
    {
        return [
            [['user_id', 'job_id', 'task_item_id', 'description', 'date', 'hours'], 'required'],
            [['user_id', 'job_id', 'sub_job_id', 'task_item_id'], 'integer'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['hours'], 'number'],
            [
                ['job_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Job::class,
                'targetAttribute' => ['job_id' => 'id']
            ],
            [
                ['sub_job_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => SubJob::class,
                'targetAttribute' => ['sub_job_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
            [['task_item_id'], 'in', 'range' => TaskItem::getKeys()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'job_id' => 'Job',
            'sub_job_id' => 'Sub Job',
            'task_item_id' => 'Type of work',
            'description' => 'Description',
            'date' => 'Date',
            'hours' => 'Hours',
        ];
    }

    public function beforeValidate()
    {
        if (!empty($this->date)) {
            $this->date = date('Y-m-d', strtotime($this->date));
        }
        return parent::beforeValidate();
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