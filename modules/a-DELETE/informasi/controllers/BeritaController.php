<?php

namespace app\modules\informasi\controllers;

use Yii;
use app\modules\informasi\models\Informasi;
use app\modules\informasi\models\InformasiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;

/**
 * InformasiController implements the CRUD actions for Informasi model.
 */
class BeritaController extends Controller {

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
                    User::userAccessRoles2('ctrlBerita'),
                ]
            ]
        ];
    }

    /**
     * Lists all Informasi models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new InformasiSearch();
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Informasi model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Informasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Informasi();
        $inDate = new IndonesiaDate();
        $model->infoCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $model->infoAlias = $this->GenerateAlias($model->infoJudul);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->infoId]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Informasi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $inDate = new IndonesiaDate();
        $model->infoUpdate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $model->infoAlias = $this->GenerateAlias($model->infoJudul);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->infoId]);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Informasi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Informasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Informasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Informasi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function GenerateAlias($judul) {
        $conn = new DAO();
        $judul2 = strtolower(str_replace(' ', '-', $judul));
        $qCek = "SELECT * FROM informasi WHERE infoAlias=:alias";
        $rsCek = $conn->QueryAll($qCek, [':alias' => $judul2]);
        if (empty($rsCek)) {
            $alias = $judul2;
        } else {
            $urut = count($rsCek) + 1;
            $alias = $judul2 . '-' . $urut;
        }
        return $alias;
    }

}
