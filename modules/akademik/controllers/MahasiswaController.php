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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                 * Data Tabel
                 */
                
                $qDataTabel = "SELECT 
			mhs.prodiKode AS PRODI_KODE,
                        CONCAT(mhs.prodiJenjang,' - ',mhs.prodiNama) AS PRODI_NAMA,
                        mhs.mhsAngkatan AS AKT,
                        mhs.mhsJenkel AS LP,
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,e3.fakId,c3.`prodiKode`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.mhsAngkatan,mhs.prodiKode,mhs.mhsJenkel
                        ORDER BY mhs.mhsAngkatan ASC,mhs.prodiJenjang ASC, mhs.prodiKode ASC";
                $rsDataTabel = $conn->QueryAll($qDataTabel, []);
                
                $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan=YEAR(NOW())-7";
                $rsAkt = $conn->QueryRow($qAkt, []);

                $tmpTabel['last_akt'] = $rsAkt['angkatan'];
                $tmpTabel['akt'] = [];
                $tmpTabel['dimValue'][][][] = [];
                $tmpTabel['programStudi'] = [];
                foreach ($rsDataTabel as $valT) {
                    if ($valT['AKT'] > $rsAkt['angkatan']) {
                        if (!in_array($valT['AKT'], $tmpTabel['akt'])) {
                            $tmpTabel['akt'][] = $valT['AKT'];
                        }
                        $tmpTabel['dimValue'][$valT['AKT']][$valT['LP']][$valT['PRODI_KODE']] = $valT['JML'];
                    } else {
                        if (!in_array($rsAkt['angkatan'], $tmpTabel['akt'])) {
                            $tmpTabel['akt'][] = $rsAkt['angkatan'];
                        }
                        if (!in_array($valT['AKT'], $tmpTabel['akt'])) {
                            
                        }
                        $tmpTabel['dimValue'][$rsAkt['angkatan']][$valT['LP']][$valT['PRODI_KODE']][] = $valT['JML'];
                    }
                    if (!in_array(['prodiId' => $valT['PRODI_KODE'], 'prodiNama' => $valT['PRODI_NAMA']], $tmpTabel['programStudi'])) {
                        $tmpTabel['programStudi'][] = ['prodiId' => $valT['PRODI_KODE'], 'prodiNama' => $valT['PRODI_NAMA']];
                    }
                }

                sort($tmpTabel['akt']);
                $dataTabel = $tmpTabel;

                $html = $this->renderAjax('_detailMhsAktifByProdi', [
                    'dataTabel' => $dataTabel,
                ]);

                return Json::encode($html);
            } else if ($act == 'by-fakultas') {
                /*
                 * By Fakultas
                 * Data grafik column
                 */

                $qDataTabel = "SELECT 
			mhs.fakId AS FAK_KODE,
                        mhs.fakNama AS FAK_NAMA,
                        mhs.mhsAngkatan AS AKT,
                        mhs.mhsJenkel AS LP,
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,e3.fakId,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.mhsAngkatan,mhs.fakId,mhs.mhsJenkel
                        ORDER BY mhs.fakId ASC,mhs.mhsAngkatan ASC";
                $rsDataTabel = $conn->QueryAll($qDataTabel, []);

                $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan=YEAR(NOW())-7";
                $rsAkt = $conn->QueryRow($qAkt, []);

                $tmpTabel['last_akt'] = $rsAkt['angkatan'];
                $tmpTabel['akt'] = [];
                $tmpTabel['dimValue'][][][] = [];
                $tmpTabel['fakultas'] = [];
                foreach ($rsDataTabel as $valT) {
                    if ($valT['AKT'] > $rsAkt['angkatan']) {
                        if (!in_array($valT['AKT'], $tmpTabel['akt'])) {
                            $tmpTabel['akt'][] = $valT['AKT'];
                        }
                        $tmpTabel['dimValue'][$valT['AKT']][$valT['LP']][$valT['FAK_KODE']] = $valT['JML'];
                    } else {
                        if (!in_array($rsAkt['angkatan'], $tmpTabel['akt'])) {
                            $tmpTabel['akt'][] = $rsAkt['angkatan'];
                        }
                        if (!in_array($valT['AKT'], $tmpTabel['akt'])) {
                            
                        }
                        $tmpTabel['dimValue'][$rsAkt['angkatan']][$valT['LP']][$valT['FAK_KODE']][] = $valT['JML'];
                    }
                    if (!in_array(['fakId' => $valT['FAK_KODE'], 'fakNama' => $valT['FAK_NAMA']], $tmpTabel['fakultas'])) {
                        $tmpTabel['fakultas'][] = ['fakId' => $valT['FAK_KODE'], 'fakNama' => $valT['FAK_NAMA']];
                    }
                }

                sort($tmpTabel['akt']);
                $dataTabel = $tmpTabel;

                $html = $this->renderAjax('_detailMhsAktifByFakultas', [
                    'dataTabel' => $dataTabel,
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            c3.`prodiJenjang`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                    WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                    AND d1.smtIsAktif='1'
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
                    WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                    AND d2.smtIsAktif='1'
                    GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,a2.`mhsJenkel`,a2.mhsNiu
                    UNION
                    SELECT 
                    a3.mhsNiu,
                    a3.`mhsAngkatan`,
                    c3.`prodiJenjang`,
                    'N' AS ket
                    #COUNT(*)AS jml
                    FROM dim_mahasiswa a3
                    JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                    JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                    JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                    WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                    AND d3.smtIsAktif='1'
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
                    SUM(mhs.jml) AS JML
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
                        WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                        AND d1.smtIsAktif='1'
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
                        WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                        AND d2.smtIsAktif='1'
                        GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`
                        UNION
                        SELECT 
                        a3.`mhsAngkatan`,
                        c3.`prodiJenjang`,
                        'N' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a3
                        JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                        JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                        JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                        WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                        AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                 * By Program Studi
                 * Data Tabel
                 */
                $qDataTabel = "SELECT 
			mhs.prodiKode AS PRODI_KODE,
                        CONCAT(mhs.prodiJenjang,' - ',mhs.prodiNama) AS PRODI_NAMA,
                        mhs.mhsAngkatan AS AKT,
                        mhs.mhsJenkel AS LP,
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
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
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,a3.`mhsJenkel`,e3.fakId,c3.`prodiKode`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ") AND mhs.fakId=:fak
                        GROUP BY mhs.mhsAngkatan,mhs.prodiKode,mhs.mhsJenkel
                        ORDER BY mhs.mhsAngkatan ASC,mhs.prodiJenjang ASC, mhs.prodiKode ASC";
                $rsDataTabel = $conn->QueryAll($qDataTabel, [
                    ':fak' => $param['fak']
                ]);
                
                $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan=YEAR(NOW())-7";
                $rsAkt = $conn->QueryRow($qAkt, []);

                $tmpTabel['last_akt'] = $rsAkt['angkatan'];
                $tmpTabel['akt'] = [];
                $tmpTabel['dimValue'][][][] = [];
                $tmpTabel['programStudi'] = [];
                foreach ($rsDataTabel as $valT) {
                    if ($valT['AKT'] > $rsAkt['angkatan']) {
                        if (!in_array($valT['AKT'], $tmpTabel['akt'])) {
                            $tmpTabel['akt'][] = $valT['AKT'];
                        }
                        $tmpTabel['dimValue'][$valT['AKT']][$valT['LP']][$valT['PRODI_KODE']] = $valT['JML'];
                    } else {
                        if (!in_array($rsAkt['angkatan'], $tmpTabel['akt'])) {
                            $tmpTabel['akt'][] = $rsAkt['angkatan'];
                        }
                        if (!in_array($valT['AKT'], $tmpTabel['akt'])) {
                            
                        }
                        $tmpTabel['dimValue'][$rsAkt['angkatan']][$valT['LP']][$valT['PRODI_KODE']][] = $valT['JML'];
                    }
                    if (!in_array(['prodiId' => $valT['PRODI_KODE'], 'prodiNama' => $valT['PRODI_NAMA']], $tmpTabel['programStudi'])) {
                        $tmpTabel['programStudi'][] = ['prodiId' => $valT['PRODI_KODE'], 'prodiNama' => $valT['PRODI_NAMA']];
                    }
                }

                sort($tmpTabel['akt']);
                $dataTabel = $tmpTabel;
                
                $html = $this->renderAjax('_detailMhsAktifFakByProdi', [
                    'dataTabel' => $dataTabel,
                    'fakId' => $param['fak']
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
                            WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                            AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                            AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.`prodiFakId`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            c3.`prodiJenjang`,
                            c3.`prodiFakId`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                            WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                            AND d3.smtIsAktif='1'
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
                    WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                    AND d1.smtIsAktif='1'
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
                    WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                    AND d2.smtIsAktif='1'
                    GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.`prodiFakId`
                    UNION
                    SELECT 
                    a3.`mhsAngkatan`,
                    c3.`prodiJenjang`,
                    c3.`prodiFakId`,
                    'N' AS ket,
                    COUNT(*)AS jml
                    FROM dim_mahasiswa a3
                    JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                    JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                    JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                    WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                    AND d3.smtIsAktif='1'
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
                        WHERE a1.`mhsJenkel`<>'' #AND a1.`mhsStatus` IN('A') 
                        AND d1.smtIsAktif='1'
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
                        WHERE a2.`mhsJenkel`<>'' #AND a2.`mhsStatus` IN('C') 
                        AND d2.smtIsAktif='1'
                        GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,c2.`prodiFakId`
                        UNION
                        SELECT 
                        a3.`mhsAngkatan`,
                        c3.`prodiJenjang`,
                        c3.`prodiFakId`,
                        'N' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a3
                        JOIN `fact_mahasiswa_nonaktif` b3 ON b3.`mhsnNiu`=a3.`mhsNiu`
                        JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                        JOIN `app_semester` d3 ON d3.smtId=b3.`mhsnSmtId`
                        WHERE a3.`mhsJenkel`<>'' #AND a3.`mhsStatus` IN('N')
                        AND d3.smtIsAktif='1'
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
