<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sub_job".
 *
 * @property integer $id
 * @property integer $job_id
 * @property integer $status
 * @property string $job_number
 * @property integer $sce_job_number
 * @property integer $soft_deleted
 * @property integer $creator_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Task[] $tasks
 * @property Job $job
 */
class SubJob extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sub_job';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'job_number', 'creator_id'], 'required'],
            [['job_id', 'creator_id', 'sce_job_number', 'status'], 'integer'],
            [['soft_deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['job_number'], 'string', 'max' => 255],
            [
                ['job_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Job::class,
                'targetAttribute' => ['job_id' => 'id']
            ],
            [['soft_deleted'], 'default', 'value' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job ID',
            'status' => 'Status',
            'job_number' => 'Name',
            'sce_job_number' => 'SCE Job',
            'soft_deleted' => 'Deleted',
            'creator_id' => 'Creator ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['sub_job_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(Job::class, ['id' => 'job_id']);
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord && empty($this->creator_id)) {
            $this->creator_id = Yii::$app->user->id;
        }
        return parent::beforeValidate();
    }
}