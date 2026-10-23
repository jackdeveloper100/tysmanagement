<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/8/15 9:19 PM
 */

namespace backend\modules\core\controllers;


use backend\components\Controller;
use yii\filters\AccessControl;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        return $this->render("view");
    }
}