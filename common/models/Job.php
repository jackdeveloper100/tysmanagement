<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "job".
 *
 * @property integer $id
 * @property string $internal_id
 * @property string $name
 * @property integer $manager_id
 * @property integer $sce_job_number
 * @property string $accounting_notes
 * @property double $budget_hours
 * @property integer $client_id
 * @property integer $status
 * @property integer $soft_deleted
 * @property integer $creator_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $slack_channel
 * @property string $docs_link
 * @property string $jira_link
 * @property boolean $internal
 *
 * @property ArtworkComp[] $artworkComps
 * @property Client $client
 * @property User $manager
 * @property SubJob[] $subJobs
 * @property Task[] $tasks
 * @property JobLink[] $links
 */
class Job extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'job';
    }

    public static function getDataList($withEmpty = false, $favorites = false, $allowedStatuses = null)
    {
        $q = static::find()->orderBy(['internal_id' => SORT_DESC])->andWhere(['soft_deleted' => false]);
        if (is_array($allowedStatuses)) {
            $q->andWhere(['in', 'status', $allowedStatuses]);
        }
        $out = ArrayHelper::map($q->all(), 'id', function ($item) {
            return $item->internal_id . ' - ' . $item->name;
        });
        if ($favorites) {
            $users = ArrayHelper::map(Yii::$app->user->identity->favorites, 'id', 'id');
            $out_fav = [];
            $out_regular = [];
            foreach ($out as $key => $item) {
                if (isset($users[$key])) {
                    $out_fav[$key] = '* ' . $item;
                } else {
                    $out_regular[$key] = $item;
                }
            }
            $out = ArrayHelper::merge($out_fav, $out_regular);
        }
        if (!$withEmpty) {
            return $out;
        }

        return $out;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['internal_id', 'name', 'manager_id', 'client_id', 'creator_id'], 'required'],
            [['manager_id', 'client_id', 'sce_job_number', 'status', 'soft_deleted', 'creator_id'], 'integer'],
            [['budget_hours'], 'number'],
            [
                ['internal_id', 'name', 'slack_channel', 'docs_link', 'jira_link'],
                'string',
                'max' => 255
            ],
            [['internal'], 'boolean'],
            [['accounting_notes'], 'string'],
            [['internal_id'], 'unique'],
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
            [['status'], 'default', 'value' => 1],
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
            'internal_id' => 'TYS ID',
            'name' => 'Name',
            'manager_id' => 'Manager ID',
            'accounting_notes' => 'Accounting Notes',
            'sce_job_number' => 'SCE Job Number',
            'budget_hours' => 'Budget Hours',
            'client_id' => 'Client ID',
            'internal' => 'Internal Project',
            'status' => 'Status',
            'soft_deleted' => 'Deleted',
            'creator_id' => 'Creator ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtworkComps()
    {
        return $this->hasMany(ArtworkComp::class, ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(User::class, ['id' => 'manager_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubJobs()
    {
        return $this->hasMany(SubJob::class, ['job_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['job_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(JobLink::class, ['job_id' => 'id']);
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord && empty($this->creator_id)) {
            $this->creator_id = Yii::$app->user->id;
        }
        if ($this->isNewRecord && empty($this->internal_id)) {
            $this->internal_id = (static::find()->max('internal_id') + 1) . "";
        }
        return parent::beforeValidate();
    }

    public function delete()
    {
        $this->soft_deleted = true;
        return $this->save(false, ['soft_deleted']);
    }

    public function getUrl()
    {
        return Url::to(['/tracker/jobs/view', 'id' => $this->id]);
    }
}