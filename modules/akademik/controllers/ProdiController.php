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
                    'index-by' => ['POST', 'GET']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'index-by'
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlAkademikProdi'),
                        [
                        'allow' => true,
                        'actions' => [
                            'index-by'
                        ],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex() {
        $conn = new DAO();
        $dataCategories = [];
        /**
         * Grafik Column
         * Prodi Per Fakultas
         */
        $qProdi = "SELECT 
                b.`fakNama`,
                COUNT(*)AS jml 
                FROM `ref_prodi_nasional` a
                JOIN `ref_fakultas` b ON b.`fakId`=a.`prodiFakId`
                LEFT JOIN (
                SELECT * FROM `fact_prodi_akreditasi` c 
                WHERE c.`akrProdiIsPakai`='1'
                GROUP BY c.`akrProdiKode`
                ORDER BY c.`akrProdiTglBerlaku` DESC
                )AS akr 
                ON akr.akrProdiKode=a.`prodiKode`
                GROUP BY b.`fakId`
                ORDER BY b.`fakId` ASC";
        $rsProdi = $conn->QueryAll($qProdi, []);

        $data = [];
        foreach ($rsProdi as $val) {
            $data['FAK'][] = [$val['fakNama'], (int) $val['jml']];
            if (!in_array($val['fakNama'], $dataCategories)) {
                $dataCategories[] = str_replace('Fakultas ', '', $val['fakNama']);
            }
        }


        /*
         * Data Tabel
         */
        $qProdiTabel = "SELECT 
                    b.fakId,
                    b.`fakNama`,
                    COUNT(*)AS jml 
                    FROM `ref_prodi_nasional` a
                    JOIN `ref_fakultas` b ON b.`fakId`=a.`prodiFakId`
                    LEFT JOIN (
                    SELECT * FROM `fact_prodi_akreditasi` c 
                    /*WHERE c.`akrProdiIsPakai`='1'*/
                    GROUP BY c.`akrProdiKode`
                    ORDER BY c.`akrProdiTglBerlaku` DESC
                    )AS akr 
                    ON akr.akrProdiKode=a.`prodiKode`
                    GROUP BY b.`fakId`
                    ORDER BY b.`fakId` ASC";
        $rsProdiTabel = $conn->QueryAll($qProdiTabel, []);
        $tmpTabel['fakultas'] = [];
        foreach ($rsProdiTabel as $valT) {
            $tmpTabel['fakultas'][] = ['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama'], 'jml' => $valT['jml']];
        }

        $dataTabel = $tmpTabel;

        $dataSeries = [
                [
                'type' => 'column',
                'name' => 'Fakultas',
                'data' => $data['FAK'],
            ],
        ];

        return $this->render('index', [
                    'dataCategories' => $dataCategories,
                    'dataSeries' => $dataSeries,
                    'dataTabel' => $dataTabel
        ]);
    }

    public function actionIndexBy($act, $params) {
        $conn = new DAO();
        if ($act == 'by-akreditasi') {
            /**
             * Grafik Column
             * Prodi Berdasarkan Akreditasi
             */
            $dataCategories = [];
            $qProdi = "SELECT 
                    b.fakId,
                    b.`fakNama`,
                    IFNULL((akr.akrProdiAkreditasi),'N') AS akrProdiAkreditasi,
                    COUNT(*)AS jml 
                    FROM `ref_prodi_nasional` a
                    JOIN `ref_fakultas` b ON b.`fakId`=a.`prodiFakId`
                    LEFT JOIN (
                    SELECT * FROM `fact_prodi_akreditasi` c 
                    /*WHERE c.`akrProdiIsPakai`='1'*/
                    GROUP BY c.`akrProdiKode`
                    ORDER BY c.`akrProdiTglBerlaku` DESC
                    )AS akr 
                    ON akr.akrProdiKode=a.`prodiKode`
                    GROUP BY b.`fakId`,akr.akrProdiAkreditasi
                    ORDER BY b.`fakId` ASC";
            $rsProdi = $conn->QueryAll($qProdi, []);

            $data = [];
            $tmpData['fakultas'] = [];
            $tmpData['item'][] = [];
            foreach ($rsProdi as $val) {
                if (!in_array(['id' => $val['fakId'], 'nama' => $val['fakNama']], $tmpData['fakultas'])) {
                    $tmpData['fakultas'][] = ['id' => $val['fakId'], 'nama' => $val['fakNama']];
                }
                if ($val['akrProdiAkreditasi'] == 'A') {
                    $tmpData['item']['A'][$val['fakId']] = $val['jml'];
                }
                if ($val['akrProdiAkreditasi'] == 'B') {
                    $tmpData['item']['B'][$val['fakId']] = $val['jml'];
                }
                if ($val['akrProdiAkreditasi'] == 'C') {
                    $tmpData['item']['C'][$val['fakId']] = $val['jml'];
                }
                if ($val['akrProdiAkreditasi'] == 'N') {
                    $tmpData['item']['N'][$val['fakId']] = $val['jml'];
                }
                if (!in_array(str_replace('Fakultas ', '', $val['fakNama']), $dataCategories)) {
                    $dataCategories[] = str_replace('Fakultas ', '', $val['fakNama']);
                }
            }
            for ($j = 0; $j < count($tmpData['fakultas']); $j++) {
                $id = $tmpData['fakultas'][$j]['id'];
                $data['A'][] = [$tmpData['fakultas'][$j]['nama'], (int) (isset($tmpData['item']['A'][$id]) ? $tmpData['item']['A'][$id] : 0)];
                $data['B'][] = [$tmpData['fakultas'][$j]['nama'], (int) (isset($tmpData['item']['B'][$id]) ? $tmpData['item']['B'][$id] : 0)];
                $data['C'][] = [$tmpData['fakultas'][$j]['nama'], (int) (isset($tmpData['item']['C'][$id]) ? $tmpData['item']['C'][$id] : 0)];
                $data['N'][] = [$tmpData['fakultas'][$j]['nama'], (int) (isset($tmpData['item']['N'][$id]) ? $tmpData['item']['N'][$id] : 0)];
            }


            /*
             * Data Tabel
             */
            $qProdiTabel = "SELECT 
                    b.fakId,
                    b.`fakNama`,
                    IFNULL((akr.akrProdiAkreditasi),'N') AS akrProdiAkreditasi,
                    COUNT(*)AS jml 
                    FROM `ref_prodi_nasional` a
                    JOIN `ref_fakultas` b ON b.`fakId`=a.`prodiFakId`
                    LEFT JOIN (
                    SELECT * FROM `fact_prodi_akreditasi` c 
                    /*WHERE c.`akrProdiIsPakai`='1'*/
                    GROUP BY c.`akrProdiKode`
                    ORDER BY c.`akrProdiTglBerlaku` DESC
                    )AS akr 
                    ON akr.akrProdiKode=a.`prodiKode`
                    GROUP BY b.`fakId`,akr.akrProdiAkreditasi
                    ORDER BY b.`fakId` ASC";
            $rsProdiTabel = $conn->QueryAll($qProdiTabel, []);
            $tmpTabel['fakultas'] = [];
            $tmpTabel['item'] = [];
            foreach ($rsProdiTabel as $valT) {
                if ($valT['akrProdiAkreditasi'] == 'A') {
                    $tmpTabel['item']['A'][$valT['fakId']] = $valT['jml'];
                }
                if ($valT['akrProdiAkreditasi'] == 'B') {
                    $tmpTabel['item']['B'][$valT['fakId']] = $valT['jml'];
                }
                if ($valT['akrProdiAkreditasi'] == 'C') {
                    $tmpTabel['item']['C'][$valT['fakId']] = $valT['jml'];
                }
                if ($valT['akrProdiAkreditasi'] == 'N') {
                    $tmpTabel['item']['N'][$valT['fakId']] = $valT['jml'];
                }
                if (!in_array(['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']], $tmpTabel['fakultas'])) {
                    $tmpTabel['fakultas'][] = ['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']];
                }
            }


            $dataTabel = $tmpTabel;

            $dataSeries = [
                    [
                    'type' => 'column',
                    'name' => 'Akreditasi A',
                    'data' => $data['A'], 
                ],
                    [
                    'type' => 'column',
                    'name' => 'Akreditasi B',
                    'data' => $data['B'], 
                ],
                    [
                    'type' => 'column',
                    'name' => 'Akreditasi C',
                    'data' => $data['C'], 
                ],
                    [
                    'type' => 'column',
                    'name' => 'Terakreditasi',
                    'data' => $data['N'], 
                ]
            ];

            $html = $this->renderAjax('_prodiByAkreditasi', [
                'dataCategories' => $dataCategories,
                'dataSeries' => $dataSeries,
                'dataTabel' => $dataTabel
            ]);
            return Json::encode($html);
        } else if ($act == 'by-masa-berlaku') {
            /*
             * Data Tabel
             */
            $qProdiTabel = "SELECT 
                    b.fakId,
                    b.`fakNama`,
                    a.`prodiKode`,
                    CONCAT(a.`prodiJenjang`,' - ',a.`prodiNama`)AS prodiNama,
                    akr.akrProdiAkreditasi,
                    akr.akrProdiNomorSK,
                    akr.akrProdiTglAkreditasi,
                    akr.akrProdiTglBerlaku,
                    IF(DATE(NOW())<=akr.akrProdiTglBerlaku,'Berlaku','Kadaluarsa')AS ket
                    FROM `ref_prodi_nasional` a
                    JOIN `ref_fakultas` b ON b.`fakId`=a.`prodiFakId`
                    LEFT JOIN (
                    SELECT * FROM `fact_prodi_akreditasi` c 
                    /*WHERE c.`akrProdiIsPakai`='1'*/
                    GROUP BY c.`akrProdiKode`
                    ORDER BY c.`akrProdiTglBerlaku` DESC
                    )AS akr 
                    ON akr.akrProdiKode=a.`prodiKode`
                    GROUP BY a.`prodiKode`
                    ORDER BY b.`fakId` ASC";
            $rsProdiTabel = $conn->QueryAll($qProdiTabel, []);
            $tmpTabel['fakultas'] = [];
            $tmpTabel['prodi'] = [];
            foreach ($rsProdiTabel as $valT) {
                if (!in_array(['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']], $tmpTabel['fakultas'])) {
                    $tmpTabel['fakultas'][] = ['fakId' => $valT['fakId'], 'fakNama' => $valT['fakNama']];
                }
                $tmpTabel['prodi'][$valT['fakId']][] = [
                    'prodiKode' => $valT['prodiKode'],
                    'prodiNama' => $valT['prodiNama'],
                    'prodiAkreditasi' => $valT['akrProdiAkreditasi'],
                    'prodiAkreditasiSK' => $valT['akrProdiNomorSK'],
                    'prodiAkreditasiTgl' => $valT['akrProdiTglAkreditasi'],
                    'prodiAkreditasiBerlaku' => $valT['akrProdiTglBerlaku'],
                    'prodiAkreditasiStatus' => $valT['ket'],
                ];
            }

            //print_r($tmpTabel['prodi']);

            $dataTabel = $tmpTabel;

            $html = $this->renderAjax('_prodiByMasaBerlaku', [
                'dataTabel' => $dataTabel
            ]);
            return Json::encode($html);
        }
    }

}
