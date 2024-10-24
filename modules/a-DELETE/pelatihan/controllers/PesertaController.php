<?php

namespace app\modules\pelatihan\controllers;

use Yii;
use app\modules\pelatihan\models\LatPeserta;
use app\modules\pelatihan\models\LatPesertaSearch;
use app\modules\pelatihan\models\LatKelas;
use app\modules\pelatihan\models\LatJadwal;
use app\modules\pelatihan\models\LatPesertaAbsen;
use app\modules\pelatihan\models\LatPeriode;
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
                    'absenonline' => ['POST', 'GET'],
                    'checkpeserta' => ['POST', 'GET'],
                    'simpanpeserta' => ['GET'],
                    'getkehadiran' => ['GET'],
                    'batalpeserta' => ['GET'],
                    'countabsensi' => ['GET'],
                    'printblankoabsen' => ['GET'],
                    'absenglobal' => ['POST', 'GET'],
                    'checkpesertaglobal' => ['POST', 'GET'],
                    'pindahkelas' => ['POST', 'GET'],
                    'importabsen' => ['POST', 'GET'],
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
                    'absenonline',
                    'checkpeserta',
                    'simpanpeserta',
                    'getkehadiran',
                    'batalpeserta',
                    'countabsensi',
                    'printblankoabsen',
                    'absenglobal',
                    'checkpesertaglobal',
                    'pindahkelas',
                    'importabsen',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlPesertaPelatihan'),
                        [
                        'allow' => true,
                        'actions' => [
                            'checkpeserta',
                            'simpanpeserta',
                            'getkehadiran',
                            'batalpeserta',
                            'countabsensi',
                            'checkpesertaglobal',
                            'pindahkelas',
                            'delete',
                            'importabsen',
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
        $modelPeserta->load(\Yii::$app->request->post());
        $queryPeserta = LatPeserta::find()
                ->join('JOIN', 'member', 'member.memberId=lat_peserta.pesertaMemberId')
                ->where('pesertaKlsId=:kls', [
            ':kls' => $model->klsId
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
                    'nopes' => $modelPeserta->pesertaId,
                    'nama' => $modelPeserta->pesertaNama
                ]
            ]
        ]);
        return $this->render('view', [
                    'model' => $model,
                    'modelPeserta' => $modelPeserta,
                    'dataProvider' => $dataProvider,
                    'dataProviderPeserta' => $dataProviderPeserta
        ]);
    }

    public function actionPindahkelas($id, $klsasal) {
        if (\Yii::$app->request->isAjax) {
            $modelPeserta = LatPeserta::findOne($id);
            $periodeId = LatKelas::findOne($modelPeserta->pesertaKlsId)->klsPeriodeId;
            if (!empty($modelPeserta)) {
                return $this->renderAjax('dialogPindahKelas', [
                            'modelPeserta' => $modelPeserta,
                            'periodeId' => $periodeId,
                            'klsasal' => $klsasal
                ]);
            }
        }
    }

    public function actionSetpindahkelas($id) {
        if (\Yii::$app->request->isAjax) {
            $conn = new DAO();
            $modelPeserta = new LatPeserta();
            $modelPeserta->load(\Yii::$app->request->get());
            $query = "UPDATE lat_peserta SET pesertaKlsId=:kls WHERE pesertaId=:id";
            $result = $conn->Execute($query, [
                ':kls' => $modelPeserta->pesertaKlsId,
                ':id' => $id
            ]);
            if ($result) {
                return 410;
            }
        }
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

    public function actionImportabsen($id) {
        $inDate = new IndonesiaDate();
        $model = LatKelas::findOne($id);
        $conn = new DAO();
        $modelAbsen = new LatPesertaAbsen();
        $hariTglMatch = '';
        $modelAbsen->absenJdwlId = Yii::$app->request->get('jdwl');
        $modelAbsen->absenTgl = Yii::$app->request->get('tgl');
        if ($modelAbsen->load(\Yii::$app->request->post())) {
            //Get Hari DAY
            $qGetHari = "SELECT DAYNAME(:tgl) AS hari";
            $rsGetHari = $conn->QueryRow($qGetHari, [':tgl' => $modelAbsen->absenTgl]);
            //Get Hari Jadwal
            $hariJadwal = LatJadwal::findOne($modelAbsen->absenJdwlId);
            if ($hariJadwal->jdwlHariKode == $rsGetHari['hari']) {
                $hariTglMatch = '1';
                $namaFile = $this->gantiFileName('ABSENSI');
                $modelAbsen->absenFile = UploadedFile::getInstance($modelAbsen, 'absenFile');
                $excStatusKode = '';
                $excStatusMsg = '';
                if ($modelAbsen->absenFile != null) {
                    $uploaded = $modelAbsen->absenFile->saveAs(Yii::getAlias('@webroot/../berkas/berkas-absensi/') . $namaFile . '.' . $modelAbsen->absenFile->extension);
                    if ($uploaded) {
                        //Path filename
                        $filename = Yii::getAlias('@webroot/../berkas/berkas-absensi/') . $namaFile . '.' . $modelAbsen->absenFile->extension;
                        $trans = $conn->beginTransaction();
                        try {
                            //Mengidentifikasi Filename
                            $readerType = \PHPExcel_IOFactory::identify($filename);
                            //Membaca Filename berdasarkan Type File
                            $objReader = \PHPExcel_IOFactory::createReader($readerType);
                            //Meload filename
                            $objPHPExcel = $objReader->load($filename);
                            //Membaca Sheet 1 Array 1
                            $sheet = $objPHPExcel->getSheet(0);
                            //Membaca Banyak Baris atau Baris Terakhir Sheet 2 Array 1
                            $highestRow = $sheet->getHighestRow();
                            //Membaca Kolom Terakhir atau Tertinggi Sheet 2 Array 1
                            $highestColumn = $sheet->getHighestColumn();
                            //Count Baris
                            $jmlData = $highestRow - 3;
                            $jmlExcSuccess = 0;
                            $jmlExcExist = 0;
                            //Looping Data Array Sheet 2 Array 1
                            for ($row = 4; $row <= $highestRow; ++$row) {
                                //Membaca Array dari Excel Dengan batasan Tertentu Exp : A - Kolom Tertinggi
                                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                                //Cek Absensi
                                $qCek = "SELECT * FROM lat_peserta_absen "
                                        . "WHERE absenPesertaId=:peserta AND absenJdwlId=:jdwl AND absenTgl=:tgl";
                                $rsCek = $conn->QueryRow($qCek, [
                                    ':peserta' => $rowData[0][1],
                                    ':jdwl' => $modelAbsen->absenJdwlId,
                                    ':tgl' => $modelAbsen->absenTgl
                                ]);
                                if (empty($rsCek)) {
                                    //Cek Peserta Bayar atau Gratis
                                    $peserta = LatPeserta::find()
                                            ->where("pesertaId=:peserta AND ((pesertaIsFree='1' AND pesertaIsPaid='0') OR (pesertaIsFree='0' AND pesertaIsPaid='1'))", [
                                                ':peserta' => $modelAbsen->absenPesertaId
                                            ])
                                            ->count();
                                    if ($peserta > 0) {
                                        //Insert Absensi Hadir
                                        $qInsert = "INSERT INTO lat_peserta_absen(absenPesertaId,absenJdwlId,absenTgl,absenIsHadir,absenCreate) "
                                                . "VALUE(:peserta,:jdwl,:tgl,:hadir,:buat)";
                                        $rsInsert = $conn->Execute($qInsert, [
                                            ':peserta' => $rowData[0][1],
                                            ':jdwl' => $modelAbsen->absenJdwlId,
                                            ':tgl' => $modelAbsen->absenTgl,
                                            ':hadir' => '1',
                                            ':buat' => $inDate->getNow()
                                        ]);
                                        if ($rsInsert == 1) {
                                            $jmlExcSuccess = $jmlExcSuccess + 1;
                                        }
                                    }
                                } else {
                                    $jmlExcExist = $jmlExcExist + 1;
                                }
                            }
                            //Cek Belum Absen
                            $qCekBelumAbsen = "SELECT * FROM `lat_peserta` p "
                                    . "LEFT JOIN `lat_peserta_absen` pa ON pa.`absenPesertaId`=p.`pesertaId` AND pa.`absenJdwlId`=:jdwl AND pa.`absenTgl`=:tgl "
                                    . "WHERE p.pesertaKlsId=:kls AND pa.`absenPesertaId` IS NULL";
                            $rsCekBelumAbsen = $conn->QueryAll($qCekBelumAbsen, [
                                ':jdwl' => $modelAbsen->absenJdwlId,
                                ':tgl' => $modelAbsen->absenTgl,
                                ':kls' => $model->klsId
                            ]);
                            foreach ($rsCekBelumAbsen as $val) {
                                //Cek Peserta Bayar atau Gratis
                                $peserta = LatPeserta::find()
                                        ->where("pesertaId=:peserta AND ((pesertaIsFree='1' AND pesertaIsPaid='0') OR (pesertaIsFree='0' AND pesertaIsPaid='1'))", [
                                            ':peserta' => $modelAbsen->absenPesertaId
                                        ])
                                        ->count();
                                if ($peserta > 0) {
                                    //Insert Absensi Tidak Hadir
                                    $qInsert = "INSERT INTO lat_peserta_absen(absenPesertaId,absenJdwlId,absenTgl,absenIsHadir,absenCreate) "
                                            . "VALUE(:peserta,:jdwl,:tgl,:hadir,:buat)";
                                    $rsInsert = $conn->Execute($qInsert, [
                                        ':peserta' => $val['pesertaId'],
                                        ':jdwl' => $modelAbsen->absenJdwlId,
                                        ':tgl' => $modelAbsen->absenTgl,
                                        ':hadir' => '0',
                                        ':buat' => $inDate->getNow()
                                    ]);
                                }
                            }

                            if ($jmlData == ($jmlExcExist + $jmlExcSuccess)) {
                                $trans->commit();
                                $excStatusKode = '410';
                                $excStatusMsg = 'Import data absensi berhasil...';
                            } else {
                                $trans->rollBack();
                                $excStatusKode = '401';
                                $excStatusMsg = 'Gagal Import data absensi!';
                            }
                        } catch (Exception $ex) {
                            $trans->rollBack();
                            $excStatusKode = '401';
                            $excStatusMsg = 'Gagal Import data absensi!';
                        }
                    }
                }
                if ($excStatusKode == '410') {
                    Yii::$app->session->setFlash('success', $excStatusMsg);
                }
                if ($excStatusKode == '401') {
                    Yii::$app->session->setFlash('warning', $excStatusMsg);
                }
            } else {
                $modelAbsen->addError('absenJdwlId', 'Hari dan tanggal tidak sesuai, silahkan periksa kembali!');
                $modelAbsen->addError('absenTgl', 'Hari dan tanggal tidak sesuai, silahkan periksa kembali!');
                $hariTglMatch = '0';
            }
        }
        //Data Peserta
        $queryPeserta = LatPeserta::find()
                ->select(['*', 'absenIsHadir AS kehadiran'])
                ->join('JOIN', 'lat_peserta_absen', 'lat_peserta_absen.absenPesertaId=lat_peserta.pesertaId')
                ->where('pesertaKlsId=:kls AND absenJdwlId=:jdwl AND absenTgl=:tgl', [
            ':kls' => $id,
            ':jdwl' => $modelAbsen->absenJdwlId,
            ':tgl' => $modelAbsen->absenTgl
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $queryPeserta,
            'pagination' => [
                'pageSize' => 20,
                'page' => Yii::$app->request->get('page') - 1,
                'params' => [
                    'id' => $id,
                    'jdwl' => $modelAbsen->absenJdwlId,
                    'tgl' => $modelAbsen->absenTgl
                ]
            ]
        ]);
        return $this->render('importAbsen', [
                    'model' => $model,
                    'modelAbsen' => $modelAbsen,
                    'hariTglMatch' => $hariTglMatch,
                    'dataProvider' => $dataProvider,
                    'id' => $id,
                    'jdwl' => $modelAbsen->absenJdwlId,
                    'tgl' => $modelAbsen->absenTgl
        ]);
    }

    public function actionAbsenonline($id) {
        $model = LatKelas::findOne($id);
        $conn = new DAO();
        $modelAbsen = new LatPesertaAbsen();
        $hariTglMatch = '';
        if ($modelAbsen->load(\Yii::$app->request->post())) {
            //Get Hari DAY
            $qGetHari = "SELECT DAYNAME(:tgl) AS hari";
            $rsGetHari = $conn->QueryRow($qGetHari, [':tgl' => $modelAbsen->absenTgl]);
            //Get Hari Jadwal
            $hariJadwal = LatJadwal::findOne($modelAbsen->absenJdwlId);
            if ($hariJadwal->jdwlHariKode == $rsGetHari['hari']) {
                $hariTglMatch = '1';
            } else {
                $modelAbsen->addError('absenJdwlId', 'Hari dan tanggal tidak sesuai, silahkan periksa kembali!');
                $modelAbsen->addError('absenTgl', 'Hari dan tanggal tidak sesuai, silahkan periksa kembali!');
                $hariTglMatch = '0';
            }
        }
        return $this->render('absenOnline', [
                    'model' => $model,
                    'modelAbsen' => $modelAbsen,
                    'hariTglMatch' => $hariTglMatch
        ]);
    }

    public function actionCheckpeserta() {
        if (\Yii::$app->request->isAjax) {
            $conn = new DAO();
            $modelAbsen = new LatPesertaAbsen();
            if ($modelAbsen->load(\Yii::$app->request->get())) {
                if (strlen($modelAbsen->absenPesertaId) == 10) {
                    //Get Hari DAY
                    $qGetHari = "SELECT DAYNAME(:tgl) AS hari";
                    $rsGetHari = $conn->QueryRow($qGetHari, [':tgl' => $modelAbsen->absenTgl]);
                    //Get Hari Jadwal
                    $hariJadwal = LatJadwal::findOne($modelAbsen->absenJdwlId);
                    if ($hariJadwal->jdwlHariKode == $rsGetHari['hari']) {
                        //Cek Peserta Bayar atau Gratis
                        $peserta = LatPeserta::find()
                                ->where("pesertaId=:peserta AND ((pesertaIsFree='1' AND pesertaIsPaid='0') OR (pesertaIsFree='0' AND pesertaIsPaid='1'))", [
                                    ':peserta' => $modelAbsen->absenPesertaId
                                ])
                                ->count();
                        if ($peserta > 0) {
                            $qMeet = "SELECT COUNT(*) AS jmlMeet,lk.`klsMeetingMax` AS maxMeet "
                                    . "FROM `lat_peserta_absen` pa "
                                    . "JOIN `lat_jadwal` lj ON lj.`jdwlId`=pa.`absenJdwlId` "
                                    . "JOIN `lat_kelas` lk ON lk.`klsId`=lj.`jdwlKlsId` "
                                    . "WHERE lj.`jdwlKlsId`=:kls AND pa.`absenPesertaId`=:peserta";
                            $rsMeet = $conn->QueryRow($qMeet, [
                                ':peserta' => $modelAbsen->absenPesertaId,
                                ':kls' => $hariJadwal->jdwlKlsId
                            ]);
                            if ($rsMeet['jmlMeet'] < $rsMeet['maxMeet']) {
                                $qCek = "SELECT * FROM lat_peserta_absen "
                                        . "WHERE absenPesertaId=:peserta AND absenJdwlId=:jdwl "
                                        . "AND absenTgl=:tgl";
                                $rsCek = $conn->QueryRow($qCek, [
                                    ':peserta' => $modelAbsen->absenPesertaId,
                                    ':jdwl' => $modelAbsen->absenJdwlId,
                                    ':tgl' => $modelAbsen->absenTgl,
                                ]);
                                if (empty($rsCek)) {
                                    $modelPeserta = LatPeserta::find()
                                                    ->where('pesertaId=:id AND pesertaKlsId=:kls', [
                                                        ':id' => $modelAbsen->absenPesertaId,
                                                        ':kls' => $hariJadwal->jdwlKlsId
                                                    ])->one();
                                    if (!empty($modelPeserta)) {
                                        return $this->renderAjax('dialogCheckPeserta', [
                                                    'modelPeserta' => $modelPeserta,
                                                    'klsid' => $modelPeserta->pesertaKlsId,
                                                    'peserta' => $modelAbsen->absenPesertaId,
                                                    'jdwl' => $modelAbsen->absenJdwlId,
                                                    'tgl' => $modelAbsen->absenTgl,
                                                    'ishadir' => 1, //$modelAbsen->absenIsHadir
                                        ]);
                                    } else {
                                        //Peserta tidak ditemukan di database
                                        return 401;
                                    }
                                } else {
                                    //Peserta Sudah absen pada hari dan tanggal tersebut
                                    return 402;
                                }
                            } else {
                                //Pertemuan Sudah Terpenuhi
                                return 406;
                            }
                        } else {
                            //Peserta tidak ditemukan di database
                            return 401;
                        }
                    } else {
                        //Tanggal dan Hari Tidak Sesuai
                        return 403;
                    }
                } else {
                    //Nomor Peserta Kurang dari 10 digit
                    return 404;
                }
            } else {
                //Gagal
                return 400;
            }
        }
    }

    public function actionSimpanpeserta($act, $params) {
        if (\Yii::$app->request->isAjax) {
            $conn = new DAO();
            $inDate = new IndonesiaDate();
            $param = unserialize(urldecode($params));
            if ($act == 'personal-set-hadir') {
                //Set Hadir Per Personal
                $qCek = "SELECT * FROM lat_peserta_absen "
                        . "WHERE absenPesertaId=:peserta AND absenJdwlId=:jdwl AND absenTgl=:tgl";
                $rsCek = $conn->QueryAll($qCek, [
                    ':peserta' => $param['peserta'],
                    ':jdwl' => $param['jdwl'],
                    ':tgl' => $param['tgl'],
                ]);
                if (empty($rsCek)) {
                    $query = "INSERT INTO lat_peserta_absen VALUE(:peserta,:jdwl,:tgl,:ishadir,:buat)";
                    $result = $conn->Execute($query, [
                        ':peserta' => $param['peserta'],
                        ':jdwl' => $param['jdwl'],
                        ':tgl' => $param['tgl'],
                        ':ishadir' => $param['ishadir'],
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
            } else if ($act == 'masal-set-hadir') {
                //Set Hadir Masal
                $query = "SELECT * FROM lat_peserta "
                        . "WHERE pesertaId NOT IN(SELECT pa.absenPesertaId FROM lat_peserta_absen pa "
                        . "JOIN lat_jadwal lj ON lj.jdwlId=pa.absenJdwlId "
                        . "WHERE lj.jdwlKlsId=:kls AND pa.absenJdwlId=:jdwl AND pa.absenTgl=:tgl) "
                        . "AND pesertaKlsId=:kls AND ((pesertaIsFree='1' AND pesertaIsPaid='0') OR (pesertaIsFree='0' AND pesertaIsPaid='1'))";
                $result = $conn->QueryAll($query, [
                    ':kls' => $param['kls'],
                    ':jdwl' => $param['jdwl'],
                    ':tgl' => $param['tgl'],
                ]);
                if (count($result) > 0) {
                    $jmlEx = 0;
                    $trans = $conn->beginTransaction();
                    foreach ($result as $val) {
                        $query1 = "INSERT INTO lat_peserta_absen VALUE(:peserta,:jdwl,:tgl,:ishadir,:buat)";
                        $result1 = $conn->Execute($query1, [
                            ':peserta' => $val['pesertaId'],
                            ':jdwl' => $param['jdwl'],
                            ':tgl' => $param['tgl'],
                            ':ishadir' => '1',
                            ':buat' => $inDate->getNow()
                        ]);
                        if ($result1 == 1) {
                            $jmlEx = $jmlEx + 1;
                        }
                    }
                    if ($jmlEx == count($result)) {
                        $trans->commit();
                        return 410;
                    } else {
                        $trans->rollBack();
                        return 401;
                    }
                } else {
                    return 400;
                }
            } else if ($act == 'masal-set-tidak-hadir') {
                //Set Tidak Hadir Masal
                $query = "SELECT * FROM lat_peserta "
                        . "WHERE pesertaId NOT IN(SELECT pa.absenPesertaId FROM lat_peserta_absen pa "
                        . "JOIN lat_jadwal lj ON lj.jdwlId=pa.absenJdwlId "
                        . "WHERE lj.jdwlKlsId=:kls AND pa.absenJdwlId=:jdwl AND pa.absenTgl=:tgl) "
                        . "AND pesertaKlsId=:kls AND ((pesertaIsFree='1' AND pesertaIsPaid='0') OR (pesertaIsFree='0' AND pesertaIsPaid='1'))";
                $result = $conn->QueryAll($query, [
                    ':kls' => $param['kls'],
                    ':jdwl' => $param['jdwl'],
                    ':tgl' => $param['tgl'],
                ]);
                if (count($result) > 0) {
                    $jmlEx = 0;
                    $trans = $conn->beginTransaction();
                    foreach ($result as $val) {
                        $query1 = "INSERT INTO lat_peserta_absen VALUE(:peserta,:jdwl,:tgl,:ishadir,:buat)";
                        $result1 = $conn->Execute($query1, [
                            ':peserta' => $val['pesertaId'],
                            ':jdwl' => $param['jdwl'],
                            ':tgl' => $param['tgl'],
                            ':ishadir' => '0',
                            ':buat' => $inDate->getNow()
                        ]);
                        if ($result1 == 1) {
                            $jmlEx = $jmlEx + 1;
                        }
                    }
                    if ($jmlEx == count($result)) {
                        $trans->commit();
                        return 410;
                    } else {
                        $trans->rollBack();
                        return 401;
                    }
                } else {
                    return 400;
                }
            }
        }
    }

    public function actionBatalpeserta($pesertaid) {
        if (\Yii::$app->request->isAjax) {
            $conn = new DAO();
            if (\Yii::$app->request->get()) {
                $query = "DELETE FROM lat_peserta_absen "
                        . "WHERE absenPesertaId=:peserta AND absenJdwlId=:jdwl AND absenTgl=:tgl";
                $result = $conn->Execute($query, [
                    ':peserta' => $pesertaid,
                    ':jdwl' => Yii::$app->request->get('jdwl'),
                    ':tgl' => Yii::$app->request->get('tgl'),
                ]);
                if ($result == 1) {
                    return 410;
                } else {
                    return 401;
                }
            } else {
                return 400;
            }
        }
    }

    public function actionGetkehadiran($klsid = '', $jdwlid = '', $tgl = '') {
        if (Yii::$app->request->isAjax) {
            if ($klsid != '' && $jdwlid != '' && $tgl != '') {
                //Data Peserta
                $queryPeserta = LatPeserta::find()
                        ->select(['*', 'absenIsHadir AS kehadiran'])
                        ->join('JOIN', 'lat_peserta_absen', 'lat_peserta_absen.absenPesertaId=lat_peserta.pesertaId')
                        ->where('pesertaKlsId=:kls AND absenJdwlId=:jdwl AND absenTgl=:tgl AND ((pesertaIsFree="1" AND pesertaIsPaid="0") OR (pesertaIsFree="0" AND pesertaIsPaid="1"))', [
                    ':kls' => $klsid,
                    ':jdwl' => $jdwlid,
                    ':tgl' => $tgl
                ]);
                $dataProvider = new ActiveDataProvider([
                    'query' => $queryPeserta,
                ]);

                return $this->renderAjax('_indexAbsensi', [
                            'dataProvider' => $dataProvider,
                            'klsid' => $klsid,
                            'jdwlid' => $jdwlid,
                            'tgl' => $tgl
                ]);
            }
        }
    }

    public function actionCountabsensi($act, $params) {
        if (Yii::$app->request->isAjax) {
            $conn = new DAO();
            $param = unserialize(urldecode($params));
            if ($act == 'belum-absen') {
                $query = "SELECT COUNT(*)AS jml FROM lat_peserta "
                        . "WHERE pesertaId NOT IN(SELECT pa.absenPesertaId FROM lat_peserta_absen pa "
                        . "JOIN lat_jadwal lj ON lj.jdwlId=pa.absenJdwlId "
                        . "WHERE lj.jdwlKlsId=:kls AND pa.absenJdwlId=:jdwl AND pa.absenTgl=:tgl) "
                        . "AND pesertaKlsId=:kls AND ((pesertaIsFree='1' AND pesertaIsPaid='0') OR (pesertaIsFree='0' AND pesertaIsPaid='1'))";
                $result = $conn->QueryRow($query, [
                    ':kls' => $param['kls'],
                    ':jdwl' => $param['jdwl'],
                    ':tgl' => $param['tgl'],
                ]);
                return Json::decode($result['jml']);
            } else if ($act == 'sudah-absen') {
                $query = "SELECT COUNT(*)AS jml FROM lat_peserta_absen pa "
                        . "JOIN lat_jadwal lj ON lj.jdwlId=pa.absenJdwlId "
                        . "WHERE lj.jdwlKlsId=:kls AND pa.absenJdwlId=:jdwl AND pa.absenTgl=:tgl";
                $result = $conn->QueryRow($query, [
                    ':kls' => $param['kls'],
                    ':jdwl' => $param['jdwl'],
                    ':tgl' => $param['tgl'],
                ]);
                return Json::decode($result['jml']);
            } else if ($act == 'jml-hadir') {
                $query = "SELECT COUNT(*)AS jml FROM lat_peserta_absen pa "
                        . "JOIN lat_jadwal lj ON lj.jdwlId=pa.absenJdwlId "
                        . "WHERE lj.jdwlKlsId=:kls AND pa.absenJdwlId=:jdwl "
                        . "AND pa.absenTgl=:tgl AND pa.absenIsHadir='1'";
                $result = $conn->QueryRow($query, [
                    ':kls' => $param['kls'],
                    ':jdwl' => $param['jdwl'],
                    ':tgl' => $param['tgl'],
                ]);
                return Json::decode($result['jml']);
            } else if ($act == 'jml-tidak-hadir') {
                $query = "SELECT COUNT(*)AS jml FROM lat_peserta_absen pa "
                        . "JOIN lat_jadwal lj ON lj.jdwlId=pa.absenJdwlId "
                        . "WHERE lj.jdwlKlsId=:kls AND pa.absenJdwlId=:jdwl "
                        . "AND pa.absenTgl=:tgl AND pa.absenIsHadir='0'";
                $result = $conn->QueryRow($query, [
                    ':kls' => $param['kls'],
                    ':jdwl' => $param['jdwl'],
                    ':tgl' => $param['tgl'],
                ]);
                return Json::decode($result['jml']);
            }
        }
    }

    public function actionPrintblankoabsen($id) {
        $this->layout = '//mainPrint';
        $conn = new DAO();
        //Keterangan kelas
        $qKelas = "SELECT * FROM lat_kelas lk "
                . "JOIN lat_periode lp ON lp.periodeId=lk.klsPeriodeId "
                . "JOIN ref_jenis_pelatihan jp ON jp.jnslatId=lp.periodeJnslatId "
                . "WHERE lk.klsId=:kls";
        $rsKelas = $conn->QueryRow($qKelas, [':kls' => $id]);
        //Peserta
        $qPeserta = "SELECT * FROM lat_peserta lp "
                . "JOIN member m ON m.memberId=lp.pesertaMemberId "
                . "WHERE lp.pesertaKlsId=:kls AND ((lp.pesertaIsFree='1' AND lp.pesertaIsPaid='0') OR (lp.pesertaIsFree='0' AND lp.pesertaIsPaid='1'))"
                . "ORDER BY m.memberNama ASC "
                . "LIMIT :page,:ofset";

        $qPesertaA = "SELECT * FROM lat_peserta lp "
                . "JOIN member m ON m.memberId=lp.pesertaMemberId "
                . "WHERE lp.pesertaKlsId=:kls AND ((lp.pesertaIsFree='1' AND lp.pesertaIsPaid='0') OR (lp.pesertaIsFree='0' AND lp.pesertaIsPaid='1'))"
                . "ORDER BY m.memberNama ASC ";
        $rsPesertaA = $conn->QueryAll($qPesertaA, [
            ':kls' => $id
        ]);
        $jml = count($rsPesertaA);
        $ofset = 15;
        if ($jml <= 15) {
            $jmlPage = 1;
            $page = 0;
        } else {
            if (($jml % 15) == 0) {
                $page = 0;
                $jmlPage = round($jml / 15);
            } else {
                $page = 0;
                if (($jml % 15) == 0) {
                    $jmlPage = round($jml / 15);
                } else {
                    $p = $jml / 15;
                    $ex = explode('.', $p);
                    if (count($ex) > 1) {
                        $jmlPage = $ex[0] + 1;
                    } else {
                        $jmlPage = $ex[0];
                    }
                }
            }
        }
        return $this->render('printBlankoAbsen', [
                    'conn' => $conn,
                    'kls' => $id,
                    'rsKelas' => $rsKelas,
                    'qPeserta' => $qPeserta,
                    'page' => $page,
                    'jmlPage' => $jmlPage,
                    'ofset' => $ofset
        ]);
    }

    public function actionAbsenglobal() {
        $conn = new DAO();
        $modelAbsen = new LatPesertaAbsen();
        $inDate = new IndonesiaDate();
        $modelAbsen->load(\Yii::$app->request->post());
        //Get Hari DAY
        $qGetHari = "SELECT DAYNAME(:tgl) AS hari";
        $rsGetHari = $conn->QueryRow($qGetHari, [':tgl' => $inDate->getDate()]);
        //Get Hari Jadwal

        return $this->render('absenOnlineGlobal', [
                    'modelAbsen' => $modelAbsen,
                    'hari' => $rsGetHari['hari']
        ]);
    }

    public function actionCheckpesertaglobal() {
        if (\Yii::$app->request->isAjax) {
            $conn = new DAO();
            $inDate = new IndonesiaDate();
            $modelAbsen = new LatPesertaAbsen();
            if ($modelAbsen->load(\Yii::$app->request->get())) {
                if (strlen($modelAbsen->absenPesertaId) == 10) {
                    //Get Peserta
                    $qGetPeserta = "SELECT * FROM `lat_peserta` lp "
                            . "JOIN `lat_kelas` lk ON lk.`klsId`=lp.`pesertaKlsId` "
                            . "JOIN `lat_jadwal` lj ON lj.`jdwlKlsId`=lk.`klsId` "
                            . "WHERE lp.`pesertaId`=:peserta "
                            . "AND ((lp.pesertaIsFree='1' AND lp.pesertaIsPaid='0') OR (lp.pesertaIsFree='0' AND lp.pesertaIsPaid='1')) "
                            . "AND lj.`jdwlHariKode`=DAYNAME(:tglsekarang) "
                            . "AND lj.`jdwlJamSelesai`>TIME(:wktsekarang)";
                    $rsGetPeserta = $conn->QueryRow($qGetPeserta, [
                        ':peserta' => $modelAbsen->absenPesertaId,
                        ':tglsekarang' => $inDate->getDate(),
                        ':wktsekarang' => $inDate->getTime()
                    ]);
                    if (!empty($rsGetPeserta)) {
                        //Absen Pada Jadwalnya dan Peserta Ditemukan
                        $qMeet = "SELECT COUNT(*) AS jmlMeet,lk.`klsMeetingMax` AS maxMeet "
                                . "FROM `lat_peserta_absen` pa "
                                . "JOIN `lat_jadwal` lj ON lj.`jdwlId`=pa.`absenJdwlId` "
                                . "JOIN `lat_kelas` lk ON lk.`klsId`=lj.`jdwlKlsId` "
                                . "WHERE lj.`jdwlKlsId`=:kls AND pa.`absenPesertaId`=:peserta";
                        $rsMeet = $conn->QueryRow($qMeet, [
                            ':peserta' => $modelAbsen->absenPesertaId,
                            ':kls' => $rsGetPeserta['klsId']
                        ]);
                        if ($rsMeet['jmlMeet'] < $rsMeet['maxMeet']) {
                            //Pertemuan Masih Kurang dari batas maksimal
                            $modelPeserta = LatPeserta::find()
                                            ->where('pesertaId=:id AND pesertaKlsId=:kls', [
                                                ':id' => $modelAbsen->absenPesertaId,
                                                ':kls' => $rsGetPeserta['klsId']
                                            ])->one();
                            if (!empty($modelPeserta)) {
                                return $this->renderAjax('dialogCheckPesertaGlobal', [
                                            'modelPeserta' => $modelPeserta,
                                            'klsid' => $rsGetPeserta['klsId'],
                                            'peserta' => $modelAbsen->absenPesertaId,
                                            'jdwl' => $rsGetPeserta['jdwlId'],
                                            'tgl' => $inDate->getDate(),
                                            'ishadir' => 1, //$modelAbsen->absenIsHadir
                                ]);
                            } else {
                                //Peserta tidak ditemukan di database
                                return 401;
                            }
                        } else {
                            //Pertemuan Sudah Terpenuhi atau melebihi batas maksimal
                            return 406;
                        }
                    } else {
                        //Peserta Tidak Ditemukan atau Jadwal Pengambilan Absen Sudah Lewat
                        return 402;
                    }
                } else {
                    //Nomor Peserta Kurang dari 10 digit
                    return 404;
                }
            } else {
                //Gagal
                return 400;
            }
        }
    }

    private function gantiFileName($nama) {
        $inDate = new IndonesiaDate();
        $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $string = '';
        for ($i = 0; $i < 15; $i++) {
            $pos = rand(0, strlen($karakter) - 1);
            $string .= $karakter{$pos};
        }
        $fileName = $nama . '-' . $inDate->getDate() . '-' . \Yii::$app->user->getId() . '-' . $string;
        return $fileName;
    }

}
