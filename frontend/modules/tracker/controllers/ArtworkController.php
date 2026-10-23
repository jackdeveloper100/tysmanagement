<?php
namespace frontend\modules\tracker\controllers;

use common\models\ArtcompVersion;
use common\models\ArtcompVersionState;
use common\models\ArtworkComp;
use common\models\ArtworkItem;
use common\models\ArtworkItemState;
use frontend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ArtworkController extends Controller
{
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
            return $this->render('artwork_comp', ['model' => $model, 'version' => $version]);
        } else {
            $version = ArtcompVersion::find()->where(['artcomp_id' => $model->id])->orderBy(['id' => SORT_DESC])->one();
            if ($version) {
                return $this->redirect(['comp', 'id' => $id, 'version' => $version->id]);
            }
            return $this->render('artwork_comp_version_choose', ['model' => $model]);
        }
    }

    public function actionHistory($id, $version)
    {
        $model = ArtworkItem::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $version = ArtcompVersionState::find()->where([
            'artwork_item_id' => $model->id,
            'artcomp_version_id' => $version
        ])->one();
        if (!$version) {
            throw new NotFoundHttpException('Version state not found');
        }
        return $this->render('history', ['model' => $model, 'version' => $version]);
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
}
