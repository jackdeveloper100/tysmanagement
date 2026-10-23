<?php

namespace backend\modules\accounting\controllers;

use backend\components\Controller;
use common\models\enum\TaskItem;
use common\models\Job;
use common\models\SCEProjectTracker;
use common\models\Task;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class ReportsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['accountant']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $sce_jobs = ArrayHelper::map(SCEProjectTracker::getJobs(), 'id', function ($item) {
            return $item;
        });
        $q = \common\models\Task::find()
            ->select(['task.*', 'sum' => 'SUM(hours)'])
            ->groupBy([
                'user_id',
                new Expression('CONCAT(job_id, \'_\', sub_job_id)'),
                'date',
                'description'
            ])
            ->with(['user', 'job', 'job.client'])
            ->leftJoin('job', ['job.id' => new Expression('task.job_id')])
            ->andFilterWhere(['job_id' => Yii::$app->request->get('job')])
            ->andFilterWhere(['sub_job_id' => Yii::$app->request->get('sub_job_id')])
            ->orderBy(['id' => SORT_DESC]);

        if (!empty(Yii::$app->request->get('from'))) {
            $q->andFilterWhere(['>=', 'date', date('Y-m-d', strtotime(Yii::$app->request->get('from')))]);
        }
        if (!empty(Yii::$app->request->get('to'))) {
            $q->andFilterWhere(['<=', 'date', date('Y-m-d', strtotime(Yii::$app->request->get('to')))]);
        }
        if (!empty(Yii::$app->request->get('entry_from'))) {
            $q->andFilterWhere(['>=', 'created_at', date('Y-m-d', strtotime(Yii::$app->request->get('entry_from')))]);
        }
        if (!empty(Yii::$app->request->get('entry_to'))) {
            $q->andFilterWhere(['<=', 'created_at', date('Y-m-d', strtotime(Yii::$app->request->get('entry_to')))]);
        }
        if (is_array(Yii::$app->request->get('clients'))) {
            $q->andWhere(['IN', 'job.client_id', Yii::$app->request->get('clients', [])]);
        }
        if (is_array(Yii::$app->request->get('clientsExclude'))) {
            $q->andWhere(['NOT IN', 'job.client_id', Yii::$app->request->get('clientsExclude', [])]);
        }

        return $this->render('index', ['q' => $q, 'sce_jobs' => $sce_jobs]);
    }

    public function actionProjects()
    {
        return $this->render('projects');
    }

    public function actionGenerateExcelProjects()
    {
        $doc = new Spreadsheet();
        $doc->setActiveSheetIndex(0);

        $q = \common\models\Job::find()
            ->andFilterWhere(['client_id' => Yii::$app->request->get('client')])
            ->andFilterWhere(['manager_id' => Yii::$app->request->get('manager')])
            ->andFilterWhere(['status' => Yii::$app->request->get('status')])
            ->orderBy(['internal_id' => SORT_DESC]);

        $out = [
            [
                'TYS ID',
                'Name',
                'Client',
                'Created At',
                'Status',
                'Manager',
                'Budget hours'
            ]
        ];

        foreach ($q->all() as $item) {
            /**
             * @var $item Job
             */
            $hours = \common\models\Task::find()->where(['job_id' => $item->id])->sum('hours');

            $out[] = [
                $item->internal_id,
                $item->name,
                $item->client->name,
                Yii::$app->formatter->asDate($item->created_at),
                \common\models\enum\JobStatus::getByValue($item->status)->text(),
                $item->manager->getFullName(),
                $item->budget_hours ? ($hours > 0 ? $hours : 0) . ' / ' . $item->budget_hours : $item->budget_hours
            ];
        }

        $doc->getActiveSheet()->fromArray($out);
        $doc->getActiveSheet()->getStyle('A1:' . $doc->getActiveSheet()->getHighestDataColumn() . '1')
            ->getFont()->setBold(true);
        $doc->getActiveSheet()->getStyle('A1:' . $doc->getActiveSheet()->getHighestDataColumn() . '1')
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $doc->getActiveSheet()->getStyle('A1:' . $doc->getActiveSheet()->getHighestDataColumn() . $doc->getActiveSheet()->getHighestRow())
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        foreach (range('A', $doc->getActiveSheet()->getHighestDataColumn()) as $col) {
            $doc->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }
        $doc->getActiveSheet()->setAutoFilter('A1:' . $doc->getActiveSheet()->getHighestDataColumn() . '1');


        $writer = IOFactory::createWriter($doc, 'Xls');
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return Yii::$app->response->sendContentAsFile($content, 'tys_projects_' . date('m.d.Y_h-i') . '.xls', [
            'mimeType' => 'application/vnd.ms-excel',
        ]);
    }

    public function actionGenerateExcel()
    {
        $doc = new Spreadsheet();
        $doc->setActiveSheetIndex(0);

        $q = \common\models\Task::find()
            ->select(['task.*', 'sum' => 'SUM(hours)'])
            ->orderBy(['date' => SORT_ASC])
            ->groupBy([
                'user_id',
                new Expression('CONCAT(job_id, \'_\', sub_job_id)'),
                'date',
                'description'
            ])
            ->with(['user', 'job', 'job.client'])
            ->leftJoin('job', ['job.id' => new Expression('task.job_id')])
            ->andFilterWhere(['job_id' => Yii::$app->request->get('job')])
            ->andFilterWhere(['sub_job_id' => Yii::$app->request->get('sub_job_id')]);
        if (is_array(Yii::$app->request->get('clients'))) {
            $q->andWhere(['IN', 'job.client_id', Yii::$app->request->get('clients', [])]);
        }
        if (is_array(Yii::$app->request->get('clientsExclude'))) {
            $q->andWhere(['NOT IN', 'job.client_id', Yii::$app->request->get('clientsExclude', [])]);
        }

        if (!empty(Yii::$app->request->get('from'))) {
            $q->andFilterWhere(['>=', 'date', date('Y-m-d', strtotime(Yii::$app->request->get('from')))]);
        }
        if (!empty(Yii::$app->request->get('to'))) {
            $q->andFilterWhere(['<=', 'date', date('Y-m-d', strtotime(Yii::$app->request->get('to')))]);
        }
        if (!empty(Yii::$app->request->get('entry_from'))) {
            $q->andFilterWhere(['>=', 'created_at', date('Y-m-d', strtotime(Yii::$app->request->get('entry_from')))]);
        }
        if (!empty(Yii::$app->request->get('entry_to'))) {
            $q->andFilterWhere(['<=', 'created_at', date('Y-m-d', strtotime(Yii::$app->request->get('entry_to')))]);
        }
        $out = [
            [
                'Client',
                'Work Date',
                'Work Type',
                'Work Detail',
                'Employee',
                'Title',
                'Hours',
                'Rate',
                'Fee',
                'TYS Job ID',
                'TYS Job Name',
                'TYS Sub Job Name',
                'TYS Task Entered Date',
                'TYS Task Description',
                'SCE Job ID',
                'SCE Job Name',
                'SCE Sub Job ID',
                'SCE Sub Job Name',
                'SCE Cost Centers',
                'SCE Requestor',
                'SCE Approver',
                'Accounting Notes'
            ]
        ];

        $sce_jobs = ArrayHelper::map(SCEProjectTracker::getJobs(), 'id', function ($item) {
            return $item;
        });

        foreach ($q->all() as $item) {
            /**
             * @var $item Task
             */

            $work_detail = "TYS JOB (" . $item->job->internal_id . "): " . $item->job->name . "\n";
            if ($item->subJob) {
                $work_detail .= "TYS SUB JOB ( " . $item->subJob->job_number . ")" . "\n";
            }

            if ($item->job->sce_job_number && isset($sce_jobs[$item->job->sce_job_number])) {
                $work_detail .= "SCE JOB (" . $sce_jobs[$item->job->sce_job_number]->legacy_id . "): " . $sce_jobs[$item->job->sce_job_number]->name . "\n";
            }
            if ($item->subJob && $item->subJob->sce_job_number && $sce_jobs[$item->subJob->sce_job_number]) {
                $work_detail .= "SCE SUB JOB (" . $sce_jobs[$item->subJob->sce_job_number]->legacy_id . "): " . $sce_jobs[$item->subJob->sce_job_number]->name . "\n";
            }
            $work_detail .= "WORK DESCRIPTION: " . $item->description;

            $tmp = [
                $item->job->client->name,
                date('m/d/Y', strtotime($item->date)),
                TaskItem::getByValue($item->task_item_id)->text(),
                $work_detail,
                $item->user->getShortName(),
                $item->user->title,
                $item->sum,
                $item->user->hourly_rate,
                $item->user->hourly_rate * $item->sum,
                $item->job->internal_id,
                $item->job->name,
                $item->subJob ? $item->subJob->job_number : '',
                date('m/d/Y h:i', strtotime($item->created_at)),
                $item->description

            ];

            if ($item->job->sce_job_number && isset($sce_jobs[$item->job->sce_job_number])) {
                array_push($tmp, $sce_jobs[$item->job->sce_job_number]->legacy_id,
                    $sce_jobs[$item->job->sce_job_number]->name);
            } else {
                array_push($tmp, '', '');
            }

            if ($item->subJob) {
                array_push($tmp,
                    $item->subJob->sce_job_number && $sce_jobs[$item->subJob->sce_job_number] ? $sce_jobs[$item->subJob->sce_job_number]->legacy_id : '',
                    $item->subJob->sce_job_number && $sce_jobs[$item->subJob->sce_job_number] ? $sce_jobs[$item->subJob->sce_job_number]->name : ''
                );
            } else {
                array_push($tmp, '', '');
            }

            if ($item->subJob) {
                if ($item->subJob->sce_job_number && isset($sce_jobs[$item->subJob->sce_job_number])) {
                    $tmp[] = implode("\n",
                        SCEProjectTracker::getCostCenters($item->subJob->sce_job_number));
                } else {
                    $tmp[] = '';
                }
            } elseif ($item->job->sce_job_number && isset($sce_jobs[$item->job->sce_job_number])) {
                $tmp[] = implode("\n",
                    SCEProjectTracker::getCostCenters($item->job->sce_job_number));
            } else {
                $tmp[] = '';
            }

            if ($item->subJob) {
                if ($item->subJob->sce_job_number && isset($sce_jobs[$item->subJob->sce_job_number])) {
                    $tmp[] = $sce_jobs[$item->subJob->sce_job_number]->creator->first_name . ' ' . $sce_jobs[$item->subJob->sce_job_number]->creator->last_name;
                } else {
                    $tmp[] = '';
                }
            } elseif ($item->job->sce_job_number && isset($sce_jobs[$item->job->sce_job_number])) {
                $tmp[] = $sce_jobs[$item->job->sce_job_number]->creator->first_name . ' ' . $sce_jobs[$item->job->sce_job_number]->creator->last_name;
            } else {
                $tmp[] = '';
            }

            if ($item->subJob) {
                if ($item->subJob->sce_job_number && isset($sce_jobs[$item->subJob->sce_job_number])) {
                    $tmp[] = $sce_jobs[$item->subJob->sce_job_number]->approver;

                } else {
                    $tmp[] = '';
                }
            } elseif ($item->job->sce_job_number && isset($sce_jobs[$item->job->sce_job_number])) {
                $tmp[] = $sce_jobs[$item->job->sce_job_number]->approver;
            } else {
                $tmp[] = '';
            }

            $tmp[] = $item->job->accounting_notes;

            $out[] = $tmp;
        }

        $doc->getActiveSheet()->fromArray($out);

        $doc->getActiveSheet()->getStyle('S1:S' . $doc->getActiveSheet()->getHighestRow())
            ->getAlignment()->setWrapText(true);

        $doc->getActiveSheet()->getStyle('T1:T' . $doc->getActiveSheet()->getHighestRow())
            ->getAlignment()->setWrapText(true);
        // detail column
        $doc->getActiveSheet()->getStyle('D1:D' . $doc->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
        // center everything vertically
        $doc->getActiveSheet()->getStyle('A1:' . $doc->getActiveSheet()->getHighestDataColumn() . $doc->getActiveSheet()->getHighestRow())
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // auto size all columns
        foreach (range('A', $doc->getActiveSheet()->getHighestDataColumn()) as $col) {
            $doc->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        $doc->getActiveSheet()->setAutoFilter('A1:' . $doc->getActiveSheet()->getHighestDataColumn() . '1');


        $writer = IOFactory::createWriter($doc, 'Xls');
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return Yii::$app->response->sendContentAsFile($content, 'tys_export_' . date('m.d.Y_h-i') . '.xls', [
            'mimeType' => 'application/vnd.ms-excel',
        ]);
    }
}
