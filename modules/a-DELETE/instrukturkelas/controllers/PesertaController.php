<?php

namespace app\modules\instrukturkelas\controllers;

use Yii;
use app\modules\instrukturkelas\models\LatPeserta;
use app\modules\instrukturkelas\models\LatPesertaSearch;
use app\modules\instrukturkelas\models\LatKelas;
use app\modules\instrukturkelas\models\LatJadwal;
use app\modules\instrukturkelas\models\LatPesertaAbsen;
use app\modules\instrukturkelas\models\RefHari;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\Json;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;
use yii\data\ActiveDataProvider;

/**
 * PesertaController implements the CRUD actions for LatPeserta model.
 */
class PesertaController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                    'index' => ['POST', 'GET'],
                    'create' => ['POST', 'GET'],
                    'update' => ['POST', 'GET'],
                    'view' => ['GET', 'POST'],
                    'simpanpeserta' => ['GET'],
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
                    'simpanpeserta',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlInstrukturPesertaKelas'),
                        [
                        'allow' => true,
                        'actions' => [
                            'simpanpeserta',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all LatPeserta models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new LatPesertaSearch();
        $searchModel->klsPeriodeId = Yii::$app->request->get('periode');
        $searchModel->klsNama = Yii::$app->request->get('kls');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->searchKelas(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LatPeserta model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $conn = new DAO();
        $inDate = new IndonesiaDate();
        $model = LatKelas::findOne($id);

        //Data Jadwal
        $query = LatJadwal::find()
                ->where('jdwlKlsId=:id', [
            ':id' => $model->klsId
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        //Data Peserta
        $modelPeserta = new LatPeserta();
        $modelPeserta->absenJdwlId = \Yii::$app->request->get('jdwl');
        $modelPeserta->absenTgl = \Yii::$app->request->get('tgl');
        $modelPeserta->pesertaId = \Yii::$app->request->get('peserta');
        $modelPeserta->pesertaNama = \Yii::$app->request->get('nama');
        $hariIsNotMatch = '';
        if ($modelPeserta->load(\Yii::$app->request->post())) {
            //Get Hari DAY
            $qGetHari = "SELECT DAYNAME(:tgl) AS hari";
            $rsGetHari = $conn->QueryRow($qGetHari, [':tgl' => $modelPeserta->absenTgl]);
            $hari = $rsGetHari['hari'];
            //Get Hari Jadwal
            $hariJadwal = LatJadwal::find()
                            ->join('JOIN', 'ref_hari', 'ref_hari.hariKode=lat_jadwal.jdwlHariKode')
                            ->where('lat_jadwal.jdwlId=:jdwl AND lat_jadwal.jdwlKlsId=:kls', [
                                ':jdwl' => $modelPeserta->absenJdwlId,
                                ':kls' => $model->klsId
                            ])->one();
            if ($hariJadwal->jdwlHariKode != $rsGetHari['hari']) {
                $hariIsNotMatch = 1;
                $modelPeserta->addError('absenJdwlId', 'Maaf, Jadwal dan tanggal pertemuan tidak sesuai! Silahkan cek kembali.');
                $modelPeserta->addError('absenTgl', 'Maaf, Jadwal dan tanggal pertemuan tidak sesuai! Silahkan cek kembali.');
            } else {
                $hariIsNotMatch = '';
                return $this->redirect([
                            'view',
                            'id' => $model->klsId,
                            'jdwl' => $modelPeserta->absenJdwlId,
                            'tgl' => $modelPeserta->absenTgl,
                            'peserta' => $modelPeserta->pesertaId,
                            'nama' => $modelPeserta->pesertaNama
                ]);
            }
        }
        $dataProviderPeserta = '';
        $hari = '';
        if ($modelPeserta->absenJdwlId != '' && $modelPeserta->absenTgl != '' && $hariIsNotMatch == '') {
            //Get Hari DAY
            $qGetHari = "SELECT DAYNAME(:tgl) AS hari";
            $rsGetHari = $conn->QueryRow($qGetHari, [':tgl' => $modelPeserta->absenTgl]);
            $hari = $rsGetHari['hari'];
            //Get Hari Jadwal
            $hariJadwal = LatJadwal::find()
                            ->join('JOIN', 'ref_hari', 'ref_hari.hariKode=lat_jadwal.jdwlHariKode')
                            ->where('ref_hari.hariKode=:kode AND lat_jadwal.jdwlKlsId=:kls', [
                                ':kode' => $rsGetHari['hari'],
                                ':kls' => $model->klsId
                            ])->one();

            $queryPeserta = LatPeserta::find()
                    ->select([
                        '*',
                        'jdwlId AS absenJdwlId'
                    ])
                    ->join('JOIN', 'member', 'member.memberId=lat_peserta.pesertaMemberId')
                    ->join('JOIN', 'lat_kelas', 'lat_kelas.klsId=lat_peserta.pesertaKlsId')
                    ->join('JOIN', 'lat_jadwal', 'lat_jadwal.jdwlKlsId=lat_kelas.klsId')
                    ->where('pesertaKlsId=:kls AND jdwlId=:jdwl', [
                ':kls' => $model->klsId,
                ':jdwl' => empty($hariJadwal->jdwlId) ? '' : $hariJadwal->jdwlId,
            ]);
            if ($modelPeserta->pesertaId != '' || $modelPeserta->pesertaNama != '') {
                $queryPeserta->andWhere('pesertaId LIKE :id AND memberNama LIKE :nama', [
                    ':id' => '%' . $modelPeserta->pesertaId . '%',
                    ':nama' => '%' . $modelPeserta->pesertaNama . '%'
                ]);
            }

            $dataProviderPeserta = new ActiveDataProvider([
                'query' => $queryPeserta,
                'pagination' => [
                    'pageSize' => 20,
                    'page' => Yii::$app->request->get('page') - 1,
                    'params' => [
                        'id' => $id,
                        'jdwl' => $modelPeserta->absenJdwlId,
                        'tgl' => $modelPeserta->absenTgl,
                        'peserta' => $modelPeserta->pesertaId,
                        'nama' => $modelPeserta->pesertaNama
                    ]
                ]
            ]);
        }
        return $this->render('view', [
                    'model' => $model,
                    'modelPeserta' => $modelPeserta,
                    'dataProvider' => $dataProvider,
                    'dataProviderPeserta' => $dataProviderPeserta,
                    'absenJdwl' => $modelPeserta->absenJdwlId,
                    'absenTgl' => $modelPeserta->absenTgl,
                    'hari' => $hari,
                    'hariIsNotMatch' => $hariIsNotMatch
        ]);
    }

    /**
     * Creates a new LatPeserta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new LatPeserta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pesertaId]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LatPeserta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pesertaId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LatPeserta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        if (\Yii::$app->request->isAjax) {
            $result = $this->findModel($id)->delete();
            if ($result) {
                return 410;
            }
        }
    }

    /**
     * Finds the LatPeserta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return LatPeserta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = LatPeserta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSimpanpeserta($act, $params) {
        if (\Yii::$app->request->isAjax) {
            $conn = new DAO();
            $inDate = new IndonesiaDate();
            $param = unserialize(urldecode($params));
            if ($act == 'set-hadir') {
                //Set Hadir Per Personal
                $qCek = "SELECT * FROM lat_peserta_absen "
                        . "WHERE absenPesertaId=:peserta AND absenJdwlId=:jdwl AND absenTgl=:tgl";
                $rsCek = $conn->QueryAll($qCek, [
                    ':peserta' => $param['id'],
                    ':jdwl' => \Yii::$app->request->get('jdwl'),
                    ':tgl' => \Yii::$app->request->get('tgl'),
                ]);
                if (empty($rsCek)) {
                    $query = "INSERT INTO lat_peserta_absen VALUE(:peserta,:jdwl,:tgl,:ishadir,:buat)";
                    $result = $conn->Execute($query, [
                        ':peserta' => $param['id'],
                        ':jdwl' => \Yii::$app->request->get('jdwl'),
                        ':tgl' => \Yii::$app->request->get('tgl'),
                        ':ishadir' => '1',
                        ':buat' => $inDate->getNow()
                    ]);
                    if ($result == 1) {
                        return 410;
                    } else {
                        return 401;
                    }
                } else {
                    return 400;
                }
            } else if ($act == 'set-tidak-hadir') {
                //Set Tidak Hadir Per Personal
                $qCek = "SELECT * FROM lat_peserta_absen "
                        . "WHERE absenPesertaId=:peserta AND absenJdwlId=:jdwl AND absenTgl=:tgl";
                $rsCek = $conn->QueryAll($qCek, [
                    ':peserta' => $param['id'],
                    ':jdwl' => \Yii::$app->request->get('jdwl'),
                    ':tgl' => \Yii::$app->request->get('tgl'),
                ]);
                if (empty($rsCek)) {
                    $query = "INSERT INTO lat_peserta_absen VALUE(:peserta,:jdwl,:tgl,:ishadir,:buat)";
                    $result = $conn->Execute($query, [
                        ':peserta' => $param['id'],
                        ':jdwl' => \Yii::$app->request->get('jdwl'),
                        ':tgl' => \Yii::$app->request->get('tgl'),
                        ':ishadir' => '0',
                        ':buat' => $inDate->getNow()
                    ]);
                    if ($result == 1) {
                        return 410;
                    } else {
                        return 401;
                    }
                } else {
                    return 400;
                }
            } else if ($act == 'reset-absen') {
                $query = "DELETE FROM lat_peserta_absen "
                        . "WHERE absenPesertaId=:peserta AND absenJdwlId=:jdwl AND absenTgl=:tgl";
                $result = $conn->Execute($query, [
                    ':peserta' => $param['id'],
                    ':jdwl' => Yii::$app->request->get('jdwl'),
                    ':tgl' => Yii::$app->request->get('tgl'),
                ]);
                if ($result == 1) {
                    return 410;
                } else {
                    return 401;
                }
            }
        }
    }

}
