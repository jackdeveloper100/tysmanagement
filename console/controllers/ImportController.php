<?php
namespace console\controllers;

use common\models\enum\JobStatus;
use common\models\enum\TaskItem;
use common\models\Job;
use common\models\SubJob;
use common\models\User;
use GuzzleHttp\Client;
use League\Csv\Reader;
use yii\base\InvalidParamException;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class ImportController extends Controller
{
    /**
     * Parses a CSV HTTP response using the first line as column names.
     */
    private function csvRows($response)
    {
        $reader = Reader::createFromString((string)$response->getBody());
        $reader->setHeaderOffset(0);

        return $reader->getRecords();
    }

    public function actionUsers()
    {
        $url = $this->prompt('Url to CSV file with USERS: ');
        if (empty($url)) {
            throw new InvalidParamException();
        }

        $c = new Client();
        $r = $c->get($url);

        foreach ($this->csvRows($r) as $row) {

            $model = new User();
            $model->first_name = $row['FirstName'];
            $model->last_name = $row['LastName'];
            $model->email = $row['Email'];
            $model->setPassword($row['Password']);
            $model->title = $row['UserTitle'];
            $model->hourly_rate = $row['HourlyRate'];
            $model->phone = $row['Phone'];
            $model->cell = $row['Cell'];
            $model->slack_username = 'temp';

            if (!$model->save()) {
                print_r($row);
                print_r($model->getErrors());
            }
        }
    }

    public function actionClients()
    {
        $url = $this->prompt('Url to CSV file with CLIENTS: ');
        if (empty($url)) {
            throw new InvalidParamException();
        }

        $c = new Client();
        $r = $c->get($url);

        foreach ($this->csvRows($r) as $row) {

            $model = new \common\models\Client();
            $model->name = $row['ClientName'];

            $model->save();
        }
    }

    public function actionJobs()
    {
        $url = $this->prompt('Url to CSV file with JOBS: ');
        $url2 = $this->prompt('Url to CSV file with SUB JOBS: ');
        if (empty($url) || empty($url2)) {
            throw new InvalidParamException();
        }

        $clients = collect(\common\models\Client::getDataList())->map(function ($item) {
            return mb_strtolower(trim($item));
        })->flip();
        $statuses = array_flip(JobStatus::getDataList());
        $users = array_flip(User::getDataList());

        print_r($clients);
        print_r($statuses);

        $c = new Client();
        $r = $c->get($url);
        $r2 = $c->get($url2);

        $subJobs = [];

        foreach ($this->csvRows($r2) as $row) {

            $subJobs[$row['JobID']][] = [
                'internal_id' => $row['SubJobNumber'],
                'description' => $row['Description']
            ];
        }

        $managers = [
            '' => $users['Tin Yen'],
            'Alex' => $users['Alex Chan'],
            'Tin Yen' => $users['Tin Yen'],
            'Adriana' => $users['Adriana Anderson'],
            'Amy / Charlie' => $users['Charlie Shames'],
            'Amy' => $users['Amy Aguilar'],
            'Martin / Alex' => $users['Alex Chan'],
            'Adam' => $users['Adam Roth'],
            'Amy / Tin' => $users['Tin Yen'],
            'Traci Brown' => $users['Traci Brown'],
            'Tracy Brown' => $users['Traci Brown'],
            'Charlie' => $users['Charlie Shames'],
            'Tin' => $users['Tin Yen'],
            'Amy/Charlie' => $users['Charlie Shames']
        ];

        foreach ($this->csvRows($r) as $row) {

            $model = new Job();

            $model->internal_id = $row['JobNumber'];
            $model->name = $row['JobName'];
            $model->client_id = $clients[mb_strtolower(trim($row['ClientName']))];
            $model->status = $statuses[trim($row['JobStatus'])];
            $model->manager_id = $managers[trim($row['PM'])];
            $date = mb_split('/', $row['JobDate']);
            $model->created_at = $date[2] . '-' . $date[0] . '-' . $date[1];
            $model->creator_id = 1;
            $model->accounting_notes = "REF NUMBER: " . $row['ReferenceNumber'] . "\n\nSCE JOB: " . $row['SceJob'] . "\n\nLast invoice Date: " . $row['LastInvoiceDate'];

            if ($model->save()) {
                if (isset($subJobs[$row['JobID']])) {
                    foreach ($subJobs[$row['JobID']] as $sub) {
                        $m = new SubJob();

                        $m->job_id = $model->id;
                        $m->job_number = $sub['internal_id'];
                        $m->job_description = $sub['description'];
                        $m->creator_id = 1;

                        $m->save();
                    }
                }
            } else {
                var_dump($model->getErrors());
            }
        }
    }

    public function actionTimes()
    {
        $url = $this->prompt('Url to CSV file with TIMES: ');
        $url_users = $this->prompt('Url to CSV file with USERS: ');
        $url_jobs = $this->prompt('Url to CSV file with JOBS: ');
        if (empty($url) || empty($url_users) || empty($url_jobs)) {
            throw new InvalidParamException();
        }

        $users = [];

        $c = new Client();
        $r = $c->get($url_users);

        foreach ($this->csvRows($r) as $row) {

            $users[$row['UserID']] = User::findByEmail($row['Email'])->id;
        }

        $jobs = [];

        $c = new Client();
        $r = $c->get($url_jobs);

        foreach ($this->csvRows($r) as $row) {

            $jobs[$row['JobID']] = Job::find()->where(['internal_id' => $row['JobNumber']])->one()->id;
        }

        $ta = array_flip(TaskItem::getDataList());


        $c = new Client();
        $r = $c->get($url);

        foreach ($this->csvRows($r) as $row) {
            if (!isset($jobs[$row['JobID']]) || !isset($users[$row['UserID']])) {
                continue;
            }

            $model = new \common\models\Task();
            $model->job_id = $jobs[$row['JobID']];
            if (!empty($row['SubTaskID'])) {
                $m2 = SubJob::find()->where([
                    'job_id' => $jobs[$row['JobID']],
                    'job_number' => $row['SubTaskID']
                ])->one();
                if ($m2) {
                    $model->sub_job_id = $m2->id;
                } else {
                    $m2 = new SubJob();
                    $m2->job_id = $jobs[$row['JobID']];
                    $m2->job_number = $row['SubTaskID'];
                    $m2->job_description = $row['SubTaskID'] . "";
                    $m2->creator_id = $users[$row['UserID']];
                    if (!$m2->save()) {
                        echo "Err: " . json_encode($m2->getErrors()) . "\n";
                    }

                    $model->sub_job_id = $m2->id;
                }
            }
            $model->user_id = $users[$row['UserID']];
            $model->description = $row['TaskDescription'];
            $model->hours = $row['NumWorkHour'];
            $date = mb_split('/', $row['OperationDate']);
            $model->date = $date[2] . '-' . $date[0] . '-' . $date[1];
            if (!empty($row['DateEntered'])) {
                $date = mb_split('/', $row['DateEntered']);
            }
            $model->created_at = $date[2] . '-' . $date[0] . '-' . $date[1];
            if (isset($ta[$row['TaskItem']])) {
                $model->task_item_id = $ta[$row['TaskItem']];

                if (!$model->save()) {
                    print_r($model->getErrors());
                }
            } else {
                print_r('No task = ' . $row['TaskItem']);
            }


        }
    }

    public function actionReconnectSubJobs()
    {
        $items = SubJob::find()->all();

        $sce_jobs = ArrayHelper::map(\common\models\SCEProjectTracker::getJobs(), 'legacy_id', function ($item) {
            return $item;
        });

        /**
         * @var $items SubJob[]
         */
        $i = 0;
        foreach ($items as $item) {
            if (isset($sce_jobs[$item->job_number])) {
                $item->sce_job_number = $sce_jobs[$item->job_number]->id;
                $item->save(false, ['sce_job_number']);
                $i++;
            }
        }
        echo "Updated " . $i . " records\n";
    }
}
