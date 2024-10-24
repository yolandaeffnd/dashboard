<?php

namespace app\modules\aplikasi\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * ActionController implements the CRUD actions for AppAction model.
 */
class InfoController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['system'],
                'rules' => User::userAccessRoles('ctrlSystemInfo')
            ]
        ];
    }

    /**
     * Lists all AppAction models.
     * @return mixed
     */
    public function actionSystem() {
        return $this->renderAjax('system', []);
    }


}
