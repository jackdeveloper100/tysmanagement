<?php
namespace backend\modules\tys\models;

use common\models\Client;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ClientSearch extends Model
{
    public $id;
    public $name;
    public $is_default;
    public $soft_deleted;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['is_default', 'soft_deleted'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'is_default' => 'Is Default',
            'soft_deleted' => 'Deleted'
        ];
    }

    public function search($params)
    {
        $query = Client::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere(['soft_deleted' => false]);
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['is_default' => $this->is_default])
            ->andFilterWhere(['soft_deleted' => $this->soft_deleted])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
