<?php
namespace backend\modules\tracker\controllers;

use backend\components\Controller;
use backend\modules\tracker\models\ArtcompVersionCreate;
use backend\modules\tracker\models\ArtworkCompForm;
use backend\modules\tracker\models\ArtworkCompRevisionForm;
use backend\modules\tracker\models\ArtworkItemNewForm;
use common\models\ArtcompVersion;
use common\models\ArtcompVersionState;
use common\models\ArtworkComp;
use common\models\ArtworkItem;
use common\models\ArtworkItemState;
use common\models\Job;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ArtworkController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['employee']
                    ]
                ],
            ]
        ];
    }

    public function actionComp($id)
    {
        $model = ArtworkComp::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if (\Yii::$app->request->get('version')) {
            $version = ArtcompVersion::findOne(\Yii::$app->request->get('version'));
            if (!$version) {
                throw new NotFoundHttpException('Version not found');
            }
            return $this->render('artwork_comp_version', ['model' => $model, 'version' => $version]);
        }
        return $this->render('artwork_comp', ['model' => $model]);
    }

    public function actionCreateComp($project)
    {
        $project = Job::findOne($project);
        if (!$project) {
            throw new NotFoundHttpException();
        }
        $model = new ArtworkCompForm();
        $model->model = new ArtworkComp(['project_id' => $project->id]);
        $model->load(\Yii::$app->request->post());
        if ($model->save()) {
            return $this->redirect(['comp', 'id' => $model->model->id]);
        } else {
            \Yii::$app->session->addFlash('error', 'Can\'t create artcomp');
            return $this->redirect(['jobs/view', 'id' => $project->id]);
        }
    }

    public function actionUpdateComp($id)
    {
        $model = ArtworkComp::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $form = new ArtworkCompForm();
        $form->model = $model;
        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            $form->image = UploadedFile::getInstance($form, 'image');
            if ($form->save()) {
                \Yii::$app->session->addFlash('success', 'Object updated');
                return $this->redirect(['comp', 'id' => $model->id]);
            }
        }

        return $this->render('comp_update', ['model' => $model]);
    }

    public function actionDeleteComp($id)
    {
        $model = ArtworkComp::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $model->delete();
        \Yii::$app->session->setFlash('success', 'Object removed');

        return $this->redirect(['jobs/view', 'id' => $model->project_id]);
    }

    public function actionDeleteItem($id)
    {
        $model = ArtworkItem::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $model->delete();
        \Yii::$app->session->setFlash('success', 'Object removed');

        return $this->redirect(['comp', 'id' => $model->comp->id]);
    }

    public function actionDeleteState($id)
    {
        $model = ArtworkItemState::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', 'Object removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        if (ArtworkItemState::find()->where(['item_id' => $model->item_id])->count() == 0) {
            return $this->redirect(['comp', 'id' => $model->item->comp->id]);
        } else {
            return $this->redirect(['history', 'id' => $model->item->id]);
        }
    }

    public function actionUpdateName($id)
    {
        $model = ArtworkItem::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $model->name = \Yii::$app->request->post('value');
        return $model->save();
    }

    public function actionUpload()
    {
        $form = new ArtworkItemNewForm();
        $form->load(\Yii::$app->request->post());
        $form->file = UploadedFile::getInstanceByName('file');
        if ($form->save()) {
            return "1";
        } else {
            throw new Exception();
        }
    }

    public function actionUploadRevision()
    {
        $form = new ArtworkCompRevisionForm();
        $form->setModel(new ArtworkItemState());
        $form->load(\Yii::$app->request->post());
        $form->file = UploadedFile::getInstanceByName('file');
        if ($form->save()) {
            return "1";
        } else {
            throw new Exception();
        }
    }

    public function actionHistory($id)
    {
        $model = ArtworkItem::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if (\Yii::$app->request->get('version')) {
            $version = ArtcompVersionState::find()->where([
                'artwork_item_id' => $model->id,
                'artcomp_version_id' => \Yii::$app->request->get('version')
            ])->one();
            if (!$version) {
                throw new NotFoundHttpException('Version state not found');
            }
            return $this->render('history', ['model' => $model, 'version' => $version]);
        }

        return $this->render('history', ['model' => $model, 'version' => null]);
    }

    public function actionPreview($id)
    {
        \Yii::$app->response->format = Response::FORMAT_RAW;
        $model = ArtworkItemState::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        header('Content-Type: ' . $model->getMimeType());
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($model->getFilePath()));
        readfile($model->getFilePath());
        \Yii::$app->end();
    }

    public function actionDownload($id)
    {
        \Yii::$app->response->format = Response::FORMAT_RAW;
        $model = ArtworkItemState::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($model->original_file_name) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($model->getFilePath()));
        readfile($model->getFilePath());
        \Yii::$app->end();
    }

    public function actionCreateVersion($id)
    {
        $model = ArtworkComp::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $version = new ArtcompVersionCreate();
        $version->load(\Yii::$app->request->post());
        if ($version->save($model)) {
            \Yii::$app->session->addFlash('success', 'New version created');
            return $this->redirect(['comp', 'id' => $id, 'version' => $version->getModel()->id]);
        }
        \Yii::$app->session->addFlash('error', 'Error occurred while creating version');
        return $this->redirect(['comp', 'id' => $id]);
    }
}
