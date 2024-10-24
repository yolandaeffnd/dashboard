<?php

namespace app\modules\referensi\controllers;

use Yii;
use app\modules\referensi\models\RefJenisPelatihan;
use app\modules\referensi\models\RefJenisPelatihanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;

/**
 * JenispelatihanController implements the CRUD actions for RefJenisPelatihan model.
 */
class JenispelatihanController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'index' => ['POST', 'GET'],
                    'create' => ['POST', 'GET'],
                    'update' => ['POST', 'GET'],
                    'view' => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                    'view',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlJenisPelatihan'),
                ]
            ]
        ];
    }

    /**
     * Lists all RefJenisPelatihan models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RefJenisPelatihanSearch();
        $searchModel->jnslatNama = Yii::$app->request->get('jnslat');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RefJenisPelatihan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RefJenisPelatihan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $inDate = new IndonesiaDate();
        $model = new RefJenisPelatihan();
        $model->jnslatCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RefJenisPelatihan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $inDate = new IndonesiaDate();
        $model = $this->findModel($id);
        $model->jnslatUpdate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RefJenisPelatihan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RefJenisPelatihan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RefJenisPelatihan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RefJenisPelatihan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}