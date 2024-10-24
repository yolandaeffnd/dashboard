<?php

namespace app\modules\akademik\controllers;

use Yii;
use app\modules\akademik\models\Mahasiswa;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MahasiswaController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'per-prodi' => ['POST', 'GET'],
                    'per-angkatan' => ['POST', 'GET'],
                    'aktif' => ['POST', 'GET'],
                    'aktif-fakultas' => ['POST', 'GET'],
                    'get-aktif' => ['POST', 'GET'],
                    'get-aktif-detail' => ['POST', 'GET'],
                    'get-aktif-fak-detail' => ['POST', 'GET']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'per-prodi',
                    'per-angkatan',
                    'aktif',
                    'aktif-fakultas',
                    'get-aktif',
                    'get-aktif-detail',
                    'get-aktif-fak-detail'
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlAkademikMahasiswa'),
                        [
                        'allow' => true,
                        'actions' => [
                            'get-aktif',
                            'get-aktif-detail',
                            'get-aktif-fak-detail'
                        ],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionGetAktif($act, $params) {
        $conn = new DAO();
        if (\Yii::$app->request->isAjax) {
            $param = unserialize(urldecode($params));
            if ($act == 'by-prodi') {
                /* 
                 * By Prodi
                 * Data grafik pie
                 */
                $qPie = "SELECT 
                        CONCAT(mhs.prodiJenjang,' - ',mhs.prodiNama)AS PRODI_NAMA,
                        COUNT(*)AS JML
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,a1.`mhsJenkel`,c1.prodiKode,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,c2.prodiKode,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,c3.prodiKode,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.prodiKode
                        ORDER BY mhs.prodiJenjang ASC";
                $rsPie = $conn->QueryAll($qPie, []);
                $totMem = 0;
                foreach ($rsPie as $val) {
                    $totMem = $totMem + $val['JML'];
                }
                foreach ($rsPie as $val) {
                    $persentase = round((($val['JML'] / $totMem) * 100), 2);
                    $dataPie[] = ['name' => $val['PRODI_NAMA'] . ' ( ' . $val['JML'] . ' Org)', 'y' => $persentase];
                }

                $html = $this->renderAjax('_mhsAktifPieProdi', [
                    'dataPie' => $dataPie,
                ]);

                return Json::encode($html);
            } else if ($act == 'by-fakultas') {
                /*
                 * By Fakultas
                 * Data grafik column
                 */
                $qCol = "SELECT 
                        mhs.fakNama AS FAK_NAMA,
                        IF(mhs.mhsJenkel IS NULL,'null',mhs.mhsJenkel) AS LP,
                        COUNT(*)AS JML
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,a1.`mhsJenkel`,e1.fakId,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,e2.fakId,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,e3.fakId,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.fakId,mhs.mhsJenkel
                        ORDER BY mhs.fakId ASC";
                $rsCol = $conn->QueryAll($qCol, []);
                $dataKategori = [];
                $data = [];
                $tot = [];
                foreach ($rsCol as $val) {
                    if ($val['LP'] == 'L') {
                        $data['L'][] = [$val['FAK_NAMA'], (int) $val['JML']];
                        //$dataKategori[] = $val['FAK_NAMA'];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['FAK_NAMA'], (int) $val['JML']];
                    }
                    if ($val['LP'] == 'null') {
                        $data['N'][] = [$val['ANGKATAN'], (int) $val['JML']];
                    }
                    if (!in_array($val['FAK_NAMA'], $dataKategori)) {
                        $dataKategori[] = $val['FAK_NAMA'];
                    }
                }
                for ($i = 0; $i < count($dataKategori); $i++) {
                    $jL = isset($data['L'][$i][1]) ? $data['L'][$i][1] : 0;
                    $jP = isset($data['P'][$i][1]) ? $data['P'][$i][1] : 0;
                    $jN = isset($data['N'][$i][1]) ? $data['N'][$i][1] : 0;
                    $tot[] = $jL + $jP + $jN;
                }

                $dataColumn = [
                        [
                        'type' => 'column',
                        'name' => 'Laki-Laki',
                        'data' => $data['L'],
                    ],
                        [
                        'type' => 'column',
                        'name' => 'Perempuan',
                        'data' => $data['P'],
                    ],
                        [
                        'type' => 'spline',
                        'name' => 'Total',
                        'data' => $tot,
                    ],
                ];

                $html = $this->renderAjax('_mhsAktifColFakultas', [
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
                ]);

                return Json::encode($html);
            } else if ($act == 'by-angkatan') {
                /*
                 * By Angkatan
                 * Data grafik column
                 */
                $qCol7Thn = "SELECT 
                        mhs.mhsAngkatan AS ANGKATAN,
                        IF(mhs.mhsJenkel IS NULL,'null',mhs.mhsJenkel) AS LP,
                        COUNT(*)AS JML
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.prodiKode,a1.`mhsJenkel`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.prodiKode,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode,a3.`mhsJenkel`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ") AND mhs.mhsAngkatan>YEAR(NOW())-7
                        GROUP BY mhs.mhsAngkatan,mhs.mhsJenkel
                        ORDER BY mhs.mhsAngkatan ASC";
                $rsCol7Thn = $conn->QueryAll($qCol7Thn, []);
                $qColKecil7Thn = "SELECT 
                        mhs.mhsAngkatan AS ANGKATAN,
                        IF(mhs.mhsJenkel IS NULL,'null',mhs.mhsJenkel) AS LP,
                        COUNT(*)AS JML
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.prodiKode,a1.`mhsJenkel`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.prodiKode,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode,a3.`mhsJenkel`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ") AND mhs.mhsAngkatan<=YEAR(NOW())-7
                        GROUP BY mhs.mhsAngkatan,mhs.mhsJenkel
                        ORDER BY mhs.mhsAngkatan DESC";
                $rsColKecil7Thn = $conn->QueryAll($qColKecil7Thn, []);

                $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan<=YEAR(NOW())-7 ORDER BY angkatan DESC LIMIT 1";
                $rsAkt = $conn->QueryRow($qAkt, []);

                $dataKategori = [];
                $dataAkt = [];
                $data = [];
                $dataCol = [];
                $tot = [];
                $jL = 0;
                $jP = 0;
                foreach ($rsColKecil7Thn as $val) {
                    if ($val['LP'] == 'L') {
                        $jL = $jL + (int) $val['JML'];
                    }
                    if ($val['LP'] == 'P') {
                        $jP = $jP + (int) $val['JML'];
                    }
                }
                $dataAkt[] = $rsAkt['angkatan'];
                $data[$rsAkt['angkatan']]['L'] = [$rsAkt['angkatan'], $jL];
                $data[$rsAkt['angkatan']]['P'] = [$rsAkt['angkatan'], $jP];

                foreach ($rsCol7Thn as $val) {
                    if ($val['LP'] == 'L') {
                        $data[$val['ANGKATAN']]['L'] = [$val['ANGKATAN'], (int) $val['JML']];
                    }
                    if ($val['LP'] == 'P') {
                        $data[$val['ANGKATAN']]['P'] = [$val['ANGKATAN'], (int) $val['JML']];
                    }
                    if (!in_array($val['ANGKATAN'], $dataAkt)) {
                        $dataAkt[] = $val['ANGKATAN'];
                    }
                }
                for ($i = 0; $i < count($dataAkt); $i++) {
                    $jL = isset($data[$dataAkt[$i]]['L'][1]) ? $data[$dataAkt[$i]]['L'][1] : 0;
                    $jP = isset($data[$dataAkt[$i]]['P'][1]) ? $data[$dataAkt[$i]]['P'][1] : 0;
                    $tot[] = $jL + $jP;

                    if (!in_array($dataAkt[$i], $dataKategori)) {
                        if ($rsAkt['angkatan'] == $dataAkt[$i]) {
                            $dataKategori[] = '<=' . $dataAkt[$i];
                        } else {
                            $dataKategori[] = $dataAkt[$i];
                        }
                    }
                    if (isset($data[$dataAkt[$i]]['L'])) {
                        $dataCol['L'][] = [$dataAkt[$i], (int) $data[$dataAkt[$i]]['L'][1]];
                    } else {
                        $dataCol['L'][] = [$dataAkt[$i], (int) 0];
                    }
                    if (isset($data[$dataAkt[$i]]['P'])) {
                        $dataCol['P'][] = [$dataAkt[$i], (int) $data[$dataAkt[$i]]['P'][1]];
                    } else {
                        $dataCol['P'][] = [$dataAkt[$i], (int) 0];
                    }
                }
                //print_r($dataKategori);
                //print_r($data);
                //print_r($dataCol);
//                exit();
                $dataColumn = [
                        [
                        'type' => 'column',
                        'name' => 'Laki-Laki',
                        'data' => $dataCol['L'],
                    ],
                        [
                        'type' => 'column',
                        'name' => 'Perempuan',
                        'data' => $dataCol['P'],
                    ],
                        [
                        'type' => 'spline',
                        'name' => 'Total',
                        'data' => $tot,
                    ],
                ];

                $html = $this->renderAjax('_mhsAktifColAngkatan', [
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
                ]);

                return Json::encode($html);
            } else if ($act == 'by-ipk') {
                /*
                 * By IPK Mahasiswa
                 * Data grafik column
                 */
                $qCol7Thn = "SELECT mhsIpk.kelIpk,
                    SUM(mhsIpk.jml)AS jml
                    FROM(		
			SELECT 
                        *,
                        IFNULL((
                        SELECT 
                        IF(ROUND(ip.ipIpk,2)<=4.00 AND ROUND(ip.ipIpk,2)>3.50,'IPK4.0-3.5',
                        IF(ROUND(ip.ipIpk,2)<=3.50 AND ROUND(ip.ipIpk,2)>3.00,'IPK3.5-3.0',
                        IF(ROUND(ip.ipIpk,2)<=3.00 AND ROUND(ip.ipIpk,2)>2.50,'IPK3.0-2.5',
                        IF(ROUND(ip.ipIpk,2)<=2.50 AND ROUND(ip.ipIpk,2)>2.00,'IPK2.5-2.0',
                        IF(ROUND(ip.ipIpk,2)<=2.00 AND ROUND(ip.ipIpk,2)>1.00,'IPK2.0-1.0','IPK1.0-0.0'))))) 
                        FROM `fact_ipk` ip WHERE ip.`ipMhsNiu`=mhs.mhsNiu ORDER BY ip.`ipSmtId` DESC LIMIT 1
                        ),'IPK1.0-0.0')AS kelIpk
                        FROM (
                            SELECT 
                            a1.`mhsNiu`,
                            a1.`mhsNama`,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.prodiKode,a1.`mhsJenkel`,a1.`mhsNiu`
                            UNION
                            SELECT 
                            a2.`mhsNiu`,
                            a2.`mhsNama`,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.prodiKode,a2.`mhsJenkel`,a2.`mhsNiu`
                            UNION
                            SELECT 
                            a3.`mhsNiu`,
                            a3.`mhsNama`,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode,a3.`mhsJenkel`,a3.`mhsNiu`
                        )AS mhs
                        GROUP BY mhs.mhsNiu
                    )AS mhsIpk                        
                    WHERE mhsIpk.mhsAngkatan IN(" . $param['akt'] . ")
                    GROUP BY mhsIpk.kelIpk
                    ORDER BY mhsIpk.kelIpk DESC";
                $rsCol7Thn = $conn->QueryAll($qCol7Thn, []);

                $dataKategori = [];
                $dataCol = [];

                foreach ($rsCol7Thn as $val) {
                    $dataCol['IPK'][] = [str_replace('IPK', 'IPK ', $val['kelIpk']), (int) $val['jml']];
                    if (!in_array($val['kelIpk'], $dataKategori)) {
                        $dataKategori[] = str_replace('IPK', 'IPK ', $val['kelIpk']);
                    }
                }
                $dataColumn = [
                        [
                        'type' => 'column',
                        'name' => 'Jumlah',
                        'data' => $dataCol['IPK'],
                    ],
                ];

                $html = $this->renderAjax('_mhsAktifColIpk', [
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
                ]);

                return Json::encode($html);
            }
        }
    }

    public function actionGetAktifDetail($act, $params) {
        $conn = new DAO();
        if (\Yii::$app->request->isAjax) {
            $param = unserialize(urldecode($params));
            if ($act == 'by-prodi') {
                /*
                 * By Program Studi
                 * Data grafik pie
                 */
                $qProdi = "SELECT 
			mhs.prodiKode AS PRODI_KODE,
                        CONCAT(mhs.prodiJenjang,' - ',mhs.prodiNama) AS PRODI_NAMA,
                        COUNT(*) AS JML
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,a1.`mhsJenkel`,e1.fakId,c1.`prodiKode`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,e2.fakId,c2.`prodiKode`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,e3.fakId,c3.`prodiKode`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.prodiKode
                        ORDER BY mhs.prodiJenjang, mhs.prodiKode ASC";
                $dataTabel = $conn->QueryAll($qProdi, []);
                /*
                 * Data angkatan
                 */
                $qAkt = "SELECT 
			mhs.mhsAngkatan AS AKT,
                        (YEAR(NOW())-7) AS LAST_AKT
                        FROM (
                        (SELECT * 
                        FROM(
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs1
                        WHERE mhs1.mhsAngkatan IN(" . $param['akt'] . ") AND mhs1.mhsAngkatan<=YEAR(NOW())-7
                        GROUP BY mhs1.mhsAngkatan
                        ORDER BY mhs1.mhsAngkatan DESC 
                        LIMIT 1)
                        UNION
                        (SELECT * 
                        FROM(
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs2
                        WHERE mhs2.mhsAngkatan IN(" . $param['akt'] . ") AND mhs2.mhsAngkatan>YEAR(NOW())-7
                        GROUP BY mhs2.mhsAngkatan)
                        )AS mhs
                        ORDER BY mhs.mhsAngkatan ASC";
                $dataTabel_akt = $conn->QueryAll($qAkt, []);

                $html = $this->renderAjax('_detailMhsAktifByProdi', [
                    'dataTabel' => $dataTabel,
                    'dataTabel_akt' => $dataTabel_akt
                ]);

                return Json::encode($html);
            } else if ($act == 'by-fakultas') {
                /*
                 * By Fakultas
                 * Data grafik column
                 */
                $qCol = "SELECT 
			mhs.fakId AS FAK_KODE,
                        mhs.fakNama AS FAK_NAMA,
                        COUNT(*) AS JML
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,a1.`mhsJenkel`,e1.fakId,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,e2.fakId,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,e3.fakId,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.fakId
                        ORDER BY mhs.fakId ASC";
                $dataTabel = $conn->QueryAll($qCol, []);
                /*
                 * Data angkatan
                 */
                $qAkt = "SELECT 
			mhs.mhsAngkatan AS AKT,
                        (YEAR(NOW())-7) AS LAST_AKT
                        FROM (
                        (SELECT * 
                        FROM(
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs1
                        WHERE mhs1.mhsAngkatan IN(" . $param['akt'] . ") AND mhs1.mhsAngkatan<=YEAR(NOW())-7
                        GROUP BY mhs1.mhsAngkatan
                        ORDER BY mhs1.mhsAngkatan DESC 
                        LIMIT 1)
                        UNION
                        (SELECT * 
                        FROM(
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs2
                        WHERE mhs2.mhsAngkatan IN(" . $param['akt'] . ") AND mhs2.mhsAngkatan>YEAR(NOW())-7
                        GROUP BY mhs2.mhsAngkatan)
                        )AS mhs
                        ORDER BY mhs.mhsAngkatan ASC";
                $dataTabel_akt = $conn->QueryAll($qAkt, []);

                $html = $this->renderAjax('_detailMhsAktifByFakultas', [
                    'dataTabel' => $dataTabel,
                    'dataTabel_akt' => $dataTabel_akt
                ]);

                return Json::encode($html);
            }
        }
    }

    public function actionAktif() {
        $conn = new DAO();
        $model = new Mahasiswa();
        $dataPie = '';
        $jmlAktif = 0;
        $jmlCuti = 0;
        $jmlNonAktif = 0;
        $jmlMabaD3 = 0;
        $jmlMabaS1 = 0;
        $jmlMabaS2 = 0;
        $jmlMabaS3 = 0;
        $jmlMabaSp = 0;
        $jmlMabaPro = 0;
        $arrAkt = '';

        $qAkt = "SELECT angkatan FROM ref_angkatan"; // WHERE angkatan>YEAR(NOW())-7";
        $rsAkt = $conn->QueryAll($qAkt, []);
        foreach ($rsAkt as $valakt) {
            if ($arrAkt == '') {
                $arrAkt = $valakt['angkatan'];
            } else {
                $arrAkt = $arrAkt . ',' . $valakt['angkatan'];
            }
        }

        /*
         * Jumlah Mahasiswa berdasarkan Status A,N,C
         */
        /*$qJmlStatus_old = "SELECT 
                        mhs.prodiJenjang AS JENJANG,
                        mhs.ket AS STATUS_MHS,
                        mhs.jml AS JML
                        FROM (
                            SELECT 
                            a1.`mhsAngkatan`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,a1.`mhsJenkel`
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $arrAkt . ")
                        ORDER BY mhs.prodiJenjang ASC";
        *
        */
        $qJmlStatus = "SELECT 	
			mhs.mhsAngkatan AS AKT,
                        mhs.prodiJenjang AS JENJANG,
                        mhs.ket AS STATUS_MHS,
                        COUNT(*) AS JML
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            c1.`prodiJenjang`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,a1.`mhsJenkel`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            c2.`prodiJenjang`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            c3.`prodiJenjang`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $arrAkt . ")
                        GROUP BY mhs.ket
                        ORDER BY mhs.ket ASC";
        $rsJmlStatus = $conn->QueryAll($qJmlStatus, []);
        foreach ($rsJmlStatus as $val) {
            if ($val['STATUS_MHS'] == 'A') {
                $jmlAktif = $jmlAktif + $val['JML'];
            }
            if ($val['STATUS_MHS'] == 'C') {
                $jmlCuti = $jmlCuti + $val['JML'];
            }
            if ($val['STATUS_MHS'] == 'N') {
                $jmlNonAktif = $jmlNonAktif + $val['JML'];
            }
        }
        /*
         * Data grafik pie
         */
        /*$qPie_old = "SELECT 
                mhs.prodiJenjang AS JENJANG,
                SUM(mhs.jml)AS JML
                FROM (
                    SELECT 
                    a1.`mhsAngkatan`,
                    c1.`prodiJenjang`,
                    'A' AS ket,
                    COUNT(*)AS jml
                    FROM dim_mahasiswa a1
                    JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                    JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                    JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                    WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                    GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`
                    UNION
                    SELECT 
                    a2.`mhsAngkatan`,
                    c2.`prodiJenjang`,
                    'C' AS ket,
                    COUNT(*)AS jml
                    FROM dim_mahasiswa a2
                    JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                    JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                    JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                    WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                    GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`
                    UNION
                    SELECT 
                    a3.`mhsAngkatan`,
                    c3.`prodiJenjang`,
                    'N' AS ket,
                    COUNT(*)AS jml
                    FROM dim_mahasiswa a3
                    JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                    WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                    GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`
                )AS mhs
                WHERE mhs.mhsAngkatan IN(" . $arrAkt . ")
                GROUP BY mhs.prodiJenjang
                ORDER BY mhs.prodiJenjang ASC";
         * 
         */
        
        $qPie = "SELECT 
                mhs.prodiJenjang AS JENJANG,
                count(*)AS JML
                FROM (
                    SELECT 
                    a1.mhsNiu,
                    a1.`mhsAngkatan`,
                    c1.`prodiJenjang`,
                    'A' AS ket
                    #COUNT(*)AS jml
                    FROM dim_mahasiswa a1
                    JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                    JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                    JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                    WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                    GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,a1.`mhsJenkel`,a1.mhsNiu
                    UNION
                    SELECT 
                    a2.mhsNiu,
                    a2.`mhsAngkatan`,
                    c2.`prodiJenjang`,
                    'C' AS ket
                    #COUNT(*)AS jml
                    FROM dim_mahasiswa a2
                    JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                    JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                    JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                    WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                    GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,a2.mhsNiu
                    UNION
                    SELECT 
                    a3.mhsNiu,
                    a3.`mhsAngkatan`,
                    c3.`prodiJenjang`,
                    'N' AS ket
                    #COUNT(*)AS jml
                    FROM dim_mahasiswa a3
                    JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                    WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                    GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,a3.mhsNiu
                )AS mhs
                WHERE mhs.mhsAngkatan IN(" . $arrAkt . ")
                GROUP BY mhs.prodiJenjang
                ORDER BY mhs.prodiJenjang ASC";
        $rsPie = $conn->QueryAll($qPie, []);
        $totMhs = 0;
        foreach ($rsPie as $val) {
            $totMhs = $totMhs + $val['JML'];
        }
        foreach ($rsPie as $val) {
            $persentase = round((($val['JML'] / $totMhs) * 100), 2);
            $dataPie[] = ['name' => $val['JENJANG'] . ' ( ' . $val['JML'] . ' Org )', 'y' => $persentase];
        }

        /*
         * Jumlah Mahasiswa Baru 
         */
        $qJmlMaba = "SELECT 
                    mhs.mhsAngkatan AS AKT,
                    mhs.prodiJenjang AS JENJANG,
                    mhs.jml AS JML
                    FROM (
                        SELECT 
                        a1.`mhsAngkatan`,
                        c1.`prodiJenjang`,
                        'A' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a1
                        JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                        JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                        JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                        WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                        GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`
                        UNION
                        SELECT 
                        a2.`mhsAngkatan`,
                        c2.`prodiJenjang`,
                        'C' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a2
                        JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                        JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                        JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                        WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                        GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`
                        UNION
                        SELECT 
                        a3.`mhsAngkatan`,
                        c3.`prodiJenjang`,
                        'N' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a3
                        JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                        WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                        GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`
                    )AS mhs
                    WHERE mhs.mhsAngkatan IN(SELECT SUBSTR(smtId,1,4) FROM app_semester WHERE smtIsAktif='1')
                    GROUP BY mhs.prodiJenjang,mhs.mhsAngkatan
                    ORDER BY mhs.prodiJenjang,mhs.mhsAngkatan ASC";
        $rsJmlMaba = $conn->QueryAll($qJmlMaba, []);
        foreach ($rsJmlMaba as $val) {
            if ($val['JENJANG'] == 'D3') {
                $jmlMabaD3 = $jmlMabaD3 + $val['JML'];
            }
            if ($val['JENJANG'] == 'S1') {
                $jmlMabaS1 = $jmlMabaS1 + $val['JML'];
            }
            if ($val['JENJANG'] == 'S2') {
                $jmlMabaS2 = $jmlMabaS2 + $val['JML'];
            }
            if ($val['JENJANG'] == 'S3') {
                $jmlMabaS3 = $jmlMabaS3 + $val['JML'];
            }
            if ($val['JENJANG'] == 'Sp-1') {
                $jmlMabaSp = $jmlMabaSp + $val['JML'];
            }
            if ($val['JENJANG'] == 'Profesi') {
                $jmlMabaPro = $jmlMabaPro + $val['JML'];
            }
        }
        $jmlMabaTotal = $jmlMabaD3 + $jmlMabaS1 + $jmlMabaS2 + $jmlMabaS3 + $jmlMabaSp + $jmlMabaPro;

        return $this->render('mhsAktif', [
                    'model' => $model,
                    'arrAkt' => $arrAkt,
                    'dataPie' => $dataPie,
                    'jmlAktif' => $jmlAktif,
                    'jmlCuti' => $jmlCuti,
                    'jmlNonAktif' => $jmlNonAktif,
                    'totalMhs' => $jmlAktif + $jmlCuti + $jmlNonAktif,
                    'jmlMabaD3' => $jmlMabaD3,
                    'jmlMabaS1' => $jmlMabaS1,
                    'jmlMabaS2' => $jmlMabaS2,
                    'jmlMabaS3' => $jmlMabaS3,
                    'jmlMabaSp' => $jmlMabaSp,
                    'jmlMabaPro' => $jmlMabaPro,
                    'jmlMabaTotal' => $jmlMabaTotal
        ]);
    }

    public function actionPerProdi() {
        $conn = new DAO();
        $model = new Mahasiswa();
        $dataColumn = '';
        $dataKategori = [];
        $arrAkt = '';
        $rsAkt = [];
        $dataTabel = '';
        $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan>(angkatan-7)";
        $refAkt = $conn->QueryAll($qAkt, []);
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->fakId) && !empty($model->thnAkt)) {
                $rsAkt = $model->thnAkt;
                for ($i = 0; $i < count($rsAkt); $i++) {
                    if ($arrAkt == '') {
                        $arrAkt = $rsAkt[$i];
                    } else {
                        $arrAkt = $arrAkt . ',' . $rsAkt[$i];
                    }
                }
                $q = "SELECT 
                    c1.`prodiKode`,
                    CONCAT(c1.`prodiJenjang`,' - ',c1.`prodiNama`)AS NAMA_PRODI,
                    a1.`mhsJenkel` AS LP,
                    COUNT(*)AS JML
                    FROM dim_mahasiswa a1
                    JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                    JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                    JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                    WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                    AND a1.`mhsAngkatan` IN(" . $arrAkt . ") AND c1.`prodiFakId`=:fak
                    GROUP BY c1.`prodiKode`,c1.`prodiJenjang`,a1.`mhsJenkel`
                    ORDER BY c1.`prodiJenjang` ASC,c1.`prodiKode` ASC";
                $result = $conn->QueryAll($q, [
                    ':fak' => $model->fakId,
                ]);
                $data = [];
                $tot = [];
                foreach ($result as $val) {
                    if ($val['LP'] == 'L') {
                        $data['L'][] = [$val['NAMA_PRODI'], (int) $val['JML']];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['NAMA_PRODI'], (int) $val['JML']];
                    }

                    if (!in_array($val['NAMA_PRODI'], $dataKategori)) {
                        $dataKategori[] = $val['NAMA_PRODI'];
                    }
                }
                for ($i = 0; $i < count($dataKategori); $i++) {
                    $tot[] = $data['L'][$i][1] + $data['P'][$i][1];
                }
                $dataColumn = [
                        [
                        'type' => 'column',
                        'name' => 'Laki-Laki',
                        'data' => $data['L'],
                    ],
                        [
                        'type' => 'column',
                        'name' => 'Perempuan',
                        'data' => $data['P'],
                    ],
                        [
                        'type' => 'spline',
                        'name' => 'Total',
                        'data' => $tot,
                    ],
                ];

                /*
                 * Data Tabel
                 */
                $qTbl = "SELECT 
                    c1.`prodiKode` AS KODE_PRODI,
                    CONCAT(c1.`prodiJenjang`,' - ',c1.`prodiNama`)AS NAMA_PRODI,
                    COUNT(*)AS JML
                    FROM dim_mahasiswa a1
                    JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                    JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                    JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                    WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                    AND a1.`mhsAngkatan` IN(" . $arrAkt . ") AND c1.`prodiFakId`=:fak
                    GROUP BY c1.`prodiKode`,c1.`prodiJenjang`
                    ORDER BY c1.`prodiJenjang` ASC,c1.`prodiKode` ASC";
                $dataTabel = $conn->QueryAll($qTbl, [
                    ':fak' => $model->fakId
                ]);
            }
        }

        return $this->render('perProdi', [
                    'model' => $model,
                    'refAkt' => $refAkt,
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn,
                    'dataTabel' => $dataTabel,
                    'rsAkt' => $rsAkt,
        ]);
    }

    public function actionPerAngkatan() {
        $conn = new DAO();
        $model = new Mahasiswa();
        $dataColumn = '';
        $dataKategori = '';
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('btn-search')) {
                if (!empty($model->fakId) && empty($model->prodiId)) {
                    $qGroup = "SELECT 
                        a.`mhsAngkatan` AS AKT,
                        a.`mhsProdiDikti` AS KODE_PRODI,
                        CONCAT(b.`prodiJenjang`,' - ',b.`prodiNama`)AS NAMA_PRODI,
                        COUNT(*)AS JML
                        FROM `dim_mahasiswa` a
                        JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                        WHERE a.`mhsStatus` IN('A') AND b.`prodiFakId`=:fak
                        GROUP BY a.`mhsAngkatan`
                        ORDER BY NAMA_PRODI ASC";
                    $rsGroup = $conn->QueryAll($qGroup, [
                        ':fak' => $model->fakId
                    ]);
                    $qItem = "SELECT 
                        a.`mhsAngkatan` AS AKT,
                        a.`mhsProdiDikti` AS KODE_PRODI,
                        a.`mhsJenkel` AS LP,
                        COUNT(*)AS JML
                        FROM `dim_mahasiswa` a
                        JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                        WHERE a.`mhsStatus` IN('A') AND b.`prodiFakId`=:fak
                        GROUP BY a.`mhsAngkatan`,a.`mhsJenkel`
                        ORDER BY AKT ASC";
                    $rsItem = $conn->QueryAll($qItem, [
                        ':fak' => $model->fakId
                    ]);
                    $data = [];
                    foreach ($rsGroup as $val) {
                        $dataKategori[] = $val['AKT'];
                    }
                    foreach ($rsItem as $valItem) {
                        if ($valItem['LP'] == 'L') {
                            $data['L'][] = [$valItem['AKT'], (int) $valItem['JML']];
                        }
                        if ($valItem['LP'] == 'P') {
                            $data['P'][] = [$valItem['AKT'], (int) $valItem['JML']];
                        }
                    }

                    $dataColumn = [
                            [
                            'name' => 'Laki-Laki',
                            'data' => $data['L'],
                        ],
                            [
                            'name' => 'Perempuan',
                            'data' => $data['P'],
                        ],
                    ];
                } else if (!empty($model->fakId) && !empty($model->prodiId)) {
                    $q = "SELECT 
                    a.`mhsAngkatan` AS AKT,
                    a.`mhsJenkel` AS LP,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    WHERE a.`mhsStatus` IN('A') AND b.`prodiFakId`=:fak 
                    AND a.`mhsProdiDikti`=:prodi
                    GROUP BY a.`mhsAngkatan`,a.`mhsJenkel`
                    ORDER BY a.`mhsAngkatan` ASC";
                    $result = $conn->QueryAll($q, [
                        ':fak' => $model->fakId,
                        ':prodi' => $model->prodiId
                    ]);
                    $data = [];
                    foreach ($result as $val) {
                        if ($val['LP'] == 'L') {
                            $data['L'][] = [$val['AKT'], (int) $val['JML']];
                        }
                        if ($val['LP'] == 'P') {
                            $data['P'][] = [$val['AKT'], (int) $val['JML']];
                            $dataKategori[] = $val['AKT'];
                        }
                    }
                    if (empty($data)) {
                        $dataColumn = [];
                    } else {
                        $dataColumn = [
                                [
                                'name' => 'Laki-Laki',
                                'data' => $data['L'],
                            ],
                                [
                                'name' => 'Perempuan',
                                'data' => $data['P'],
                            ],
                        ];
                    }
                }
            }
        }

        //echo '<br/><br/><br/><pre>';
        // print_r($dataColumn);
        //print_r($dataColumn);
        //echo '</pre>';

        return $this->render('perAngkatan', [
                    'model' => $model,
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
        ]);
    }

    public function actionPerJalur() {
        $conn = new DAO();
        $model = new Mahasiswa();
        $dataColumn = '';
        $dataKategori = '';
        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->fakId) && !empty($model->thnAkt)) {
                $qGroup = "SELECT 
                    d.`namaJalur` AS NAMA_JALUR,
                    a.`mhsJenkel` AS LP,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    JOIN `ref_jalur_map` c ON c.`mapIdJalur`=a.`mhsIdJalur`
                    JOIN `ref_jalur` d ON d.`idJalur`=c.`idJalur`
                    WHERE a.`mhsStatus` IN('A') AND a.`mhsAngkatan`=:akt 
                    GROUP BY d.`idJalur`,a.`mhsJenkel`,a.`mhsAngkatan`
                    ORDER BY a.`mhsAngkatan` ASC,d.`idJalur` ASC";
                $rsGroup = $conn->QueryAll($qGroup, [
                    ':akt' => $model->thnAkt
                ]);
                $data = [];
                foreach ($rsGroup as $val) {
                    if ($val['LP'] == 'L') {
                        $dataKategori[] = $val['NAMA_JALUR'];
                        $data['L'][] = [$val['NAMA_JALUR'], (int) $val['JML']];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['NAMA_JALUR'], (int) $val['JML']];
                    }
                }

                $dataColumn = [
                        [
                        'name' => 'Laki-Laki',
                        'data' => $data['L'],
                    ],
                        [
                        'name' => 'Perempuan',
                        'data' => $data['P'],
                    ],
                ];
            } else if (!empty($model->fakId) && !empty($model->thnAkt)) {
                $q = "SELECT 
                    d.`namaJalur` AS NAMA_JALUR,
                    a.`mhsJenkel` AS LP,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    JOIN `ref_jalur_map` c ON c.`mapIdJalur`=a.`mhsIdJalur`
                    JOIN `ref_jalur` d ON d.`idJalur`=c.`idJalur`
                    WHERE a.`mhsStatus` IN('A') AND a.`mhsAngkatan`=:akt AND b.`prodiFakId`=:fak
                    GROUP BY d.`idJalur`,a.`mhsJenkel`,a.`mhsAngkatan`
                    ORDER BY a.`mhsAngkatan` ASC,d.`idJalur` ASC";
                $result = $conn->QueryAll($q, [
                    ':fak' => $model->fakId,
                    ':akt' => $model->thnAkt
                ]);
                $data = [];
                foreach ($result as $val) {
                    if ($val['LP'] == 'L') {
                        $data['L'][] = [$val['NAMA_JALUR'], (int) $val['JML']];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['NAMA_JALUR'], (int) $val['JML']];
                        $dataKategori[] = $val['NAMA_JALUR'];
                    }
                }
                $dataColumn = [
                        [
                        'name' => 'Laki-Laki',
                        'data' => $data['L'],
                    ],
                        [
                        'name' => 'Perempuan',
                        'data' => $data['P'],
                    ],
                ];
            }
        }

        return $this->render('perJalur', [
                    'model' => $model,
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
        ]);
    }

    public function actionGetAktifFak($act, $params) {
        $conn = new DAO();
        if (\Yii::$app->request->isAjax) {
            $param = unserialize(urldecode($params));
            if ($act == 'by-prodi') {
                /*
                 * By Program Studi
                 * Data grafik pie
                 */
                $qPie = "SELECT 
                        CONCAT(mhs.prodiJenjang,' - ',mhs.prodiNama)AS PRODI_NAMA,
                        SUM(jml)AS JML
                        FROM (
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.prodiKode
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.prodiKode
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ") AND mhs.fakId=:fak 
                        GROUP BY mhs.prodiKode
                        ORDER BY mhs.prodiJenjang ASC";
                $rsPie = $conn->QueryAll($qPie, [
                    ':fak' => $param['fak']
                ]);
                $totMem = 0;
                foreach ($rsPie as $val) {
                    $totMem = $totMem + $val['JML'];
                }
                foreach ($rsPie as $val) {
                    $persentase = round((($val['JML'] / $totMem) * 100), 2);
                    $dataPie[] = ['name' => $val['PRODI_NAMA'] . ' ( ' . $val['JML'] . ' Org)', 'y' => $persentase];
                }

                $html = $this->renderAjax('_mhsAktifFakPieProdi', [
                    'dataPie' => $dataPie,
                    'fakId' => $param['fak']
                ]);

                return Json::encode($html);
            } else if ($act == 'by-angkatan') {
                /*
                 * By Angkatan
                 * Data grafik column
                 */
                $qCol7Thn = "SELECT 
                        mhs.mhsAngkatan AS ANGKATAN,
                        IF(mhs.mhsJenkel IS NULL,'null',mhs.mhsJenkel) AS LP,
                        SUM(jml)AS JML
                        FROM (
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.prodiKode,a1.`mhsJenkel`
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.prodiKode,a2.`mhsJenkel`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode,a3.`mhsJenkel`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ") 
                        AND mhs.mhsAngkatan>YEAR(NOW())-7 AND mhs.fakId=:fak 
                        GROUP BY mhs.mhsAngkatan,mhs.mhsJenkel
                        ORDER BY mhs.mhsAngkatan ASC";
                $rsCol7Thn = $conn->QueryAll($qCol7Thn, [
                    ':fak' => $param['fak'],
                ]);
                $qColKecil7Thn = "SELECT 
                        mhs.mhsAngkatan AS ANGKATAN,
                        IF(mhs.mhsJenkel IS NULL,'null',mhs.mhsJenkel) AS LP,
                        SUM(jml)AS JML
                        FROM (
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.prodiKode,a1.`mhsJenkel`
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.prodiKode,a2.`mhsJenkel`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode,a3.`mhsJenkel`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ") 
                        AND mhs.mhsAngkatan<=YEAR(NOW())-7 AND mhs.fakId=:fak 
                        GROUP BY mhs.mhsAngkatan,mhs.mhsJenkel
                        ORDER BY mhs.mhsAngkatan DESC";
                $rsColKecil7Thn = $conn->QueryAll($qColKecil7Thn, [
                    ':fak' => $param['fak']
                ]);

                $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan<=YEAR(NOW())-7 ORDER BY angkatan DESC LIMIT 1";
                $rsAkt = $conn->QueryRow($qAkt, [
                    ':fak' => $param['fak']
                ]);

                $dataKategori = [];
                $dataAkt = [];
                $data = [];
                $dataCol = [];
                $tot = [];
                $jL = 0;
                $jP = 0;
                foreach ($rsColKecil7Thn as $val) {
                    if ($val['LP'] == 'L') {
                        $jL = $jL + (int) $val['JML'];
                    }
                    if ($val['LP'] == 'P') {
                        $jP = $jP + (int) $val['JML'];
                    }
                }
                $dataAkt[] = $rsAkt['angkatan'];
                $data[$rsAkt['angkatan']]['L'] = [$rsAkt['angkatan'], $jL];
                $data[$rsAkt['angkatan']]['P'] = [$rsAkt['angkatan'], $jP];

                foreach ($rsCol7Thn as $val) {
                    if ($val['LP'] == 'L') {
                        $data[$val['ANGKATAN']]['L'] = [$val['ANGKATAN'], (int) $val['JML']];
                    }
                    if ($val['LP'] == 'P') {
                        $data[$val['ANGKATAN']]['P'] = [$val['ANGKATAN'], (int) $val['JML']];
                    }
                    if (!in_array($val['ANGKATAN'], $dataAkt)) {
                        $dataAkt[] = $val['ANGKATAN'];
                    }
                }
                for ($i = 0; $i < count($dataAkt); $i++) {
                    $jL = isset($data[$dataAkt[$i]]['L'][1]) ? $data[$dataAkt[$i]]['L'][1] : 0;
                    $jP = isset($data[$dataAkt[$i]]['P'][1]) ? $data[$dataAkt[$i]]['P'][1] : 0;
                    $tot[] = $jL + $jP;

                    if (!in_array($dataAkt[$i], $dataKategori)) {
                        if ($rsAkt['angkatan'] == $dataAkt[$i]) {
                            $dataKategori[] = '<=' . $dataAkt[$i];
                        } else {
                            $dataKategori[] = $dataAkt[$i];
                        }
                    }
                    if (isset($data[$dataAkt[$i]]['L'])) {
                        $dataCol['L'][] = [$dataAkt[$i], (int) $data[$dataAkt[$i]]['L'][1]];
                    } else {
                        $dataCol['L'][] = [$dataAkt[$i], (int) 0];
                    }
                    if (isset($data[$dataAkt[$i]]['P'])) {
                        $dataCol['P'][] = [$dataAkt[$i], (int) $data[$dataAkt[$i]]['P'][1]];
                    } else {
                        $dataCol['P'][] = [$dataAkt[$i], (int) 0];
                    }
                }
                //print_r($dataKategori);
                //print_r($data);
                //print_r($dataCol);
//                exit();
                $dataColumn = [
                        [
                        'type' => 'column',
                        'name' => 'Laki-Laki',
                        'data' => $dataCol['L'],
                    ],
                        [
                        'type' => 'column',
                        'name' => 'Perempuan',
                        'data' => $dataCol['P'],
                    ],
                        [
                        'type' => 'spline',
                        'name' => 'Total',
                        'data' => $tot,
                    ],
                ];

                $html = $this->renderAjax('_mhsAktifFakColAngkatan', [
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn,
                    'fakId' => $param['fak']
                ]);

                return Json::encode($html);
            } else if ($act == 'by-ipk') {
                /*
                 * By IPK Mahasiswa
                 * Data grafik column
                 */
                $qCol7Thn = "SELECT mhsIpk.kelIpk,
                    SUM(mhsIpk.jml)AS jml
                    FROM(		
			SELECT 
                        *,
                        IFNULL((
                        SELECT 
                        IF(ROUND(ip.ipIpk,2)<=4.00 AND ROUND(ip.ipIpk,2)>3.50,'IPK4.0-3.5',
                        IF(ROUND(ip.ipIpk,2)<=3.50 AND ROUND(ip.ipIpk,2)>3.00,'IPK3.5-3.0',
                        IF(ROUND(ip.ipIpk,2)<=3.00 AND ROUND(ip.ipIpk,2)>2.50,'IPK3.0-2.5',
                        IF(ROUND(ip.ipIpk,2)<=2.50 AND ROUND(ip.ipIpk,2)>2.00,'IPK2.5-2.0',
                        IF(ROUND(ip.ipIpk,2)<=2.00 AND ROUND(ip.ipIpk,2)>1.00,'IPK2.0-1.0','IPK1.0-0.0'))))) 
                        FROM `fact_ipk` ip WHERE ip.`ipMhsNiu`=mhs.mhsNiu ORDER BY ip.`ipSmtId` DESC LIMIT 1
                        ),'IPK1.0-0.0')AS kelIpk
                        FROM (
                            SELECT 
                            a1.`mhsNiu`,
                            a1.`mhsNama`,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiNama`,
                            c1.`prodiJenjang`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.prodiKode,a1.`mhsJenkel`,a1.`mhsNiu`
                            UNION
                            SELECT 
                            a2.`mhsNiu`,
                            a2.`mhsNama`,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiNama`,
                            c2.`prodiJenjang`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.prodiKode,a2.`mhsJenkel`,a2.`mhsNiu`
                            UNION
                            SELECT 
                            a3.`mhsNiu`,
                            a3.`mhsNama`,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiNama`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode,a3.`mhsJenkel`,a3.`mhsNiu`
                        )AS mhs
                        GROUP BY mhs.mhsNiu
                    )AS mhsIpk                        
                    WHERE mhsIpk.mhsAngkatan IN(" . $param['akt'] . ") AND mhsIpk.fakId=:fak
                    GROUP BY mhsIpk.kelIpk
                    ORDER BY mhsIpk.kelIpk DESC";
                $rsCol7Thn = $conn->QueryAll($qCol7Thn, [
                    ':fak' => $param['fak']
                ]);

                $dataKategori = [];
                $dataCol = [];

                foreach ($rsCol7Thn as $val) {
                    $dataCol['IPK'][] = [str_replace('IPK', 'IPK ', $val['kelIpk']), (int) $val['jml']];
                    if (!in_array($val['kelIpk'], $dataKategori)) {
                        $dataKategori[] = str_replace('IPK', 'IPK ', $val['kelIpk']);
                    }
                }
                $dataColumn = [
                        [
                        'type' => 'column',
                        'name' => 'Jumlah',
                        'data' => $dataCol['IPK'],
                    ],
                ];

                $html = $this->renderAjax('_mhsAktifFakColIpk', [
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn,
                    'fakId' => $param['fak']
                ]);

                return Json::encode($html);
            }
        }
    }

    public function actionGetAktifFakDetail($act, $params) {
        $conn = new DAO();
        if (\Yii::$app->request->isAjax) {
            $param = unserialize(urldecode($params));
            if ($act == 'by-prodi') {
                /*
                 * Data grafik pie
                 */
                $qProdi = "SELECT 
			mhs.prodiKode AS PRODI_KODE,
                        CONCAT(mhs.prodiJenjang,' - ',mhs.prodiNama) AS PRODI_NAMA,
                        SUM(mhs.jml) AS JML
                        FROM (
                            SELECT 
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,c1.`prodiKode`
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,c2.`prodiKode`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,c3.`prodiKode`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ") AND mhs.fakId=:fak
                        GROUP BY mhs.prodiKode
                        ORDER BY mhs.prodiJenjang, mhs.prodiKode ASC";
                $dataTabel = $conn->QueryAll($qProdi, [
                    ':fak'=>$param['fak']
                ]);
                /*
                 * Data angkatan
                 */
                $qAkt = "SELECT 
                        angkatan AS AKT,
                        (YEAR(NOW())-7) AS LAST_AKT
                        FROM(
                        (SELECT angkatan FROM ref_angkatan
                        WHERE angkatan<=YEAR(NOW())-7
                        ORDER BY angkatan DESC
                        LIMIT 1)                        
                        UNION
                        (SELECT angkatan FROM ref_angkatan
                        WHERE angkatan>YEAR(NOW())-7)
                        )AS akt";
                $dataTabel_akt = $conn->QueryAll($qAkt, []);

                $html = $this->renderAjax('_detailMhsAktifFakByProdi', [
                    'dataTabel' => $dataTabel,
                    'dataTabel_akt' => $dataTabel_akt,
                    'fakId'=>$param['fak']
                ]);

                return Json::encode($html);
            }
        }
    }

    public function actionAktifFakultas() {
        $conn = new DAO();
        $model = new Mahasiswa();
        $dataPie = '';
        $jmlAktif = 0;
        $jmlCuti = 0;
        $jmlNonAktif = 0;
        $jmlMabaD3 = 0;
        $jmlMabaS1 = 0;
        $jmlMabaS2 = 0;
        $jmlMabaS3 = 0;
        $jmlMabaSp = 0;
        $jmlMabaPro = 0;
        $jmlMabaTotal = 0;
        $arrAkt = '';
        $rsAkt = [];
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->fakId)) {
                $qAkt = "SELECT angkatan FROM ref_angkatan"; // WHERE angkatan>YEAR(NOW())-7";
                $rsAkt = $conn->QueryAll($qAkt, []);
                foreach ($rsAkt as $valakt) {
                    if ($arrAkt == '') {
                        $arrAkt = $valakt['angkatan'];
                    } else {
                        $arrAkt = $arrAkt . ',' . $valakt['angkatan'];
                    }
                }
                /*
                 * Jumlah Mahasiswa berdasarkan Status A,N,C
                 */
                $qJmlStatus = "SELECT 
                        mhs.prodiJenjang AS JENJANG,
                        mhs.ket AS STATUS_MHS,
                        mhs.jml AS JML 
                        FROM (
                            SELECT 
                            a1.`mhsAngkatan`,
                            c1.`prodiJenjang`,
                            c1.`prodiFakId`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.`prodiFakId`
                            UNION
                            SELECT 
                            a2.`mhsAngkatan`,
                            c2.`prodiJenjang`,
                            c2.`prodiFakId`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.`prodiFakId`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            c3.`prodiJenjang`,
                            c3.`prodiFakId`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.`prodiFakId`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $arrAkt . ") AND mhs.prodiFakId=:fak 
                        ORDER BY mhs.prodiJenjang ASC";
                $rsJmlStatus = $conn->QueryAll($qJmlStatus, [
                    ':fak' => $model->fakId
                ]);
                foreach ($rsJmlStatus as $val) {
                    if ($val['STATUS_MHS'] == 'A') {
                        $jmlAktif = $jmlAktif + $val['JML'];
                    }
                    if ($val['STATUS_MHS'] == 'C') {
                        $jmlCuti = $jmlCuti + $val['JML'];
                    }
                    if ($val['STATUS_MHS'] == 'N') {
                        $jmlNonAktif = $jmlNonAktif + $val['JML'];
                    }
                }
                /*
                 * Data grafik pie
                 */
                $qPie = "SELECT 
                mhs.prodiJenjang AS JENJANG,
                SUM(mhs.jml)AS JML
                FROM (
                    SELECT 
                    a1.`mhsAngkatan`,
                    c1.`prodiJenjang`,
                    c1.`prodiFakId`,
                    'A' AS ket,
                    COUNT(*)AS jml
                    FROM dim_mahasiswa a1
                    JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                    JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                    JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                    WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                    GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.`prodiFakId`
                    UNION
                    SELECT 
                    a2.`mhsAngkatan`,
                    c2.`prodiJenjang`,
                    c2.`prodiFakId`,
                    'C' AS ket,
                    COUNT(*)AS jml
                    FROM dim_mahasiswa a2
                    JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                    JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                    JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                    WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                    GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.`prodiFakId`
                    UNION
                    SELECT 
                    a3.`mhsAngkatan`,
                    c3.`prodiJenjang`,
                    c3.`prodiFakId`,
                    'N' AS ket,
                    COUNT(*)AS jml
                    FROM dim_mahasiswa a3
                    JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                    WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                    GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.`prodiFakId`
                )AS mhs
                WHERE mhs.mhsAngkatan IN(" . $arrAkt . ") AND mhs.prodiFakId=:fak 
                GROUP BY mhs.prodiJenjang
                ORDER BY mhs.prodiJenjang ASC";
                $rsPie = $conn->QueryAll($qPie, [
                    ':fak' => $model->fakId
                ]);
                $totMhs = 0;
                foreach ($rsPie as $val) {
                    $totMhs = $totMhs + $val['JML'];
                }
                foreach ($rsPie as $val) {
                    $persentase = round((($val['JML'] / $totMhs) * 100), 2);
                    $dataPie[] = ['name' => $val['JENJANG'] . ' ( ' . $val['JML'] . ' Org )', 'y' => $persentase];
                }

                /*
                 * Jumlah Mahasiswa Baru 
                 */
                $qJmlMaba = "SELECT 
                    mhs.mhsAngkatan AS AKT,
                    mhs.prodiJenjang AS JENJANG,
                    mhs.jml AS JML
                    FROM (
                        SELECT 
                        a1.`mhsAngkatan`,
                        c1.`prodiJenjang`,
                        c1.`prodiFakId`,
                        'A' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a1
                        JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                        JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                        JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                        WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                        GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,c1.`prodiFakId`
                        UNION
                        SELECT 
                        a2.`mhsAngkatan`,
                        c2.`prodiJenjang`,
                        c2.`prodiFakId`,
                        'C' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a2
                        JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                        JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                        JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                        WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                        GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.`prodiFakId`
                        UNION
                        SELECT 
                        a3.`mhsAngkatan`,
                        c3.`prodiJenjang`,
                        c3.`prodiFakId`,
                        'N' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a3
                        JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                        WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                        GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.`prodiFakId`
                    )AS mhs
                    WHERE mhs.mhsAngkatan IN(SELECT SUBSTR(smtId,1,4) FROM app_semester WHERE smtIsAktif='1') 
                    AND mhs.prodiFakId=:fak 
                    GROUP BY mhs.prodiJenjang,mhs.mhsAngkatan 
                    ORDER BY mhs.prodiJenjang,mhs.mhsAngkatan ASC";
                $rsJmlMaba = $conn->QueryAll($qJmlMaba, [
                    ':fak' => $model->fakId
                ]);
                foreach ($rsJmlMaba as $val) {
                    if ($val['JENJANG'] == 'D3') {
                        $jmlMabaD3 = $jmlMabaD3 + $val['JML'];
                    }
                    if ($val['JENJANG'] == 'S1') {
                        $jmlMabaS1 = $jmlMabaS1 + $val['JML'];
                    }
                    if ($val['JENJANG'] == 'S2') {
                        $jmlMabaS2 = $jmlMabaS2 + $val['JML'];
                    }
                    if ($val['JENJANG'] == 'S3') {
                        $jmlMabaS3 = $jmlMabaS3 + $val['JML'];
                    }
                    if ($val['JENJANG'] == 'SP-1') {
                        $jmlMabaSp = $jmlMabaSp + $val['JML'];
                    }
                    if ($val['JENJANG'] == 'Profesi') {
                        $jmlMabaPro = $jmlMabaPro + $val['JML'];
                    }
                }
                $jmlMabaTotal = $jmlMabaD3 + $jmlMabaS1 + $jmlMabaS2 + $jmlMabaS3 + $jmlMabaSp + $jmlMabaPro;
            }
        }

        return $this->render('mhsAktifFak', [
                    'model' => $model,
                    'arrAkt' => $arrAkt,
                    'dataPie' => $dataPie,
                    'jmlAktif' => $jmlAktif,
                    'jmlCuti' => $jmlCuti,
                    'jmlNonAktif' => $jmlNonAktif,
                    'totalMhs' => $jmlAktif + $jmlCuti + $jmlNonAktif,
                    'jmlMabaD3' => $jmlMabaD3,
                    'jmlMabaS1' => $jmlMabaS1,
                    'jmlMabaS2' => $jmlMabaS2,
                    'jmlMabaS3' => $jmlMabaS3,
                    'jmlMabaSp' => $jmlMabaSp,
                    'jmlMabaPro' => $jmlMabaPro,
                    'jmlMabaTotal' => $jmlMabaTotal,
                    'fakId' => $model->fakId
        ]);
    }

}
