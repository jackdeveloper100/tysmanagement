<?php
namespace backend\modules\tracker\controllers;

use backend\components\Controller;
use common\models\Job;
use common\models\SCEProjectTracker;
use common\models\SubJob;
use backend\modules\tracker\models\SubJobForm;
use backend\modules\tracker\models\SubJobsSearch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Yii;
use yii\web\NotFoundHttpException;

class SubJobsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex($job)
    {
        $parent = Job::findOne($job);
        if (!$parent) {
            throw new NotFoundHttpException('Job not found');
        }
        $searchModel = new SubJobsSearch();
        $searchModel->soft_deleted = 0;
        $dataProvider = $searchModel->search($job, \Yii::$app->request->get());

        return $this->render('list',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'parent' => $parent]);
    }

    public function actionCreate($job)
    {
        return $this->actionUpdate(0, $job, true);
    }

    public function actionUpdate($id, $job = 0, $new = false)
    {
        if ($new) {
            $parent = Job::findOne($job);
            if (!$parent) {
                throw new NotFoundHttpException('Job not found');
            }

            $model = new SubJob();
            $model->job_id = $job;
        } else {
            $model = SubJob::findOne($id);
            $parent = $model->job;
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $form = new SubJobForm();
        $form->setModel($model);

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->save()) {
                \Yii::$app->session->setFlash('success', $model->isNewRecord ? 'Object created' : 'Object updated');
                return $this->redirect(['index', 'job' => $parent->id]);
            }
        }
        return $this->render('update', ['model' => $form, 'parent' => $parent]);
    }

    public function actionDelete($id)
    {
        /* @var $model SubJob */
        $model = SubJob::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', 'Object removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['index', 'job' => $model->job_id]);
    }

    public function actionExport($job)
    {
        $parent = Job::findOne($job);
        if (!$parent) {
            throw new NotFoundHttpException('Job not found');
        }

        /**
         * @var $models SubJob[]
         */
        $models = SubJob::find()->where(['job_id' => $parent->id])->orderBy(['created_at' => SORT_ASC])->all();

        $doc = new Spreadsheet();
        $doc->setActiveSheetIndex(0);

        $out = [
            [
                'Main job TYS ID',
                'Main job name',
                'Sub Job Name',
                'SCE Job ID',
                'SCE Job',
                'Created At'
            ]
        ];

        $sceJobs = SCEProjectTracker::getJobs();

        foreach ($models as $item) {
            $out[] = [
                $item->job->internal_id,
                $item->job->name,
                $item->job_number,
                !empty($item->sce_job_number) ? $sceJobs[$item->sce_job_number]->legacy_id : 'Not Set',
                !empty($item->sce_job_number) ? $sceJobs[$item->sce_job_number]->name : 'Not Set',
                Yii::$app->formatter->asDateTime($item->created_at)
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
}
