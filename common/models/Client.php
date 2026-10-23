<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_default
 * @property integer $soft_deleted
 *
 * @property Job[] $jobs
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    public static function getDataList($withEmpty = false, $emptyName = '')
    {
        $out = ArrayHelper::map(static::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
        if (!$withEmpty) {
            return $out;
        }

        return ArrayHelper::merge(['' => $emptyName], $out);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_default', 'soft_deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['is_default', 'soft_deleted'], 'default', 'value' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'is_default' => 'Is Default',
            'soft_deleted' => 'Soft Delete',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobs()
    {
        return $this->hasMany(Job::class, ['client_id' => 'id']);
    }

    public function delete()
    {
        $this->soft_deleted = true;
        return $this->save(false, ['soft_deleted']);
    }

    public function getLogo()
    {
        if (is_file(\Yii::getAlias('@assets/clients/logos/') . $this->id . '.png')) {
            return getenv('ASSETS_URL') . '/clients/logos/' . $this->id . '.png';
        }
        return null;
    }

    public function clearLogo()
    {
        if (is_file(\Yii::getAlias('@assets/clients/logos/') . $this->id . '.png')) {
            unlink(\Yii::getAlias('@assets/clients/logos/') . $this->id . '.png');
        }
    }
}