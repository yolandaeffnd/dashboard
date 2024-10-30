<?php

namespace app\modules\aplikasi\controllers;

use Yii;
use app\modules\aplikasi\models\AppChart;
use app\modules\aplikasi\models\AppKategori;
use app\models\RefFakultas;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * KategoriController implements the CRUD actions for AppMenu model.
 */
class KategoriController extends Controller {

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
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => User::userAccessRoles('ctrlModulMenu')
            ]
        ];
    }

    /**
     * Lists all AppKategori models.
     * @return mixed
     */
    public function actionIndex() {
        

        $query = AppKategori::find();
        $query->select([
            '*',
        ]);
        $query->orderBy('idKategori');
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key'=>'idKategori'
        ]);



        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppMenu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $query = AppKategori::find()->where(['idMenu' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('view', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new AppKategori();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idKategori]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    // 
    public function actionUpdate($id) {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->redirect(['view', 'id' => $model->idKategori]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id) {
        if (($model = AppKategori::findOne(['idKategori'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
