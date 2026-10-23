<?php

namespace backend\modules\tracker\models;

use common\models\SubJob;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SubJobsSearch extends Model
{
    public $id;
    public $job_number;
    public $soft_deleted;
    public $status;

    public function rules()
    {
        return [
            [['soft_deleted', 'status'], 'integer'],
            [['job_number'], 'string'],
            [['soft_deleted'], 'default', 'value' => false]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_number' => 'Job Number',
            'soft_deleted' => 'Deleted'
        ];
    }

    public function search($job_id, $params)
    {
        $query = SubJob::find()->where(['job_id' => $job_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere(['soft_deleted' => false]);
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['soft_deleted' => $this->soft_deleted])
            ->andFilterWhere(['like', 'job_number', $this->job_number]);

        return $dataProvider;
    }
}
