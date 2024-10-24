<?php

namespace app\modules\mahasiswa\controllers;

use Yii;
use app\modules\mahasiswa\models\Terdaftar;
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
class TerdaftarController extends Controller {

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
                    'get-aktif' => ['POST', 'GET'],
                    'get-aktif-detail' => ['POST', 'GET']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'per-prodi',
                    'per-angkatan',
                    'aktif',
                    'get-aktif',
                    'get-aktif-detail'
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlMhsTerdaftar'),
                        [
                        'allow' => true,
                        'actions' => [
                            'get-aktif',
                            'get-aktif-detail'
                        ],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    private $dbSireg = 'dbSireg';
    private $dbSiaFh = 'dbSiaFh';

    public function actionGetAktif($act, $params) {
        $conn = new DAO();
        if (\Yii::$app->request->isAjax) {
            $param = unserialize(urldecode($params));
            if ($act == 'by-prodi') {
                /*
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
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
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode
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
                 * Data grafik column
                 */
                $qCol = "SELECT 
                        mhs.fakNama AS FAK_NAMA,
                        mhs.mhsJenkel AS LP,
                        SUM(jml)AS JML
                        FROM (
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
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
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.fakId,mhs.mhsJenkel
                        ORDER BY mhs.fakId ASC";
                $rsCol = $conn->QueryAll($qCol, []);
                $data = [];
                foreach ($rsCol as $val) {
                    if ($val['LP'] == 'L') {
                        $data['L'][] = [$val['FAK_NAMA'], (int) $val['JML']];
                        $dataKategori[] = $val['FAK_NAMA'];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['FAK_NAMA'], (int) $val['JML']];
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

                $html = $this->renderAjax('_mhsAktifColFakultas', [
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
                ]);

                return Json::encode($html);
            } else if ($act == 'by-angkatan') {
                /*
                 * Data grafik column
                 */
                $qCol = "SELECT 
                        mhs.mhsAngkatan AS ANGKATAN,
                        mhs.mhsJenkel AS LP,
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
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
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,c3.prodiKode,a3.`mhsJenkel`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.mhsAngkatan,mhs.mhsJenkel
                        ORDER BY mhs.mhsAngkatan ASC";
                $rsCol = $conn->QueryAll($qCol, []);
                $data = [];
                foreach ($rsCol as $val) {
                    if ($val['LP'] == 'L') {
                        $data['L'][] = [$val['ANGKATAN'], (int) $val['JML']];
                        $dataKategori[] = $val['ANGKATAN'];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['ANGKATAN'], (int) $val['JML']];
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

                $html = $this->renderAjax('_mhsAktifColAngkatan', [
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
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
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,c3.`prodiKode`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.prodiKode
                        ORDER BY mhs.prodiJenjang, mhs.prodiKode ASC";
                $dataTabel = $conn->QueryAll($qProdi, []);
                /*
                 * Data angkatan
                 */
                $qAkt = "SELECT 
			mhs.mhsAngkatan AS AKT
                        FROM (
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
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
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.mhsAngkatan
                        ORDER BY mhs.mhsAngkatan ASC";
                $dataTabel_akt = $conn->QueryAll($qAkt, []);

                $html = $this->renderAjax('_detailMhsAktifByProdi', [
                    'dataTabel' => $dataTabel,
                    'dataTabel_akt' => $dataTabel_akt
                ]);

                return Json::encode($html);
            } else if ($act == 'by-fakultas') {
                /*
                 * Data grafik column
                 */
                $qCol = "SELECT 
			mhs.fakId AS FAK_KODE,
                        mhs.fakNama AS FAK_NAMA,
                        SUM(mhs.jml) AS JML
                        FROM (
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
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
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.fakId
                        ORDER BY mhs.fakId ASC";
                $dataTabel = $conn->QueryAll($qCol, []);
                /*
                 * Data angkatan
                 */
                $qAkt = "SELECT 
			mhs.mhsAngkatan AS AKT
                        FROM (
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
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
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $param['akt'] . ")
                        GROUP BY mhs.mhsAngkatan
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

    public function actionGetAktif_old($act, $params) {
        $conn = new DAO();
        if (\Yii::$app->request->isAjax) {
            $param = unserialize(urldecode($params));
            if ($act == 'by-prodi') {
                /*
                 * Data grafik pie
                 */
                $qPie = "SELECT 
                    CONCAT(b.prodiJenjang,' - ',b.prodiNama)AS prodiNama,
                    COUNT(*)AS jml
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.prodiKode=a.mhsProdiDikti
                    WHERE a.`mhsStatus` IN('A','C','N') AND a.mhsJenkel<>''
                    AND a.mhsAngkatan IN(" . $param['akt'] . ")
                    GROUP BY b.prodiKode
                    ORDER BY b.prodiJenjang ASC";
                $rsPie = $conn->QueryAll($qPie, []);
                $totMem = 0;
                foreach ($rsPie as $val) {
                    $totMem = $totMem + $val['jml'];
                }
                foreach ($rsPie as $val) {
                    $persentase = round((($val['jml'] / $totMem) * 100), 2);
                    $dataPie[] = ['name' => $val['prodiNama'] . ' ( ' . $val['jml'] . ' Org)', 'y' => $persentase];
                }

                $html = $this->renderAjax('_mhsAktifPieProdi', [
                    'dataPie' => $dataPie,
                ]);

                return Json::encode($html);
            } else if ($act == 'by-fakultas') {
                /*
                 * Data grafik column
                 */
                $qCol = "SELECT 
                    c.`fakNama` AS FAK_NAMA,a.`mhsJenkel` AS LP,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.prodiKode=a.mhsProdiDikti
                    JOIN `ref_fakultas` c ON c.`fakId`=b.`prodiFakId`
                    WHERE a.`mhsStatus` IN('A','C','N') AND a.mhsJenkel<>''
                    AND a.mhsAngkatan IN(" . $param['akt'] . ")
                    GROUP BY c.`fakId`,a.`mhsJenkel`
                    ORDER BY b.prodiJenjang ASC";
                $rsCol = $conn->QueryAll($qCol, []);
                $data = [];
                foreach ($rsCol as $val) {
                    if ($val['LP'] == 'L') {
                        $data['L'][] = [$val['FAK_NAMA'], (int) $val['JML']];
                        $dataKategori[] = $val['FAK_NAMA'];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['FAK_NAMA'], (int) $val['JML']];
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

                $html = $this->renderAjax('_mhsAktifColFakultas', [
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
                ]);

                return Json::encode($html);
            }
        }
    }

    public function actionAktif() {
        $conn = new DAO();
        $model = new Terdaftar();
        $dataPie = '';
        $jmlAktif = 0;
        $jmlCuti = 0;
        $jmlNonAktif = 0;
        $jmlMabaTotal = 0;
        $jmlMabaD3 = 0;
        $jmlMabaS1 = 0;
        $jmlMabaS2 = 0;
        $jmlMabaS3 = 0;
        $jmlMabaSp = 0;
        $jmlMabaPro = 0;
        $arrAkt = '';

        $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan>=2011";
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
                        /*,SUM(jml)AS total*/
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(" . $arrAkt . ")
                        /*GROUP BY mhs.prodiJenjang*/
                        ORDER BY mhs.prodiJenjang ASC";
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
                            WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                            WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`
                            UNION
                            SELECT 
                            a3.`mhsAngkatan`,
                            c3.`prodiJenjang`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            WHERE a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`
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
                        WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
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
                        WHERE a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                        GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`
                        UNION
                        SELECT 
                        a3.`mhsAngkatan`,
                        c3.`prodiJenjang`,
                        'N' AS ket,
                        COUNT(*)AS jml
                        FROM dim_mahasiswa a3
                        JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                        WHERE a3.`mhsStatus` IN('N')
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
            if ($val['JENJANG'] == 'SP-1') {
                $jmlMabaSp = $jmlMabaSp + $val['JML'];
            }
            if ($val['JENJANG'] == 'Profesi') {
                $jmlMabaPro = $jmlMabaPro + $val['JML'];
            }
        }

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
                    'jmlMabaTotal' =>$jmlMabaD3 + $jmlMabaS1 + $jmlMabaS2 + $jmlMabaS3 + $jmlMabaSp + $jmlMabaPro
        ]);
    }

    public function actionAktif_old() {
        $conn = new DAO();
        $model = new Terdaftar();
        $dataPie = '';
        $jmlAktif = 0;
        $jmlCuti = 0;
        $jmlNonAktif = 0;
        $dataTabel = [];
        $dataTabel_akt = [];
        $arrAkt = '';
        $dataKolom = '';
        $dataKategori = '';
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->thnAkt)) {
                for ($i = 0; $i < count($model->thnAkt); $i++) {
                    if ($arrAkt == '') {
                        $arrAkt = $model->thnAkt[$i];
                    } else {
                        $arrAkt = $arrAkt . ',' . $model->thnAkt[$i];
                    }
                }
                /*
                 * Jumlah Mahasiswa berdasarkan Status A,N,C
                 */
                $qJmlStatus = "SELECT 
                    a.`mhsStatus` AS STATUS_MHS,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.prodiKode=a.mhsProdiDikti
                    WHERE a.`mhsStatus` IN('A','C','N') AND a.mhsJenkel<>''
                    AND a.mhsAngkatan IN(" . $arrAkt . ")
                    GROUP BY a.`mhsStatus`
                    ORDER BY b.prodiJenjang ASC";
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
                    b.prodiJenjang AS JENJANG,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.prodiKode=a.mhsProdiDikti
                    WHERE a.`mhsStatus` IN('A','C','N') AND a.mhsJenkel<>''
                    AND a.mhsAngkatan IN(" . $arrAkt . ")
                    GROUP BY b.`prodiJenjang`
                    ORDER BY b.prodiJenjang ASC";
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
                 * Data daftar table
                 */
                $qTabel = "SELECT 
                    CONCAT(b.prodiJenjang,' - ',b.prodiNama)AS NAMA_PRODI,
                    b.`prodiKode` AS KODE_PRODI,
                    count(*) AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.prodiKode=a.mhsProdiDikti
                    WHERE a.`mhsStatus` IN('A','C','N') AND a.mhsJenkel<>''
                    AND a.mhsAngkatan IN(" . $arrAkt . ")
                    GROUP BY b.`prodiKode`
                    ORDER BY b.prodiJenjang ASC";
                $dataTabel = $conn->QueryAll($qTabel, []);
                /*
                 * Data angkatan
                 */
                $qAkt = "SELECT 
                    a.`mhsAngkatan` AS AKT
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.prodiKode=a.mhsProdiDikti
                    WHERE a.`mhsStatus` IN('A','C','N') AND a.mhsJenkel<>''
                    AND a.mhsAngkatan IN(" . $arrAkt . ")
                    GROUP BY a.`mhsAngkatan`
                    ORDER BY a.`mhsAngkatan` ASC";
                $dataTabel_akt = $conn->QueryAll($qAkt, []);
            }
        }
        return $this->render('mhsAktif', [
                    'model' => $model,
                    'arrAkt' => $arrAkt,
                    'dataPie' => $dataPie,
                    'jmlAktif' => $jmlAktif,
                    'jmlCuti' => $jmlCuti,
                    'jmlNonAktif' => $jmlNonAktif,
                    'totalMhs' => $jmlAktif + $jmlCuti + $jmlNonAktif,
                    'dataTabel' => $dataTabel,
                    'dataTabel_akt' => $dataTabel_akt,
        ]);
    }

    public function actionPerProdi() {
        $conn = new DAO();
        $model = new Terdaftar();
        $dataColumn = '';
        $dataKategori = '';
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->fakId) && empty($model->thnAkt)) {
                $qGroup = "SELECT 
                    a.`mhsProdiDikti` AS KODE_PRODI,
                    CONCAT(b.`prodiJenjang`,' - ',b.`prodiNama`)AS NAMA_PRODI,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    WHERE a.`mhsStatus` IN('A') AND b.`prodiFakId`=:fak
                    GROUP BY a.`mhsProdiDikti`
                    ORDER BY NAMA_PRODI ASC";
                $rsGroup = $conn->QueryAll($qGroup, [
                    ':fak' => $model->fakId
                ]);
                $qItem = "SELECT 
                    a.`mhsAngkatan` AS AKT,
                    a.`mhsProdiDikti` AS KODE_PRODI,
                    CONCAT(b.`prodiJenjang`,' - ',b.`prodiNama`)AS NAMA_PRODI,
                    a.`mhsJenkel` AS LP,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    WHERE a.`mhsStatus` IN('A') AND b.`prodiFakId`=:fak
                    GROUP BY a.`mhsProdiDikti`,a.`mhsJenkel`
                    ORDER BY NAMA_PRODI ASC";
                $rsItem = $conn->QueryAll($qItem, [
                    ':fak' => $model->fakId
                ]);
                $data = [];
                foreach ($rsGroup as $val) {
                    $dataKategori[] = $val['NAMA_PRODI'];
                }
                foreach ($rsItem as $valItem) {
                    if ($valItem['LP'] == 'L') {
                        $data['L'][] = [$valItem['NAMA_PRODI'], (int) $valItem['JML']];
                    }
                    if ($valItem['LP'] == 'P') {
                        $data['P'][] = [$valItem['NAMA_PRODI'], (int) $valItem['JML']];
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
                    a.`mhsAngkatan` AS AKT,
                    CONCAT(b.`prodiJenjang`,' - ',b.`prodiNama`)AS NAMA_PRODI,
                    a.`mhsJenkel` AS LP,
                    COUNT(*)AS JML
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    WHERE a.`mhsStatus` IN('A') AND b.`prodiFakId`=:fak 
                    AND a.`mhsAngkatan`=:akt
                    GROUP BY a.`mhsProdiDikti`,b.`prodiJenjang`,a.`mhsJenkel`
                    ORDER BY b.`prodiKode` DESC";
                $result = $conn->QueryAll($q, [
                    ':fak' => $model->fakId,
                    ':akt' => $model->thnAkt
                ]);
                $data = [];
                foreach ($result as $val) {
                    if ($val['LP'] == 'L') {
                        $data['L'][] = [$val['NAMA_PRODI'], (int) $val['JML']];
                    }
                    if ($val['LP'] == 'P') {
                        $data['P'][] = [$val['NAMA_PRODI'], (int) $val['JML']];
                        $dataKategori[] = $val['NAMA_PRODI'];
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

        return $this->render('perProdi', [
                    'model' => $model,
                    'dataKategori' => $dataKategori,
                    'dataColumn' => $dataColumn
        ]);
    }

    public function actionPerAngkatan() {
        $conn = new DAO();
        $model = new Terdaftar();
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
        $model = new Terdaftar();
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

}
