<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/24/15 7:41 PM
 */

namespace frontend\components;


use common\models\Settings;
use Mobile_Detect;
use yii\filters\AccessControl;

class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ]
        ];
    }

    public function render($view, $params = [])
    {
        if (YII_DEBUG) {
            $this->view->registerMetaTag([
                'name' => 'robots',
                'content' => 'noindex'
            ]);
        }
        return parent::render($view, $params);
    }
}