<?php
namespace backend\modules\tracker\controllers;

use backend\components\Controller;
use common\models\UserDescriptionTemplate;
use yii\web\NotFoundHttpException;

class DescriptionTemplatesController extends Controller
{
    public function actionIndex()
    {
        return $this->render('list');
    }

    public function actionCreate()
    {
        return $this->actionUpdate(0, true);
    }

    public function actionUpdate($id, $new = false)
    {
        if ($new) {
            $model = new UserDescriptionTemplate();
            $model->user_id = \Yii::$app->user->id;
        } else {
            $model = UserDescriptionTemplate::findOne($id);
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', $model->isNewRecord ? 'Object created' : 'Object updated');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        /* @var $model UserDescriptionTemplate */
        $model = UserDescriptionTemplate::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', 'Object removed');
        } else {
            \Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['index']);
    }
}
