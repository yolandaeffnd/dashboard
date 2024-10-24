<?php

namespace app\modules\maping\controllers;

use Yii;
use app\modules\maping\models\ProdiNasional;
use app\modules\maping\models\ProdiNasionalSearch;
use app\modules\maping\models\ProdiMap;
use app\modules\maping\models\ProgramStudi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;

/**
 * ProdiController implements the CRUD actions for ProdiNasional model.
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
                    'index' => ['POST', 'GET'],
                    'create' => ['POST', 'GET'],
                    'update' => ['POST', 'GET'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlMapingProdi'),
                ]
            ]
        ];
    }


    /**
     * Lists all ProdiNasional models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProdiNasionalSearch();
        $searchModel->load(\Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProdiNasional model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProdiNasional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ProdiNasional();
        $conn = new DAO();
        $dataSiregProdi = [];
        if ($model->load(Yii::$app->request->post())) {
            $qSiregProdi = "SELECT idProgramStudi,namaProgramStudi,namaJenjang FROM sireg_prodi WHERE idFak=:fak";
            $dataSiregProdi = $conn->QueryAll($qSiregProdi, [':fak' => $model->prodiFakId]);
            if (Yii::$app->request->post('btn-simpan')) {
                if ($model->save(0)) {
                    $jml = count($model->prodiMap);
                    $jmlEx = 0;
                    for ($i = 0; $i < $jml; $i++) {
                        $qCek = "SELECT * FROM sireg_prodi WHERE idProgramStudi=:id";
                        $rsCek = $conn->QueryRow($qCek, [':id' => $model->prodiMap[$i]]);
                        if (empty($rsCek)) {
                            $getProdi = ProgramStudi::findOne($model->prodiMap[$i]);
                            $qInsert = "INSERT INTO sireg_prodi(idProgramStudi,namaProgramStudi,idFak,idJenjang) "
                                    . "VALUE(:kode,:nama,:fak,:jenj)";
                            $conn->Execute($qInsert, [
                                ':kode' => $getProdi->idProgramStudi,
                                ':nama' => $getProdi->namaProgramStudi,
                                ':fak' => $getProdi->idFak,
                                ':jenj' => $getProdi->idJenjang
                            ]);
                        }
                        $query = "INSERT INTO ref_prodi_map(prodiKode,idProgramStudi) "
                                . "VALUE(:kode,:map)";
                        $result = $conn->Execute($query, [
                            ':kode' => $model->primaryKey,
                            ':map' => $model->prodiMap[$i]
                        ]);
                        if ($result == 1) {
                            $jmlEx = $jmlEx + 1;
                        }
                    }
                    if ($jml == $jmlEx) {
                        return $this->redirect(['index']);
                    }
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'dataSiregProdi' => $dataSiregProdi
        ]);
    }

    /**
     * Updates an existing ProdiNasional model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $conn = new DAO();
        $model = $this->findModel($id);
        $mapProdi = ProdiMap::find()
                ->where('prodiKode=:id', [':id' => $id])
                ->each();
        foreach ($mapProdi as $val) {
            $model->prodiMap[] = $val['idProgramStudi'];
        }
        $qSiregProdi = "SELECT idProgramStudi,namaProgramStudi,namaJenjang FROM sireg_prodi WHERE idFak=:fak";
        $dataSiregProdi = $conn->QueryAll($qSiregProdi, [':fak' => $model->prodiFakId]);
        if ($model->load(Yii::$app->request->post())) {
            $qSiregProdi = "SELECT idProgramStudi,namaProgramStudi,namaJenjang FROM sireg_prodi WHERE idFak=:fak";
            $dataSiregProdi = $conn->QueryAll($qSiregProdi, [':fak' => $model->prodiFakId]);
            if (Yii::$app->request->post('btn-simpan')) {
                if ($model->save(0)) {
                    if (!empty($mapProdi)) {
                        $conn->Execute('DELETE FROM ref_prodi_map WHERE prodiKode=:id', [':id' => $id]);
                    }
                    $jml = count($model->prodiMap);
                    $jmlEx = 0;
                    for ($i = 0; $i < $jml; $i++) {
                        $qCek = "SELECT * FROM sireg_prodi WHERE idProgramStudi=:id";
                        $rsCek = $conn->QueryRow($qCek, [':id' => $model->prodiMap[$i]]);
                        if (empty($rsCek)) {
                            $getProdi = ProgramStudi::findOne($model->prodiMap[$i]);
                            $qInsert = "INSERT INTO sireg_prodi(idProgramStudi,namaProgramStudi,idFak,idJenjang) "
                                    . "VALUE(:kode,:nama,:fak,:jenj)";
                            $conn->Execute($qInsert, [
                                ':kode' => $getProdi->idProgramStudi,
                                ':nama' => $getProdi->namaProgramStudi,
                                ':fak' => $getProdi->idFak,
                                ':jenj' => $getProdi->idJenjang
                            ]);
                        }
                        $query = "INSERT INTO ref_prodi_map(prodiKode,idProgramStudi) "
                                . "VALUE(:kode,:map)";
                        $result = $conn->Execute($query, [
                            ':kode' => $model->primaryKey,
                            ':map' => $model->prodiMap[$i]
                        ]);
                        if ($result == 1) {
                            $jmlEx = $jmlEx + 1;
                        }
                    }
                    if ($jml == $jmlEx) {
                        return $this->redirect(['index']);
                    }
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
                    'dataSiregProdi' => $dataSiregProdi
        ]);
    }

    /**
     * Deletes an existing ProdiNasional model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProdiNasional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ProdiNasional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProdiNasional::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
