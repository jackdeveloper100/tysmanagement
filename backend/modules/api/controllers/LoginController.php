<?php
namespace backend\modules\api\controllers;

use backend\modules\api\models\LoginForm;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class LoginController extends Controller
{
    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = \yii\rest\Serializer::class;
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'register' => ['post'],
                    'login' => ['post']
                ],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    'Access-Control-Allow-Origin' => ['*'],
                    'Origin' => ['*'],
                    'Access-Control-Expose-Headers' => [
                        'X-Pagination-Per-Page',
                        'X-Pagination-Total-Count',
                        'X-Pagination-Current-Page',
                        'X-Pagination-Page-Count',
                    ],
                ],

            ]
        ]);
    }

    public function actionLogin()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new LoginForm();
        $form->setAttributes(\Yii::$app->request->getBodyParams());
        if ($form->validate()) {
            $form->getUser()->updateAccessToken();
            \Yii::$app->getResponse()->setStatusCode(200);
            return $form->getUser();
        }
        return $form;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        return $this->serializeData($result);
    }

    /**
     * Serializes the specified data.
     * The default implementation will create a serializer based on the configuration given by [[serializer]].
     * It then uses the serializer to serialize the given data.
     * @param mixed $data the data to be serialized
     * @return mixed the serialized data.
     */
    protected function serializeData($data)
    {
        return \Yii::createObject($this->serializer)->serialize($data);
    }
}
