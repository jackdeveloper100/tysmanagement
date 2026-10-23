<?php

namespace backend\modules\tracker\models;

use common\models\Client;
use common\models\enum\JobStatus;
use common\models\Job;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class JobsSearch extends Model
{
    public $id;
    public $internal_id;
    public $name;
    public $client_id;
    public $created_at;
    public $status;
    public $manager_id;
    public $budget_hours;
    public $soft_deleted;
    public $internal;

    public $query;

    public function rules()
    {
        return [
            [['manager_id', 'client_id', 'status', 'soft_deleted'], 'integer'],
            [['internal'], 'boolean'],
            [['budget_hours'], 'number'],
            [['created_at', 'query'], 'safe'],
            [['internal_id', 'name'], 'string', 'max' => 255],
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

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'internal_id' => 'TYS ID',
            'name' => 'Name',
            'manager_id' => 'Manager',
            'reference_number' => 'Reference Number',
            'sce_job_number' => 'SCE Job Number',
            'budget_hours' => 'Budget Hours',
            'client_id' => 'Client',
            'internal' => 'Internal',
            'status' => 'Status',
            'soft_deleted' => 'Deleted',
            'creator_id' => 'Creator',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }

    public function search($params)
    {
        $query = Job::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->defaultOrder = ['internal_id' => SORT_DESC];
        $dataProvider->pagination->pageSize = 20;

        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere(['soft_deleted' => false]);
            return $dataProvider;
        }
        if (!empty($this->query)) {
            $query->orWhere(['like', 'internal_id', $this->query]);
            $query->orWhere(['like', 'name', $this->query]);
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['manager_id' => $this->manager_id])
            ->andFilterWhere(['client_id' => $this->client_id])
            ->andFilterWhere(['soft_deleted' => $this->soft_deleted])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['budget_hours' => $this->budget_hours])
            ->andFilterWhere(['internal' => $this->internal])
            ->andFilterWhere(['like', 'internal_id', $this->internal_id])
            ->andFilterWhere(['like', 'name', $this->name]);
        if (!empty($this->created_at)) {
            $query->andFilterWhere(['DATE(created_at)' => date('Y-m-d', strtotime($this->created_at))]);
        }

        return $dataProvider;
    }
}
