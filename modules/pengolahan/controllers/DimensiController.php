<?php

namespace app\modules\pengolahan\controllers;

use Yii;
use app\modules\pengolahan\models\Dimensi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;
use yii\data\ActiveDataProvider;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class DimensiController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'mahasiswa' => ['POST', 'GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'mahasiswa',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlOprDimensi'),
//                        [
//                        'allow' => true,
//                        'actions' => [
//                            'resetpassword'
//                        ],
//                        'roles' => ['@']
//                    ]
                ]
            ]
        ];
    }

    private $dbSireg = 'dbSireg';
    private $dbSiaFh = 'dbSiaFh';

    public function actionMahasiswa() {
        $conn = new DAO();
        $model = new Dimensi();
        $msgSuccess = '';
        $data = [];
        if ($model->load(Yii::$app->request->post())) {
            if (is_array($model->thnAkt)) {
                $akt = '';
                for ($i = 0; $i < count($model->thnAkt); $i++) {
                    if ($akt == '') {
                        $akt = $model->thnAkt[$i];
                    } else {
                        $akt = $akt . ',' . $model->thnAkt[$i];
                    }
                }
            } else {
                $akt = $model->thnAkt;
            }
            $qBiodata = "SELECT 
                    a.`idBiodata` AS ID,
                    a.`niu` AS NIU,
                    a.`nama` AS NAMA_MAHASISWA,
                    a.`angkatan` AS ANGKATAN,
                    a.`idJalur` AS ID_JALUR_SIREG,
                    a.`idProgramStudi` AS ID_PRODI_SIREG,
                    b.`kodeProdiDikti` AS KODE_PRODI_DIKTI,
                    a.`tglLahir` AS TGL_LAHIR,
                    a.`idAgama` AS ID_AGAMA,
                    a.`idSeks` AS JENKEL,
                    a.`idKab` AS ID_KAB_ASAL_SIREG,
                    a.`idNegara` AS ID_NEGARA_SIREG,
                    a.`idSmta` AS ID_SMTA,
                    a.`nik` AS NIK
                    FROM `biodata` a
                    JOIN program_studi b ON b.idProgramStudi=a.idProgramStudi
                    WHERE a.`angkatan`IN(" . $akt . ") AND b.idFak=:fak AND a.niu IS NOT NULL";
            $rsBiodata = $conn->dbAllQueryAll($this->dbSireg, $qBiodata, [
//                ':akt' => $model->thnAkt,
                ':fak' => $model->fakId
            ]);
            $refFak = $conn->QueryRow('SELECT * FROM ref_fakultas WHERE fakId=:id', [
                ':id' => $model->fakId
            ]);
            $dimMhs = [];
            $factMhsCuti = [];
            $factMhsKeluar = [];
            $factMhsReg = [];
            foreach ($rsBiodata as $value) {
                /*
                 * Get Data Mahasiswa
                 */
                $qCekDimMhs = "SELECT * FROM dim_mahasiswa WHERE mhsId=:id AND mhsNiu=:niu";
                $rsCekDimMhs = $conn->QueryRow($qCekDimMhs, [
                    ':id' => $value['ID'],
                    ':niu' => $value['NIU']
                ]);
                if (empty($rsCekDimMhs)) {
                    $qMhs = "SELECT 
                    b.`mhsSemesterMasuk` AS SMT_MASUK,
                    b.`mhsSemIdMasuk` AS SMT_ID_MASUK,
                    b.`mhsNomorTes` AS NO_TEST,
                    b.`mhsTanggalTerdaftar` AS TGL_TERDAFTAR,
                    b.`mhsStatusMasukPt` AS STATUS_MASUK,
                    b.`mhsStakmhsrKode` AS STATUS_MHS,
                    b.mhsSksWajib AS SKS_WAJIB,
                    b.mhsSksPilihan AS SKS_PILIHAN,
                    b.mhsSksTranskrip AS SKS_TRANSKRIP,
                    b.mhsBobotTotalTranskrip AS BOBOT_TOTAL,
                    b.mhsIpkTranskrip AS IPK_TRANSKRIP,
                    b.mhsTanggalLulus AS TGL_LULUS,
                    b.mhsSemIdLulus AS SMT_LULUS
                    FROM `mahasiswa` b 
                    JOIN `program_studi` c ON c.`prodiKode`=b.`mhsProdiKode`
                    WHERE b.`mhsAngkatan`IN(" . $akt . ") AND b.mhsNiu=:niu";
                    $rsMhs = $conn->dbAllQueryRow($refFak['fakDb'], $qMhs, [
                        //':akt' => $model->thnAkt,
                        ':niu' => $value['NIU']
                    ]);
                    //$value['NAMA_MAHASISWA'] = $rsMhs['NAMA_MAHASISWA'];
                    $value['SMT_MASUK'] = $rsMhs['SMT_MASUK'];
                    $value['SMT_ID_MASUK'] = $rsMhs['SMT_ID_MASUK'];
                    //$value['KODE_PRODI_DIKTI'] = $rsMhs['KODE_PRODI_DIKTI'];
                    $value['NO_TEST'] = $rsMhs['NO_TEST'];
                    $value['TGL_TERDAFTAR'] = $rsMhs['TGL_TERDAFTAR'];
                    $value['STATUS_MASUK'] = $rsMhs['STATUS_MASUK'];
                    $value['STATUS_MHS'] = $rsMhs['STATUS_MHS'];
                    $value['SKS_WAJIB'] = $rsMhs['SKS_WAJIB'];
                    $value['SKS_PILIHAN'] = $rsMhs['SKS_PILIHAN'];
                    $value['SKS_TRANSKRIP'] = $rsMhs['SKS_TRANSKRIP'];
                    $value['BOBOT_TOTAL'] = $rsMhs['BOBOT_TOTAL'];
                    $value['IPK_TRANSKRIP'] = $rsMhs['IPK_TRANSKRIP'];
                    $value['TGL_LULUS'] = $rsMhs['TGL_LULUS'];
                    $value['SMT_LULUS'] = $rsMhs['SMT_LULUS'];
                    $dimMhs[] = $value;
                }

                /*
                 * Get Data Mahasiswa Cuti
                 */
                $qCuti = "SELECT 
                        a.`mhsctMhsNiu` AS NIU,
                        a.`mhsctSemId` AS ID_SMT,
                        a.`mhsctTanggal` AS TGL_SK,
                        a.`mhsctNoSuratIjinCuti` AS NO_SK,
                        a.`mhsctSbctrId` AS SEBAB_ID
                        FROM `mahasiswa_cuti` a
                        WHERE a.`mhsctMhsNiu`=:niu";
                $rsCuti = $conn->dbAllQueryAll($refFak['fakDb'], $qCuti, [
                    ':niu' => $value['NIU'],
                ]);
                foreach ($rsCuti as $val) {
                    $qCekCuti = "SELECT * FROM fact_mahasiswa_cuti WHERE mhsctNiu=:niu AND mhsctSmtId=:smt";
                    $rsCekCuti = $conn->QueryRow($qCekCuti, [
                        ':niu' => $value['NIU'],
                        ':smt' => $val['ID_SMT'],
                    ]);
                    if (empty($rsCekCuti)) {
                        $factMhsCuti[] = $val;
                    }
                }
                /*
                 * Get Mahasiswa Keluar
                 */
                $qKeluar = "SELECT 
                        a.`mhskMhsNiu` AS NIU,
                        a.`mhskSemId` AS ID_SMT,
                        a.`mhskTanggal` AS TGL_SK,
                        a.`mhskNoSuratKeluar` AS NO_SK,
                        a.`mhskSbkrId` AS SEBAB_ID
                        FROM `mahasiswa_keluar` a
                        WHERE a.`mhskMhsNiu`=:niu";
                $rsKeluar = $conn->dbAllQueryAll($refFak['fakDb'], $qKeluar, [
                    ':niu' => $value['NIU'],
                ]);
                foreach ($rsKeluar as $val) {
                    $qCekKeluar = "SELECT * FROM fact_mahasiswa_keluar WHERE mhskNiu=:niu AND mhskSmtId=:smt";
                    $rsCekKeluar = $conn->QueryRow($qCekKeluar, [
                        ':niu' => $value['NIU'],
                        ':smt' => $val['ID_SMT'],
                    ]);
                    if (empty($rsCekKeluar)) {
                        $factMhsKeluar[] = $val;
                    }
                }
                /*
                 * Get Mahasiswa Registrasi
                 */
                $qReg = "SELECT 
                        a.`mhsregMhsNiu` AS NIU,
                        a.`mhsregSemId` AS ID_SMT,
                        a.`mhsregTanggalRegistrasi` AS TGL
                        FROM `mahasiswa_registrasi` a
                        WHERE a.`mhsregMhsNiu`<>'' AND a.`mhsregMhsNiu`=:niu";
                $rsReg = $conn->dbAllQueryAll($refFak['fakDb'], $qReg, [
                    ':niu' => $value['NIU'],
                ]);
                foreach ($rsReg as $val) {
                    $qCekReg = "SELECT * FROM fact_mahasiswa_registrasi WHERE mhsregNiu=:niu AND mhsregSmtId=:smt";
                    $rsCekReg = $conn->QueryRow($qCekReg, [
                        ':niu' => $value['NIU'],
                        ':smt' => $val['ID_SMT'],
                    ]);
                    if (empty($rsCekReg)) {
                        $factMhsReg[] = $val;
                    }
                }
            }
            $data['jmlMhs'] = count($dimMhs);
            $data['jmlMhsCuti'] = count($factMhsCuti);
            $data['jmlMhsKeluar'] = count($factMhsKeluar);
            $data['jmlMhsReg'] = count($factMhsReg);
            if (Yii::$app->request->post('btn-proses')) {
                try {
                    /*
                     * Entri data mahasiswa
                     */
                    $parDimMhs = [];
                    foreach ($dimMhs as $key => $val) {
                        $parDimMhs[] = [
                            $val['ID'],
                            $val['NIU'],
                            $val['NAMA_MAHASISWA'],
                            $val['ANGKATAN'],
                            $val['ID_JALUR_SIREG'],
                            $val['SMT_MASUK'],
                            $val['SMT_ID_MASUK'],
                            $val['ID_PRODI_SIREG'],
                            $val['KODE_PRODI_DIKTI'],
                            $val['NO_TEST'],
                            $val['TGL_TERDAFTAR'],
                            $val['STATUS_MASUK'],
                            $val['TGL_LAHIR'],
                            $val['ID_AGAMA'],
                            $val['JENKEL'],
                            $val['ID_KAB_ASAL_SIREG'],
                            $val['ID_NEGARA_SIREG'],
                            $val['ID_SMTA'],
                            $val['NIK'],
                            $val['STATUS_MHS'],
                            $val['SKS_WAJIB'],
                            $val['SKS_PILIHAN'],
                            $val['SKS_TRANSKRIP'],
                            $val['BOBOT_TOTAL'],
                            $val['IPK_TRANSKRIP'],
                            $val['TGL_LULUS'],
                            $val['SMT_LULUS'],
                        ];
                    }
                    if (!empty($parDimMhs)) {
                        $trans1 = $conn->beginTransaction();
                        $attDimMhs = ['mhsId', 'mhsNiu', 'mhsNama',
                            'mhsAngkatan', 'mhsIdJalur', 'mhsSmtMasuk',
                            'mhsSmtIdMasuk', 'mhsIdProdi', 'mhsProdiDikti',
                            'mhsNomorTes', 'mhsTglTerdaftar', 'mhsStatusMasukPt',
                            'mhsTglLahir', 'mhsIdAgama', 'mhsJenkel',
                            'mhsIdKabAsal', 'mhsIdNegara', 'mhsIdSmta',
                            'mhsNik', 'mhsStatus', 'mhsSksWajib', 'mhsSksPilihan',
                            'mhsSksTranskrip', 'mhsBobotTotalTranskrip', 'mhsIpkTranskrip',
                            'mhsTglLulus', 'mhsSmtIdLulus'];
                        $result1 = $conn->BatchInsert('dim_mahasiswa', $attDimMhs, $parDimMhs);
                        if ($result1) {
                            $trans1->commit();
                            if ($msgSuccess == '') {
                                $msgSuccess = '<br/>#DATA MAHASISWA :<br/>Update data mahasiswa berhasil...';
                            } else {
                                $msgSuccess = $msgSuccess . '<br/><br/>#DATA MAHASISWA :<br/>Update data mahasiswa berhasil...';
                            }
                        } else {
                            $trans1->rollBack();
                        }
                    }
                    /*
                     * Entri data mahasiswa cuti
                     */
                    $parFactCuti = [];
                    foreach ($factMhsCuti as $key => $val) {
                        $parFactCuti[] = [
                            $val['NIU'],
                            $val['ID_SMT'],
                            $val['TGL_SK'],
                            $val['NO_SK'],
                            $val['SEBAB_ID']
                        ];
                    }
                    if (!empty($parFactCuti)) {
                        $trans2 = $conn->beginTransaction();
                        $attFactCuti = ['mhsctNiu', 'mhsctSmtId', 'mhsctTanggal', 'mhsctNoSuratIjinCuti', 'mhsctSbctId'];
                        $result2 = $conn->BatchInsert('fact_mahasiswa_cuti', $attFactCuti, $parFactCuti);
                        if ($result2) {
                            $trans2->commit();
                            if ($msgSuccess == '') {
                                $msgSuccess = '<br/>#MAHASISWA CUTI :<br/>Update data mahasiswa cuti berhasil...';
                            } else {
                                $msgSuccess = $msgSuccess . '<br/><br/>#MAHASISWA CUTI :<br/>Update data mahasiswa cuti berhasil...';
                            }
                        } else {
                            $trans2->rollBack();
                        }
                    }
                    /*
                     * Entri data mahasiswa Keluar
                     */
                    $parFactKeluar = [];
                    foreach ($factMhsKeluar as $key => $val) {
                        $parFactKeluar[] = [
                            $val['NIU'],
                            $val['ID_SMT'],
                            $val['TGL_SK'],
                            $val['NO_SK'],
                            $val['SEBAB_ID']
                        ];
                    }
                    if (!empty($parFactKeluar)) {
                        $trans3 = $conn->beginTransaction();
                        $attFactKeluar = ['mhskNiu', 'mhskSmtId', 'mhskTanggal', 'mhskNoSuratKeluar', 'mhskSbkrId'];
                        $result3 = $conn->BatchInsert('fact_mahasiswa_keluar', $attFactKeluar, $parFactKeluar);
                        if ($result3) {
                            $trans3->commit();
                            if ($msgSuccess == '') {
                                $msgSuccess = '<br/>#MAHASISWA KELUAR :<br/>Update data mahasiswa keluar berhasil...';
                            } else {
                                $msgSuccess = $msgSuccess . '<br/><br/>#MAHASISWA KELUAR :<br/>Update data mahasiswa keluar berhasil...';
                            }
                        } else {
                            $trans3->rollBack();
                        }
                    }
                    /*
                     * Entri data mahasiswa Registrasi
                     */
                    $parFactReg = [];
                    foreach ($factMhsReg as $key => $val) {
                        $parFactReg[] = [
                            $val['NIU'],
                            $val['ID_SMT'],
                            $val['TGL'],
                        ];
                    }
                    if (!empty($parFactReg)) {
                        $trans4 = $conn->beginTransaction();
                        $attFactReg = ['mhsregNiu', 'mhsregSmtId', 'mhsregTgl'];
                        $result4 = $conn->BatchInsert('fact_mahasiswa_registrasi', $attFactReg, $parFactReg);
                        if ($result4) {
                            $trans4->commit();
                            if ($msgSuccess == '') {
                                $msgSuccess = '<br/>#MAHASISWA REGISTRASI :<br/>Update data mahasiswa registrasi berhasil...';
                            } else {
                                $msgSuccess = $msgSuccess . '<br/><br/>#MAHASISWA REGISTRASI :<br/>Update data mahasiswa registrasi berhasil...';
                            }
                        } else {
                            $trans4->rollBack();
                        }
                    }
                } catch (yii\db\IntegrityException $e) {
                    Yii::$app->session->setFlash('danger', 'Update data gagal. Data mahasiswa tidak lengkap!' . $e);
                }

                if ($msgSuccess != '') {
                    Yii::$app->session->setFlash('success', $msgSuccess);
                    return $this->redirect(['mahasiswa']);
                }
            }
        }

        return $this->render('mahasiswa', [
                    'model' => $model,
                    'data' => $data
        ]);
    }

    public function actionSemester() {
        $conn = new DAO();
        $model = new Dimensi();
        $msgSuccess = '';
        $data = [];
        if ($model->load(Yii::$app->request->post())) {
            
        }
        return $this->render('semester', [
                    'model' => $model,
                    'data' => $data
        ]);
    }
}
