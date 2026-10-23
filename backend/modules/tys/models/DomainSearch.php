<?php
namespace backend\modules\tys\models;

use common\models\Client;
use common\models\Domains;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DomainSearch extends Model
{
    public $id;
    public $domain;
    public $client_id;
    public $expiration_date;
    public $auto_renew;
    public $registrar;

    public function rules()
    {
        return [
            [['client_id', 'domain', 'registrar', 'expiration_date'], 'required'],
            [['client_id', 'auto_renew'], 'integer'],
            [['expiration_date'], 'safe'],
            [['domain', 'registrar'], 'string', 'max' => 255],
            [
                ['client_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::class,
                'targetAttribute' => ['client_id' => 'id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID'
        ];
    }

    public function search($params)
    {
        $query = Domains::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->defaultOrder = ['expiration_date' => SORT_ASC];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['client_id' => $this->client_id])
            ->andFilterWhere(['expiration_date' => $this->expiration_date])
            ->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'registrar', $this->registrar]);

        if (!empty($this->expiration_date)) {
            $query->andFilterWhere(['expiration_date' => date('Y-m-d', strtotime($this->expiration_date))]);
        }

        return $dataProvider;
    }
}
