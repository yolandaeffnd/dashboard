<?php

namespace app\modules\akademik\controllers;

use Yii;
use app\modules\akademik\models\Lulusan;
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
class LulusanController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'unand' => ['POST', 'GET'],
                    'per-tahun' => ['POST', 'GET'],
                    'rata-lama-studi' => ['POST', 'GET'],
                    'unand-by' => ['POST', 'GET']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'unand',
                    'per-tahun',
                    'rata-lama-studi',
                    'unand-by'
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlAkademikLulusan'),
                        [
                        'allow' => true,
                        'actions' => [
                            'unand-by'
                        ],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionUnandBy($act, $params = []) {
        $conn = new DAO();
        if ($act == 'by-tahun-masuk') {
            /**
             * Grafik Column
             * Lulusan Berdasarkan Tahun Lulus
             */
            $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan<=YEAR(NOW())-7 ORDER BY angkatan DESC LIMIT 1";
            $rsAkt = $conn->QueryRow($qAkt, []);
            $dataCategories = [];
            $qLulusan = "SELECT 
                        a.`mhsAngkatan`,
                        a.`mhsJenkel`,
                        COUNT(*)AS jml 
                        FROM `dim_mahasiswa` a
                        JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                        WHERE a.`mhsStatus` IN('L') 
                        AND a.`mhsTglTerdaftar` IS NOT NULL AND a.`mhsJenkel`<>'' 
                        AND a.`mhsTglLulus` IS NOT NULL AND YEAR(a.`mhsTglLulus`)>YEAR(NOW())-7
                        GROUP BY a.mhsAngkatan,mhsJenkel
                        ORDER BY a.mhsAngkatan ASC";
            $rsLulusan = $conn->QueryAll($qLulusan, []);

            foreach ($rsLulusan as $val) {
                if ($val['mhsAngkatan'] <= $rsAkt['angkatan']) {
                    if (!in_array('<=' . $rsAkt['angkatan'], $dataCategories)) {
                        $dataCategories[] = '<=' . $rsAkt['angkatan'];
                    }
                } else {
                    if (!in_array($val['mhsAngkatan'], $dataCategories)) {
                        $dataCategories[] = $val['mhsAngkatan'];
                    }
                }
            }
            $data = [];
            $jLKecil = 0;
            $jPKecil = 0;
            foreach ($rsLulusan as $val) {
                if ($val['mhsAngkatan'] <= $rsAkt['angkatan']) {
                    if ($val['mhsJenkel'] == 'L') {
                        $jLKecil = $jLKecil + (int) $val['jml'];
                    }
                    if ($val['mhsJenkel'] == 'P') {
                        $jPKecil = $jPKecil + (int) $val['jml'];
                    }
                }
            }
            $data['L'][] = [$rsAkt['angkatan'] . ' Kebawah', (int) $jLKecil];
            $data['P'][] = [$rsAkt['angkatan'] . ' Kebawah', (int) $jPKecil];
            foreach ($rsLulusan as $val) {
                if ($val['mhsAngkatan'] > $rsAkt['angkatan']) {
                    if ($val['mhsJenkel'] == 'L') {
                        $data['L'][] = [$val['mhsAngkatan'], (int) $val['jml']];
                    }
                    if ($val['mhsJenkel'] == 'P') {
                        $data['P'][] = [$val['mhsAngkatan'], (int) $val['jml']];
                    }
                }
            }

            $tot = [];
            for ($i = 0; $i < count($dataCategories); $i++) {
                $jL = isset($data['L'][$i][1]) ? $data['L'][$i][1] : 0;
                $jP = isset($data['P'][$i][1]) ? $data['P'][$i][1] : 0;
                $tot[] = $jL + $jP;
            }

            /*
             * Data Tabel
             */
            $qLulusanTabel = "SELECT 
                    c.`fakId`,
                    c.`fakNama`,
                    a.`mhsAngkatan`,
                    a.`mhsJenkel`,
                    COUNT(*)AS jml 
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    JOIN `ref_fakultas` c ON c.`fakId`=b.`prodiFakId`
                    WHERE a.`mhsStatus` IN('L') 
                    AND a.`mhsTglTerdaftar` IS NOT NULL AND a.`mhsJenkel`<>'' 
                    AND a.`mhsTglLulus` IS NOT NULL AND YEAR(a.`mhsTglLulus`)>YEAR(NOW())-7
                    GROUP BY a.`mhsAngkatan`,b.`prodiFakId`,mhsJenkel
                    ORDER BY a.`mhsAngkatan` ASC,b.`prodiFakId` ASC";
            $rsLulusanTabel = $conn->QueryAll($qLulusanTabel, []);
            $tmpTabel['thn'] = [];
            $tmpTabel['_thn'] = [];
            $tmpTabel['dimValue'][][][] = [];
            $tmpTabel['_dimValue'][][][] = [];
            $tmpTabel['fakultas'] = [];
            $tmpTabel['subTotal'][]=[];
            foreach ($rsLulusanTabel as $valT) {
                if ($valT['mhsAngkatan'] <= $rsAkt['angkatan']) {
                    if (!in_array('<=' . $rsAkt['angkatan'], $tmpTabel['thn'])) {
                        $tmpTabel['thn'][] = '<=' . $rsAkt['angkatan'];
                    }
                    if (!in_array($valT['mhsAngkatan'], $tmpTabel['_thn'])) {
                        $tmpTabel['_thn'][] = $valT['mhsAngkatan'];
                    }
                    $tmpTabel['_dimValue'][$valT['mhsAngkatan']][$valT['mhsJenkel']][$valT['fakId']] = $valT['jml'];
                } else {
                    if (!in_array($valT['mhsAngkatan'], $tmpTabel['thn'])) {
                        $tmpTabel['thn'][] = $valT['mhsAngkatan'];
                    }
                    $tmpTabel['dimValue'][$valT['mhsAngkatan']][$valT['mhsJenkel']][$valT['fakId']] = $valT['jml'];
                }
                if (!in_array(['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']], $tmpTabel['fakultas'])) {
                    $tmpTabel['fakultas'][] = ['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']];
                }
            }
            $jtL['kecilDari'] = [];
            $jtP['kecilDari'] = [];
            for ($j = 0; $j < count($tmpTabel['_thn']); $j++) {
                $thn = $tmpTabel['_thn'][$j];
                for ($i = 0; $i < count($tmpTabel['fakultas']); $i++) {
                    $id = $tmpTabel['fakultas'][$i]['fakId'];
                    $jtL['kecilDari'][$id] = (isset($jtL['kecilDari'][$id]) ? $jtL['kecilDari'][$id] : 0) + (isset($tmpTabel['_dimValue'][$thn]['L'][$id]) ? $tmpTabel['_dimValue'][$thn]['L'][$id] : 0);
                    $jtP['kecilDari'][$id] = (isset($jtP['kecilDari'][$id]) ? $jtP['kecilDari'][$id] : 0) + (isset($tmpTabel['_dimValue'][$thn]['P'][$id]) ? $tmpTabel['_dimValue'][$thn]['P'][$id] : 0);
                }
            }
            for ($i = 0; $i < count($tmpTabel['fakultas']); $i++) {
                $id = $tmpTabel['fakultas'][$i]['fakId'];
                $tmpTabel['dimValue']['<='.$rsAkt['angkatan']]['L'][$id] = isset($jtL['kecilDari'][$id])?$jtL['kecilDari'][$id]:0;
                $tmpTabel['dimValue']['<='.$rsAkt['angkatan']]['P'][$id] = isset($jtP['kecilDari'][$id])?$jtP['kecilDari'][$id]:0;
            }
            for ($j = 0; $j < count($tmpTabel['thn']); $j++) {
                $tmpTabel['subTotal'][$tmpTabel['thn'][$j]] = $tot[$j];
            }
            
            $dataTabel = $tmpTabel;

            //echo $jtL;
            //print_r($tmpTabel['subTotal']);

            $dataSeries = [
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

            $html = $this->renderAjax('_unandByAngkatan', [
                'dataCategories' => $dataCategories,
                'dataSeries' => $dataSeries,
                'dataTabel' => $dataTabel
            ]);
            return Json::encode($html);
        }
    }

    public function actionUnand() {
        $conn = new DAO();
        $dataCategories = [];
        /**
         * Grafik Column
         * Lulusan Per Tahun
         */
        $qLulusan = "SELECT 
                        YEAR(a.`mhsTglLulus`)AS thnLulus,
                        a.`mhsJenkel`,
                        COUNT(*)AS jml 
                        FROM `dim_mahasiswa` a
                        JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                        WHERE a.`mhsStatus` IN('L') 
                        AND a.`mhsTglTerdaftar` IS NOT NULL AND a.`mhsJenkel`<>'' 
                        AND a.`mhsTglLulus` IS NOT NULL AND YEAR(a.`mhsTglLulus`)>YEAR(NOW())-7
                        GROUP BY thnLulus,mhsJenkel
                        ORDER BY thnLulus ASC";
        $rsLulusan = $conn->QueryAll($qLulusan, []);

        $data = [];
        foreach ($rsLulusan as $val) {
            if ($val['mhsJenkel'] == 'L') {
                $data['L'][] = [$val['thnLulus'], (int) $val['jml']];
            }
            if ($val['mhsJenkel'] == 'P') {
                $data['P'][] = [$val['thnLulus'], (int) $val['jml']];
            }
            if (!in_array($val['thnLulus'], $dataCategories)) {
                $dataCategories[] = $val['thnLulus'];
            }
        }
        $tot = [];
        for ($i = 0; $i < count($dataCategories); $i++) {
            $jL = isset($data['L'][$i][1]) ? $data['L'][$i][1] : 0;
            $jP = isset($data['P'][$i][1]) ? $data['P'][$i][1] : 0;
            $tot[] = $jL + $jP;
        }

        /*
         * Data Tabel
         */
        $qLulusanTabel = "SELECT 
                    c.`fakId`,
                    c.`fakNama`,
                    YEAR(a.`mhsTglLulus`)AS thnLulus,
                    a.`mhsJenkel`,
                    COUNT(*)AS jml 
                    FROM `dim_mahasiswa` a
                    JOIN `ref_prodi_nasional` b ON b.`prodiKode`=a.`mhsProdiDikti`
                    JOIN `ref_fakultas` c ON c.`fakId`=b.`prodiFakId`
                    WHERE a.`mhsStatus` IN('L') 
                    AND a.`mhsTglTerdaftar` IS NOT NULL AND a.`mhsJenkel`<>'' 
                    AND a.`mhsTglLulus` IS NOT NULL AND YEAR(a.`mhsTglLulus`)>YEAR(NOW())-7
                    GROUP BY thnLulus,b.`prodiFakId`,mhsJenkel
                    ORDER BY thnLulus ASC,b.`prodiFakId` ASC";
        $rsLulusanTabel = $conn->QueryAll($qLulusanTabel, []);
        $tmpTabel['thn'] = [];
        $tmpTabel['dimValue'][][][] = [];
        $tmpTabel['fakultas'] = [];
        foreach ($rsLulusanTabel as $valT) {
            if (!in_array($valT['thnLulus'], $tmpTabel['thn'])) {
                $tmpTabel['thn'][] = $valT['thnLulus'];
            }
            if (!in_array(['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']], $tmpTabel['fakultas'])) {
                $tmpTabel['fakultas'][] = ['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']];
            }
            $tmpTabel['dimValue'][$valT['thnLulus']][$valT['mhsJenkel']][$valT['fakId']] = $valT['jml'];
        }

        $dataTabel = $tmpTabel;

        $dataSeries = [
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

        return $this->render('unand', [
                    'dataCategories' => $dataCategories,
                    'dataSeries' => $dataSeries,
                    'dataTabel' => $dataTabel
        ]);
    }

    public function actionPerTahun() {
        $conn = new DAO();
        $model = new Lulusan();
        //Ref Tahun Lulus
        $qRefThnLulus = "SELECT 
                    YEAR(a.`mhsTglLulus`)AS thnLulus
                    FROM `dim_mahasiswa` a
                    WHERE a.`mhsStatus` IN('L') 
                    AND a.`mhsTglTerdaftar` IS NOT NULL AND a.`mhsTglLulus` IS NOT NULL AND YEAR(a.`mhsTglLulus`)>=YEAR(NOW())-7
                    GROUP BY thnLulus
                    ORDER BY thnLulus DESC";
        $refThnLulus = $conn->QueryAll($qRefThnLulus, []);
        //Program Studi
        $qRefProdi = "SELECT * FROM ref_prodi_nasional";
        $refProdi = $conn->QueryAll($qRefProdi, []);

        $dataCategories = [];
        $dataSeries = [];
        $dataTabel = [];
        if ($model->load(Yii::$app->request->post())) {
            $qData = "SELECT 
                    CONCAT(b.`prodiJenjang`,' - ',b.`prodiNama`)AS namaProdi,
                    a.`peminatPil1`,a.`peminatPil2`,a.`peminatPil3`,
                    SUM(a.`peminatPil1`+a.`peminatPil2`+a.`peminatPil3`)as jml
                    FROM `peminat_diploma` a
                    JOIN `ref_prodi_nasional` b on b.`prodiKode`=a.`peminatProdiKode`
                    WHERE a.`peminatTahun`=:tahun
                    GROUP BY a.`peminatProdiKode`,a.`peminatTahun`
                    ORDER BY jml DESC";
            $rsData = $conn->QueryAll($qData, [
                ':tahun' => $model->thnAkt
            ]);
            $data = [];
            foreach ($rsData as $val) {
                $data[] = [$val['namaProdi'], (int) $val['jml']];
                if (!in_array($val['namaProdi'], $dataCategories)) {
                    $dataCategories[] = $val['namaProdi'];
                }
            }

            $dataTabel = $rsData;

            $dataSeries = [
                    [
                    'type' => 'column',
                    'name' => 'Jumlah',
                    'data' => $data,
                ],
//                    [
//                    'type' => 'column',
//                    'name' => 'Perempuan',
//                    'data' => $dataCol['P'],
//                ],
//                    [
//                    'type' => 'spline',
//                    'name' => 'Total',
//                    'data' => $tot,
//                ],
            ];
        }


        return $this->render('perTahun', [
                    'model' => $model,
                    'refThnLulus' => $refThnLulus,
                    'refProdi' => $refProdi,
                    'dataCategories' => $dataCategories,
                    'dataSeries' => $dataSeries,
                    'dataTabel' => $dataTabel
        ]);
    }

}
