<?php

namespace app\modules\referensi\controllers;

use Yii;
use app\modules\referensi\models\RefProdi;
use app\modules\referensi\models\RefProdiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;

/**
 * ProdiController implements the CRUD actions for RefProdi model.
 */
class ProdiController extends Controller {

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
                    User::userAccessRoles2('ctrlProgramStudi'),
                ]
            ]
        ];
    }

    /**
     * Lists all RefProdi models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RefProdiSearch();
        $searchModel->prodiNama = Yii::$app->request->get('prodi');
        $searchModel->prodiFakId = Yii::$app->request->get('fak');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RefProdi model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RefProdi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $inDate = new IndonesiaDate();
        $model = new RefProdi();
        $model->prodiCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RefProdi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $inDate = new IndonesiaDate();
        $model = $this->findModel($id);
        $model->prodiUpdate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RefProdi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RefProdi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RefProdi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RefProdi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
