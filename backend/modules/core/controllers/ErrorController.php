<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/8/15 7:32 PM
 */

namespace backend\modules\core\controllers;


use yii\web\Controller;

class ErrorController extends Controller
{
    public $layout = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}