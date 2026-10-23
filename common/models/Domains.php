<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "domains".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $domain
 * @property string $registrar
 * @property integer $auto_renew
 * @property string $expiration_date
 *
 * @property Client $client
 */
class Domains extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domains';
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'domain' => 'Domain',
            'registrar' => 'Registrar',
            'auto_renew' => 'Auto Renew',
            'expiration_date' => 'Expiration Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
}