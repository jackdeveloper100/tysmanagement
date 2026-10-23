<?php
namespace backend\modules\tracker\models;

use common\models\Job;
use common\models\SubJob;
use common\models\Task;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TasksSearch extends Model
{
    public $id;
    public $job_id;
    public $sub_job_id;
    public $task_item_id;
    public $description;
    public $date;
    public $user;

    public function rules()
    {
        return [
            [['job_id', 'sub_job_id', 'task_item_id', 'user'], 'integer'],
            [['description'], 'string'],
            [['date'], 'safe'],
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
                ['user'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user' => 'id']
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job ID',
            'sub_job_id' => 'Sub Job ID',
            'task_item_id' => 'Task Item ID',
            'description' => 'Description',
            'date' => 'Date',
            'hours' => 'Hours'
        ];
    }

    public function search($params)
    {
        $query = Task::find();

        if (!Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager')) {
            $query->where(['user_id' => \Yii::$app->user->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['job_id' => $this->job_id])
            ->andFilterWhere(['sub_job_id' => $this->sub_job_id])
            ->andFilterWhere(['task_item_id' => $this->task_item_id])
            ->andFilterWhere(['user_id' => $this->user])
            ->andFilterWhere(['like', 'description', $this->description]);

        if (!empty($this->date)) {
            $query->andFilterWhere(['DATE(date)' => date('Y-m-d', strtotime($this->date))]);
        }

        return $dataProvider;
    }
}