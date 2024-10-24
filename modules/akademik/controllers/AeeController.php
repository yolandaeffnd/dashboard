<?php

namespace app\modules\akademik\controllers;

use Yii;
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
class AeeController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['POST', 'GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlAee'),
                ]
            ]
        ];
    }

    public function actionIndex() {
        $conn = new DAO();
        $queryS1 = "SELECT a.mjsbCreate,a.mjsbTahun,a.mjsbFakId,f.`fakNama`,c.`prodiKode`,CONCAT(c.`prodiJenjang`,' - ',c.`prodiNama`)AS prodiNama,
            a.mjsbJumlah AS jmlSb,IF(b.`mjlJumlah` IS NULL,0,b.`mjlJumlah`) AS jmlLulus,
            ROUND(((IF(b.`mjlJumlah` IS NULL,0,b.`mjlJumlah`)/a.`mjsbJumlah`)*100),2)AS AEE
            FROM `mhs_jml_sb` a
            LEFT JOIN `mhs_jml_lulus` b ON b.`mjlTahun`=a.`mjsbTahun` AND b.`mjlProdiKode`=a.`mjsbProdiKode` AND b.`mjlFakId`=a.`mjsbFakId`
            JOIN `ref_prodi_nasional` c ON c.`prodiKode`=a.mjsbProdiKode
            JOIN `ref_fakultas` f ON f.`fakId`=c.`prodiFakId`
            WHERE c.`prodiJenjang`='S1' AND a.`mjsbTahun`='2018'
            GROUP BY a.`mjsbTahun`,a.`mjsbFakId`,a.`mjsbProdiKode`";
        $resultS1 = $conn->QueryAll($queryS1, []);
        $data['AEE_S1_TAHUN'] = [];
        $data['AEE_S1_FAKULTAS'] = [];
        $data['AEE_S1_PRODI'][] = [];
        $data['AEE_S1_NILAI'] = [];
        foreach ($resultS1 as $val) {
            if (!in_array($val['mjsbTahun'], $data['AEE_S1_TAHUN'])) {
                $data['AEE_S1_TAHUN'][] = $val['mjsbTahun'];
            }
            if (!in_array(['id' => $val['mjsbFakId'], 'nama' => $val['fakNama']], $data['AEE_S1_FAKULTAS'])) {
                $data['AEE_S1_FAKULTAS'][] = ['id' => $val['mjsbFakId'], 'nama' => $val['fakNama']];
            }
            $data['AEE_S1_PRODI'][$val['mjsbFakId']][] = ['id' => $val['prodiKode'], 'nama' => $val['prodiNama']];
            $data['AEE_S1_NILAI'][$val['mjsbFakId']][$val['prodiKode']][$val['mjsbTahun']][] = ['sb' => $val['jmlSb'], 'lulus' => $val['jmlLulus'], 'aee' => $val['AEE']];
        }
        //S2
        $queryS2 = "SELECT a.mjsbCreate,a.mjsbTahun,a.mjsbFakId,f.`fakNama`,c.`prodiKode`,CONCAT(c.`prodiJenjang`,' - ',c.`prodiNama`)AS prodiNama,
            a.mjsbJumlah AS jmlSb,IF(b.`mjlJumlah` IS NULL,0,b.`mjlJumlah`) AS jmlLulus,
            ROUND(((IF(b.`mjlJumlah` IS NULL,0,b.`mjlJumlah`)/a.`mjsbJumlah`)*100),2)AS AEE
            FROM `mhs_jml_sb` a
            LEFT JOIN `mhs_jml_lulus` b ON b.`mjlTahun`=a.`mjsbTahun` AND b.`mjlProdiKode`=a.`mjsbProdiKode` AND b.`mjlFakId`=a.`mjsbFakId`
            JOIN `ref_prodi_nasional` c ON c.`prodiKode`=a.mjsbProdiKode
            JOIN `ref_fakultas` f ON f.`fakId`=c.`prodiFakId`
            WHERE c.`prodiJenjang`='S2' AND a.`mjsbTahun`='2018'
            GROUP BY a.`mjsbTahun`,a.`mjsbFakId`,a.`mjsbProdiKode`";
        $resultS2 = $conn->QueryAll($queryS2, []);
        $data['AEE_S2_TAHUN'] = [];
        $data['AEE_S2_FAKULTAS'] = [];
        $data['AEE_S2_PRODI'][] = [];
        $data['AEE_S2_NILAI'] = [];
        foreach ($resultS2 as $val) {
            if (!in_array($val['mjsbTahun'], $data['AEE_S2_TAHUN'])) {
                $data['AEE_S2_TAHUN'][] = $val['mjsbTahun'];
            }
            if (!in_array(['id' => $val['mjsbFakId'], 'nama' => $val['fakNama']], $data['AEE_S2_FAKULTAS'])) {
                $data['AEE_S2_FAKULTAS'][] = ['id' => $val['mjsbFakId'], 'nama' => $val['fakNama']];
            }
            $data['AEE_S2_PRODI'][$val['mjsbFakId']][] = ['id' => $val['prodiKode'], 'nama' => $val['prodiNama']];
            $data['AEE_S2_NILAI'][$val['mjsbFakId']][$val['prodiKode']][$val['mjsbTahun']][] = ['sb' => $val['jmlSb'], 'lulus' => $val['jmlLulus'], 'aee' => $val['AEE']];
        }
        return $this->render('index', [
                    'data' => $data
        ]);
    }

}
