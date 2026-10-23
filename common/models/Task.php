<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $job_id
 * @property integer $sub_job_id
 * @property integer $task_item_id
 * @property string $description
 * @property string $date
 * @property double $hours
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Job $job
 * @property SubJob $subJob
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{
    public $sum;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'job_id', 'task_item_id', 'description', 'date', 'hours'], 'required'],
            [['user_id', 'job_id', 'sub_job_id', 'task_item_id'], 'integer'],
            [['description'], 'string'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['hours'], 'number'],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function extraFields()
    {
        return array_column(parent::extraFields(), ['sum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(Job::class, ['id' => 'job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubJob()
    {
        return $this->hasOne(SubJob::class, ['id' => 'sub_job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}