<?php

namespace app\modules\pelatihan\controllers;

use Yii;
use app\modules\pelatihan\models\LatKelas;
use app\modules\pelatihan\models\LatKelasSearch;
use app\modules\pelatihan\models\LatKelasInstruktur;
use app\modules\pelatihan\models\LatJadwal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;
use yii\data\ActiveDataProvider;

/**
 * KelasController implements the CRUD actions for LatKelas model.
 */
class KelasController extends Controller {

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
                    User::userAccessRoles2('ctrlKelasPelatihan'),
                ]
            ]
        ];
    }

    /**
     * Lists all LatKelas models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new LatKelasSearch();
        $searchModel->klsNama = Yii::$app->request->get('kls');
        $searchModel->klsPeriodeId = Yii::$app->request->get('periode');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LatKelas model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $query = LatJadwal::find()
                ->where('jdwlKlsId=:id', [
            ':id' => $model->klsId
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('view', [
                    'model' => $model,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new LatKelas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $inDate = new IndonesiaDate();
        $conn = new DAO();
        $model = new LatKelas();
        $model->klsCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $model->klsId = $this->generateKode($model->klsPeriodeId);
            if ($model->save()) {
                //Instruktur
                $jmlInst = 0;
                if (!empty($model->klsInstId)) {
                    $inst = count($model->klsInstId);
                    $qDel = "DELETE FROM lat_kelas_instruktur WHERE klsId=:id";
                    $conn->Execute($qDel, [':id' => $model->klsId]);
                    for ($i = 0; $i < count($model->klsInstId); $i++) {
                        $qInsert = "INSERT INTO lat_kelas_instruktur VALUE(:kls,:inst)";
                        $rsInsert = $conn->Execute($qInsert, [':kls' => $model->klsId, ':inst' => $model->klsInstId[$i]]);
                        if ($rsInsert == 1) {
                            $jmlInst = $jmlInst + 1;
                        }
                    }
                } else {
                    $inst = 0;
                }
                if ($inst == $jmlInst) {
                    return $this->redirect(['view', 'id' => $model->klsId]);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing LatKelas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $inDate = new IndonesiaDate();
        $conn = new DAO();
        $model = $this->findModel($id);
        $modelKlsInst = LatKelasInstruktur::find()->where('klsId=:id', [':id' => $id])->each();
        $instruk = [];
        foreach ($modelKlsInst as $val) {
            $instruk[] = $val['instId'];
        }
        $model->klsInstId = $instruk;
        $model->klsUpdate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Instruktur
            $jmlInst = 0;
            if (!empty($model->klsInstId)) {
                $inst = count($model->klsInstId);
                $qDel = "DELETE FROM lat_kelas_instruktur WHERE klsId=:id";
                $conn->Execute($qDel, [':id' => $model->klsId]);
                for ($i = 0; $i < count($model->klsInstId); $i++) {
                    $qInsert = "INSERT INTO lat_kelas_instruktur VALUE(:kls,:inst)";
                    $rsInsert = $conn->Execute($qInsert, [':kls' => $model->klsId, ':inst' => $model->klsInstId[$i]]);
                    if ($rsInsert == 1) {
                        $jmlInst = $jmlInst + 1;
                    }
                }
            } else {
                $qDel = "DELETE FROM lat_kelas_instruktur WHERE klsId=:id";
                $conn->Execute($qDel, [':id' => $model->klsId]);
                $inst = 0;
            }
            if ($inst == $jmlInst) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LatKelas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LatKelas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return LatKelas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = LatKelas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function generateKode($periode) {
        $conn = new DAO();
        $qCek = "SELECT * FROM counter_kelas WHERE periodeId=:periode";
        $rsCek = $conn->QueryRow($qCek, [':periode' => $periode]);
        $PRD = sprintf("%05s", $periode);
        if (empty($rsCek)) {
            $nextUrutan = sprintf("%05s", 1);
            $kode = 'K' . $PRD . $nextUrutan;
            $qInsert = "INSERT INTO counter_kelas VALUE(:periode,:urut)";
            $conn->Execute($qInsert, [
                ':urut' => $nextUrutan,
                ':periode' => $periode
            ]);
        } else {
            $nextUrutan = sprintf("%05s", (1 + (int) $rsCek['kelasUrut']));
            $kode = 'K' . $PRD . $nextUrutan;
            $qUpdate = "UPDATE counter_kelas SET kelasUrut=:urut WHERE periodeId=:periode";
            $conn->Execute($qUpdate, [
                ':urut' => $nextUrutan,
                ':periode' => $periode
            ]);
        }
        return $kode;
    }

}
