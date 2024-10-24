<?php

namespace app\modules\referensi\controllers;

use Yii;
use app\modules\referensi\models\RefAngkatan;
use app\modules\referensi\models\RefAngkatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;

/**
 * AngkatanController implements the CRUD actions for RefAngkatan model.
 */
class AngkatanController extends Controller {

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
                    User::userAccessRoles2('ctrlAngkatan'),
                ]
            ]
        ];
    }

    /**
     * Lists all RefAngkatan models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RefAngkatanSearch();
        $searchModel->angkatanNama = Yii::$app->request->get('angkatan');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RefAngkatan model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RefAngkatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new RefAngkatan();

        if ($model->load(Yii::$app->request->post())) {
            $model->angkatan = $model->angkatanNama;
            try {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            } catch (\yii\db\Exception $e) {
                if ($e->getCode() == 23000) {
                    $model->addError('angkatanNama', 'Angkatan yang anda entrikan sudah ada!');
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RefAngkatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->angkatan = $model->angkatanNama;
            try {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            } catch (\yii\db\Exception $e) {
                if ($e->getCode() == 23000) {
                    $model->addError('angkatanNama', 'Angkatan yang anda entrikan sudah ada!');
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RefAngkatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RefAngkatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RefAngkatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RefAngkatan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
