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

class WeeklyTaskForm extends Form
{
    public $id;
    public $user_id;
    public $job_id;
    public $sub_job_id;
    public $task_item_id;
    public $description;

    public $hours = [];

    public function setModel(ActiveRecord $model, $load = true)
    {
        parent::setModel($model, $load);
        $this->id = $this->model->id;
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
            [['user_id', 'job_id', 'task_item_id', 'description'], 'required'],
            [['user_id', 'job_id', 'sub_job_id', 'task_item_id'], 'integer'],
            [['description'], 'string'],
            [['hours'], 'safe'],
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
            [['task_item_id'], 'in', 'range' => TaskItem::getKeys()]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'job_id' => 'Job ID',
            'sub_job_id' => 'Sub Job ID',
            'task_item_id' => 'Task Item ID',
            'description' => 'Description',
            'date' => 'Date',
            'hours' => 'Hours',
        ];
    }

    public function getIsNewRecord()
    {
        return empty($this->id);
    }

    public function save()
    {
        if ($this->validate()) {

            foreach ($this->hours as $item) {
                if ($item['hours'] > 0) {
                    /**
                     * @var $model Task
                     */
                    $model = clone $this->model;
                    $model->setAttributes($this->getAttributes());
                    $model->date = date('Y-m-d', strtotime($item['date']));
                    $model->hours = $item['hours'];
                    $model->save();
                }
            }

            return true;
        }
        return false;
    }
}