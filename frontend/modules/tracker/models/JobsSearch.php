<?php
namespace frontend\modules\tracker\models;

use common\models\Client;
use common\models\ClientLogin;
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
    public $created_at;
    public $status;
    public $manager_id;
    public $budget_hours;
    public $soft_deleted;

    public function rules()
    {
        return [
            [['manager_id', 'status', 'soft_deleted'], 'integer'],
            [['budget_hours'], 'number'],
            [['created_at'], 'safe'],
            [['internal_id', 'name'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'soft_deleted' => 'Deleted',
            'creator_id' => 'Creator',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }

    public function search($params)
    {
        /**
         * @var $client ClientLogin
         */
        $client = \Yii::$app->user->identity;
        $query = Job::find()->where(['client_id' => $client->client_id]);
        if (!$client->all_project_access) {
            $query->andWhere([
                'in',
                'id',
                collect($client->projects)->map(function ($item) {
                    return $item->id;
                })
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        $dataProvider->pagination->pageSize = 20;

        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere(['soft_deleted' => false]);
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['manager_id' => $this->manager_id])
            ->andFilterWhere(['soft_deleted' => $this->soft_deleted])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['budget_hours' => $this->budget_hours])
            ->andFilterWhere(['like', 'internal_id', $this->internal_id])
            ->andFilterWhere(['like', 'name', $this->name]);
        if (!empty($this->created_at)) {
            $query->andFilterWhere(['DATE(created_at)' => date('Y-m-d', strtotime($this->created_at))]);
        }

        return $dataProvider;
    }
}
