<?php

namespace app\modules\kepegawaian\controllers;

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
class PegawaiController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'jml-pegawai' => ['POST', 'GET'],
                    'jml-pegawai-jenis' => ['POST', 'GET'],
                    'jml-pegawai-kategori' => ['POST', 'GET'],
                    'jml-pegawai-pendidikan' => ['POST', 'GET'],
                    'jml-tendik' => ['POST', 'GET'],
                    'jml-tendik-jenis-fungsional' => ['POST', 'GET'],
                    'jml-tendik-fungsional' => ['POST', 'GET'],
                    'jml-tendik-golongan' => ['POST', 'GET'],
                    'jml-tendik-pendidikan' => ['POST', 'GET'],
                    'jml-dosen' => ['POST', 'GET'],
                    'jml-dosen-fungsional' => ['POST', 'GET'],
                    'jml-dosen-golongan' => ['POST', 'GET'],
                    'jml-dosen-pendidikan' => ['POST', 'GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'jml-pegawai',
                    'jml-pegawai-jenis',
                    'jml-pegawai-kategori',
                    'jml-pegawai-pendidikan',
                    'jml-tendik',
                    'jml-tendik-jenis-fungsional',
                    'jml-tendik-fungsional',
                    'jml-tendik-golongan',
                    'jml-tendik-pendidikan',
                    'jml-dosen',
                    'jml-dosen-fungsional',
                    'jml-dosen-golongan',
                    'jml-dosen-pendidikan'
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlKepegawaian'),
                ]
            ]
        ];
    }

    public function actionJmlDosen() {
        $conn = new DAO();
        $data['BY_FUNGSIONAL']['kategori'] = [];
        $data['BY_FUNGSIONAL']['kolom'] = [];
        $data['BY_FUNGSIONAL_NON']['kategori'] = [];
        $data['BY_FUNGSIONAL_NON']['kolom'] = [];
        $data['BY_GOL']['kategori'] = [];
        $data['BY_GOL']['kolom'] = [];
        $data['BY_GOL_NON']['kategori'] = [];
        $data['BY_GOL_NON']['kolom'] = [];
        $data['BY_PDDK']['kategori'] = [];
        $data['BY_PDDK']['kolom'] = [];
        $data['BY_PDDK_NON']['kategori'] = [];
        $data['BY_PDDK_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Fungsional
        //PNS
        $qFung = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`='7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prfId`";
        $rsFung = $conn->QueryAll($qFung, []);
        $dt2 = [];
        $dt2a = [];
        foreach ($rsFung as $valFung) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFung = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlFung = $conn->QueryRow($qJmlFung, [
                    ':id' => $valFung['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2[$JK[$i]][] = [$valFung['FUNG'], (int) $rsJmlFung['JML']];
            }
            if (!in_array($valFung['FUNG'], $dt2a)) {
                $dt2a[] = $valFung['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2a); $a++) {
            $data['BY_FUNGSIONAL']['kategori'][] = $dt2a[$a];
        }
        if (empty($dt2)) {
            $data['BY_FUNGSIONAL']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2['P'],
                ],
            ];
        }

        //Berdasarkan Fungsional
        //Non PNS
        $qFungNon = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`='7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prfId`";
        $rsFungNon = $conn->QueryAll($qFungNon, []);
        $dt2Non = [];
        $dt2aNon = [];
        foreach ($rsFungNon as $valFungNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFungNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlFungNon = $conn->QueryRow($qJmlFungNon, [
                    ':id' => $valFungNon['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2Non[$JK[$i]][] = [$valFungNon['FUNG'], (int) $rsJmlFungNon['JML']];
            }
            if (!in_array($valFungNon['FUNG'], $dt2aNon)) {
                $dt2aNon[] = $valFungNon['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2aNon); $a++) {
            $data['BY_FUNGSIONAL_NON']['kategori'][] = $dt2aNon[$a];
        }
        if (empty($dt2Non)) {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2Non['P'],
                ],
            ];
        }

        //Berdasarkan Golongan
        //PNS
        $qGol = "SELECT a.`prgId` AS GOL,a.`prgNama`
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prgId`";
        $rsGol = $conn->QueryAll($qGol, []);
        $dt3 = [];
        $dt3a = [];
        foreach ($rsGol as $valGol) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGol = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlGol = $conn->QueryRow($qJmlGol, [
                    ':id' => $valGol['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3[$JK[$i]][] = [$valGol['GOL'], (int) $rsJmlGol['JML']];
            }
            if (!in_array($valGol['GOL'], $dt3a)) {
                $dt3a[] = $valGol['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3a); $a++) {
            $data['BY_GOL']['kategori'][] = $dt3a[$a];
        }
        if (empty($dt3)) {
            $data['BY_GOL']['kolom'] = [];
        } else {
            $data['BY_GOL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3['P'],
                ],
            ];
        }

        //Berdasarkan Golongan
        //Non PNS
        $qGolNon = "SELECT a.`prgId` AS GOL,a.`prgNama` AS GOL_NM
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prgId`";
        $rsGolNon = $conn->QueryAll($qGolNon, []);
        $dt3Non = [];
        $dt3aNon = [];
        foreach ($rsGolNon as $valGolNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGolNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlGolNon = $conn->QueryRow($qJmlGolNon, [
                    ':id' => $valGolNon['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3Non[$JK[$i]][] = [($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], (int) $rsJmlGolNon['JML']];
            }
            if (!in_array(($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], $dt3aNon)) {
                $dt3aNon[] = ($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3aNon); $a++) {
            $data['BY_GOL_NON']['kategori'][] = $dt3aNon[$a];
        }
        if (empty($dt3Non)) {
            $data['BY_GOL_NON']['kolom'] = [];
        } else {
            $data['BY_GOL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3Non['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan
        //PNS
        $qPdd = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`pddkId`";
        $rsPdd = $conn->QueryAll($qPdd, []);
        $dt4 = [];
        $dt4a = [];
        foreach ($rsPdd as $valPdd) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPdd = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlPdd = $conn->QueryRow($qJmlPdd, [
                    ':id' => $valPdd['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4[$JK[$i]][] = [$valPdd['PDDK_NAMA'], (int) $rsJmlPdd['JML']];
            }
            if (!in_array($valPdd['PDDK_NAMA'], $dt4a)) {
                $dt4a[] = \yii\helpers\Html::a($valPdd['PDDK_NAMA'], '#');
            }
        }
        for ($a = 0; $a < count($dt4a); $a++) {
            $data['BY_PDDK']['kategori'][] = $dt4a[$a];
        }
        if (empty($dt4)) {
            $data['BY_PDDK']['kolom'] = [];
        } else {
            $data['BY_PDDK']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan
        //Non PNS
        $qPddNon = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`pddkId`";
        $rsPddNon = $conn->QueryAll($qPddNon, []);
        $dt4Non = [];
        $dt4aNon = [];
        foreach ($rsPddNon as $valPddNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPddNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlPddNon = $conn->QueryRow($qJmlPddNon, [
                    ':id' => $valPddNon['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4Non[$JK[$i]][] = [$valPddNon['PDDK_NAMA'], (int) $rsJmlPddNon['JML']];
            }
            if (!in_array($valPddNon['PDDK_NAMA'], $dt4aNon)) {
                $dt4aNon[] = \yii\helpers\Html::a($valPddNon['PDDK_NAMA'], '#');
            }
        }
        for ($a = 0; $a < count($dt4aNon); $a++) {
            $data['BY_PDDK_NON']['kategori'][] = $dt4aNon[$a];
        }
        if (empty($dt4Non)) {
            $data['BY_PDDK_NON']['kolom'] = [];
        } else {
            $data['BY_PDDK_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4Non['P'],
                ],
            ];
        }

        return $this->render("jmlDosen", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }
    
    public function actionJmlDosenFungsional() {
        $conn = new DAO();
        $data['BY_FUNGSIONAL']['kategori'] = [];
        $data['BY_FUNGSIONAL']['kolom'] = [];
        $data['BY_FUNGSIONAL_NON']['kategori'] = [];
        $data['BY_FUNGSIONAL_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Fungsional
        //PNS
        $qFung = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`='7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prfId`";
        $rsFung = $conn->QueryAll($qFung, []);
        $dt2 = [];
        $dt2a = [];
        foreach ($rsFung as $valFung) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFung = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlFung = $conn->QueryRow($qJmlFung, [
                    ':id' => $valFung['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2[$JK[$i]][] = [$valFung['FUNG'], (int) $rsJmlFung['JML']];
            }
            if (!in_array($valFung['FUNG'], $dt2a)) {
                $dt2a[] = $valFung['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2a); $a++) {
            $data['BY_FUNGSIONAL']['kategori'][] = $dt2a[$a];
        }
        if (empty($dt2)) {
            $data['BY_FUNGSIONAL']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2['P'],
                ],
            ];
        }

        //Berdasarkan Fungsional
        //Non PNS
        $qFungNon = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`='7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prfId`";
        $rsFungNon = $conn->QueryAll($qFungNon, []);
        $dt2Non = [];
        $dt2aNon = [];
        foreach ($rsFungNon as $valFungNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFungNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlFungNon = $conn->QueryRow($qJmlFungNon, [
                    ':id' => $valFungNon['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2Non[$JK[$i]][] = [$valFungNon['FUNG'], (int) $rsJmlFungNon['JML']];
            }
            if (!in_array($valFungNon['FUNG'], $dt2aNon)) {
                $dt2aNon[] = $valFungNon['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2aNon); $a++) {
            $data['BY_FUNGSIONAL_NON']['kategori'][] = $dt2aNon[$a];
        }
        if (empty($dt2Non)) {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2Non['P'],
                ],
            ];
        }

        return $this->render("jmlDosenFungsional", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }
    
    public function actionJmlDosenGolongan() {
        $conn = new DAO();
        $data['BY_GOL']['kategori'] = [];
        $data['BY_GOL']['kolom'] = [];
        $data['BY_GOL_NON']['kategori'] = [];
        $data['BY_GOL_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Golongan
        //PNS
        $qGol = "SELECT a.`prgId` AS GOL,a.`prgNama`
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prgId`";
        $rsGol = $conn->QueryAll($qGol, []);
        $dt3 = [];
        $dt3a = [];
        foreach ($rsGol as $valGol) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGol = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlGol = $conn->QueryRow($qJmlGol, [
                    ':id' => $valGol['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3[$JK[$i]][] = [$valGol['GOL'], (int) $rsJmlGol['JML']];
            }
            if (!in_array($valGol['GOL'], $dt3a)) {
                $dt3a[] = $valGol['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3a); $a++) {
            $data['BY_GOL']['kategori'][] = $dt3a[$a];
        }
        if (empty($dt3)) {
            $data['BY_GOL']['kolom'] = [];
        } else {
            $data['BY_GOL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3['P'],
                ],
            ];
        }

        //Berdasarkan Golongan
        //Non PNS
        $qGolNon = "SELECT a.`prgId` AS GOL,a.`prgNama` AS GOL_NM
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prgId`";
        $rsGolNon = $conn->QueryAll($qGolNon, []);
        $dt3Non = [];
        $dt3aNon = [];
        foreach ($rsGolNon as $valGolNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGolNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlGolNon = $conn->QueryRow($qJmlGolNon, [
                    ':id' => $valGolNon['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3Non[$JK[$i]][] = [($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], (int) $rsJmlGolNon['JML']];
            }
            if (!in_array(($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], $dt3aNon)) {
                $dt3aNon[] = ($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3aNon); $a++) {
            $data['BY_GOL_NON']['kategori'][] = $dt3aNon[$a];
        }
        if (empty($dt3Non)) {
            $data['BY_GOL_NON']['kolom'] = [];
        } else {
            $data['BY_GOL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3Non['P'],
                ],
            ];
        }

        return $this->render("jmlDosenGolongan", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }
    
    public function actionJmlDosenPendidikan() {
        $conn = new DAO();
        $data['BY_PDDK']['kategori'] = [];
        $data['BY_PDDK']['kolom'] = [];
        $data['BY_PDDK_NON']['kategori'] = [];
        $data['BY_PDDK_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Pendidikan
        //PNS
        $qPdd = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`pddkId`";
        $rsPdd = $conn->QueryAll($qPdd, []);
        $dt4 = [];
        $dt4a = [];
        foreach ($rsPdd as $valPdd) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPdd = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlPdd = $conn->QueryRow($qJmlPdd, [
                    ':id' => $valPdd['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4[$JK[$i]][] = [$valPdd['PDDK_NAMA'], (int) $rsJmlPdd['JML']];
            }
            if (!in_array($valPdd['PDDK_NAMA'], $dt4a)) {
                $dt4a[] = \yii\helpers\Html::a($valPdd['PDDK_NAMA'], '#');
            }
        }
        for ($a = 0; $a < count($dt4a); $a++) {
            $data['BY_PDDK']['kategori'][] = $dt4a[$a];
        }
        if (empty($dt4)) {
            $data['BY_PDDK']['kolom'] = [];
        } else {
            $data['BY_PDDK']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan
        //Non PNS
        $qPddNon = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`='7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`pddkId`";
        $rsPddNon = $conn->QueryAll($qPddNon, []);
        $dt4Non = [];
        $dt4aNon = [];
        foreach ($rsPddNon as $valPddNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPddNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`='7'";
                $rsJmlPddNon = $conn->QueryRow($qJmlPddNon, [
                    ':id' => $valPddNon['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4Non[$JK[$i]][] = [$valPddNon['PDDK_NAMA'], (int) $rsJmlPddNon['JML']];
            }
            if (!in_array($valPddNon['PDDK_NAMA'], $dt4aNon)) {
                $dt4aNon[] = \yii\helpers\Html::a($valPddNon['PDDK_NAMA'], '#');
            }
        }
        for ($a = 0; $a < count($dt4aNon); $a++) {
            $data['BY_PDDK_NON']['kategori'][] = $dt4aNon[$a];
        }
        if (empty($dt4Non)) {
            $data['BY_PDDK_NON']['kolom'] = [];
        } else {
            $data['BY_PDDK_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4Non['P'],
                ],
            ];
        }

        return $this->render("jmlDosenPendidikan", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }

    public function actionJmlTendik() {
        $conn = new DAO();
        $data['BY_JENIS_FUNGSIONAL']['kategori'] = [];
        $data['BY_JENIS_FUNGSIONAL']['kolom'] = [];
        $data['BY_JENIS_FUNGSIONAL_NON']['kategori'] = [];
        $data['BY_JENIS_FUNGSIONAL_NON']['kolom'] = [];
        $data['BY_FUNGSIONAL']['kategori'] = [];
        $data['BY_FUNGSIONAL']['kolom'] = [];
        $data['BY_FUNGSIONAL_NON']['kategori'] = [];
        $data['BY_FUNGSIONAL_NON']['kolom'] = [];
        $data['BY_GOL']['kategori'] = [];
        $data['BY_GOL']['kolom'] = [];
        $data['BY_GOL_NON']['kategori'] = [];
        $data['BY_GOL_NON']['kolom'] = [];
        $data['BY_PDDK']['kategori'] = [];
        $data['BY_PDDK']['kolom'] = [];
        $data['BY_PDDK_NON']['kategori'] = [];
        $data['BY_PDDK_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Jenis Fungsional
        //PNS
        $qJnsFung = "SELECT a.`prjfId`,a.`prjfNama` AS JNS_FUNG
            FROM `peg_ref_jenis_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrjfId`=a.`prjfId`
            WHERE a.`prjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prjfId`";
        $rsJnsFung = $conn->QueryAll($qJnsFung, []);
        $dt1 = [];
        $dt1a = [];
        foreach ($rsJnsFung as $valJnsFung) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlJnsFung = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrjfId`=:id AND MONTH(c.`pjpCreate`)=:bln 
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlJnsFung = $conn->QueryRow($qJmlJnsFung, [
                    ':id' => $valJnsFung['prjfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                if (empty($rsJmlJnsFung)) {
                    $dt1['P'][] = [$valJnsFung['JNS_FUNG'], 0];
                    $dt1['L'][] = [$valJnsFung['JNS_FUNG'], 0];
                } else {
                    $dt1[$JK[$i]][] = [$valJnsFung['JNS_FUNG'], (int) $rsJmlJnsFung['JML']];
                }
            }
            if (!in_array($valJnsFung['JNS_FUNG'], $dt1a)) {
                $dt1a[] = $valJnsFung['JNS_FUNG'];
            }
        }
        for ($a = 0; $a < count($dt1a); $a++) {
            $data['BY_JENIS_FUNGSIONAL']['kategori'][] = $dt1a[$a];
        }
        if (empty($dt1)) {
            $data['BY_JENIS_FUNGSIONAL']['kolom'] = [];
        } else {
            $data['BY_JENIS_FUNGSIONAL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt1['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt1['P'],
                ],
            ];
        }

        //Berdasarkan Jenis Fungsional
        //Non PNS
        $qJnsFungNon = "SELECT a.`prjfId`,a.`prjfNama` AS JNS_FUNG
            FROM `peg_ref_jenis_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrjfId`=a.`prjfId`
            WHERE a.`prjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prjfId`";
        $rsJnsFungNon = $conn->QueryAll($qJnsFungNon, []);
        $dt1Non = [];
        $dt1aNon = [];
        foreach ($rsJnsFungNon as $valJnsFungNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlJnsFungNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrjfId`=:id AND MONTH(c.`pjpCreate`)=:bln 
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlJnsFungNon = $conn->QueryRow($qJmlJnsFungNon, [
                    ':id' => $valJnsFungNon['prjfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                if (empty($rsJmlJnsFungNon)) {
                    $dt1Non['P'][] = [$valJnsFungNon['JNS_FUNG'], 0];
                    $dt1Non['L'][] = [$valJnsFungNon['JNS_FUNG'], 0];
                } else {
                    $dt1Non[$JK[$i]][] = [$valJnsFungNon['JNS_FUNG'], (int) $rsJmlJnsFungNon['JML']];
                }
            }
            if (!in_array($valJnsFungNon['JNS_FUNG'], $dt1aNon)) {
                $dt1aNon[] = $valJnsFungNon['JNS_FUNG'];
            }
        }
        for ($a = 0; $a < count($dt1aNon); $a++) {
            $data['BY_JENIS_FUNGSIONAL_NON']['kategori'][] = $dt1aNon[$a];
        }
        if (empty($dt1Non)) {
            $data['BY_JENIS_FUNGSIONAL_NON']['kolom'] = [];
        } else {
            $data['BY_JENIS_FUNGSIONAL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt1Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt1Non['P'],
                ],
            ];
        }

        //Berdasarkan Fungsional
        //PNS
        $qFung = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prfId`";
        $rsFung = $conn->QueryAll($qFung, []);
        $dt2 = [];
        $dt2a = [];
        foreach ($rsFung as $valFung) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFung = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlFung = $conn->QueryRow($qJmlFung, [
                    ':id' => $valFung['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2[$JK[$i]][] = [$valFung['FUNG'], (int) $rsJmlFung['JML']];
            }
            if (!in_array($valFung['FUNG'], $dt2a)) {
                $dt2a[] = $valFung['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2a); $a++) {
            $data['BY_FUNGSIONAL']['kategori'][] = $dt2a[$a];
        }
        if (empty($dt2)) {
            $data['BY_FUNGSIONAL']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2['P'],
                ],
            ];
        }

        //Berdasarkan Fungsional
        //Non PNS
        $qFungNon = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prfId`";
        $rsFungNon = $conn->QueryAll($qFungNon, []);
        $dt2Non = [];
        $dt2aNon = [];
        foreach ($rsFungNon as $valFungNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFungNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlFungNon = $conn->QueryRow($qJmlFungNon, [
                    ':id' => $valFungNon['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2Non[$JK[$i]][] = [$valFungNon['FUNG'], (int) $rsJmlFungNon['JML']];
            }
            if (!in_array($valFungNon['FUNG'], $dt2aNon)) {
                $dt2aNon[] = $valFungNon['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2aNon); $a++) {
            $data['BY_FUNGSIONAL_NON']['kategori'][] = $dt2aNon[$a];
        }
        if (empty($dt2Non)) {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2Non['P'],
                ],
            ];
        }

        //Berdasarkan Golongan
        //PNS
        $qGol = "SELECT a.`prgId` AS GOL,a.`prgNama`
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prgId`";
        $rsGol = $conn->QueryAll($qGol, []);
        $dt3 = [];
        $dt3a = [];
        foreach ($rsGol as $valGol) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGol = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlGol = $conn->QueryRow($qJmlGol, [
                    ':id' => $valGol['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3[$JK[$i]][] = [$valGol['GOL'], (int) $rsJmlGol['JML']];
            }
            if (!in_array($valGol['GOL'], $dt3a)) {
                $dt3a[] = $valGol['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3a); $a++) {
            $data['BY_GOL']['kategori'][] = $dt3a[$a];
        }
        if (empty($dt3)) {
            $data['BY_GOL']['kolom'] = [];
        } else {
            $data['BY_GOL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3['P'],
                ],
            ];
        }

        //Berdasarkan Golongan
        //Non PNS
        $qGolNon = "SELECT a.`prgId` AS GOL,a.`prgNama` AS GOL_NM
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4') 
            GROUP BY a.`prgId`";
        $rsGolNon = $conn->QueryAll($qGolNon, []);
        $dt3Non = [];
        $dt3aNon = [];
        foreach ($rsGolNon as $valGolNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGolNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlGolNon = $conn->QueryRow($qJmlGolNon, [
                    ':id' => $valGolNon['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3Non[$JK[$i]][] = [($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], (int) $rsJmlGolNon['JML']];
            }
            if (!in_array(($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], $dt3aNon)) {
                $dt3aNon[] = ($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3aNon); $a++) {
            $data['BY_GOL_NON']['kategori'][] = $dt3aNon[$a];
        }
        if (empty($dt3Non)) {
            $data['BY_GOL_NON']['kolom'] = [];
        } else {
            $data['BY_GOL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3Non['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan
        //PNS
        $qPdd = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`pddkId`";
        $rsPdd = $conn->QueryAll($qPdd, []);
        $dt4 = [];
        $dt4a = [];
        foreach ($rsPdd as $valPdd) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPdd = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlPdd = $conn->QueryRow($qJmlPdd, [
                    ':id' => $valPdd['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4[$JK[$i]][] = [$valPdd['PDDK_NAMA'], (int) $rsJmlPdd['JML']];
            }
            if (!in_array($valPdd['PDDK_NAMA'], $dt4a)) {
                $dt4a[] = $valPdd['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt4a); $a++) {
            $data['BY_PDDK']['kategori'][] = $dt4a[$a];
        }
        if (empty($dt4)) {
            $data['BY_PDDK']['kolom'] = [];
        } else {
            $data['BY_PDDK']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan
        //Non PNS
        $qPddNon = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`pddkId`";
        $rsPddNon = $conn->QueryAll($qPddNon, []);
        $dt4Non = [];
        $dt4aNon = [];
        foreach ($rsPddNon as $valPddNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPddNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlPddNon = $conn->QueryRow($qJmlPddNon, [
                    ':id' => $valPddNon['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4Non[$JK[$i]][] = [$valPddNon['PDDK_NAMA'], (int) $rsJmlPddNon['JML']];
            }
            if (!in_array($valPddNon['PDDK_NAMA'], $dt4aNon)) {
                $dt4aNon[] = $valPddNon['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt4aNon); $a++) {
            $data['BY_PDDK_NON']['kategori'][] = $dt4aNon[$a];
        }
        if (empty($dt4Non)) {
            $data['BY_PDDK_NON']['kolom'] = [];
        } else {
            $data['BY_PDDK_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4Non['P'],
                ],
            ];
        }

        return $this->render("jmlTendik", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }

    public function actionJmlTendikJenisFungsional() {
        $conn = new DAO();
        $data['BY_JENIS_FUNGSIONAL']['kategori'] = [];
        $data['BY_JENIS_FUNGSIONAL']['kolom'] = [];
        $data['BY_JENIS_FUNGSIONAL_NON']['kategori'] = [];
        $data['BY_JENIS_FUNGSIONAL_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Jenis Fungsional
        //PNS
        $qJnsFung = "SELECT a.`prjfId`,a.`prjfNama` AS JNS_FUNG
            FROM `peg_ref_jenis_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrjfId`=a.`prjfId`
            WHERE a.`prjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prjfId`";
        $rsJnsFung = $conn->QueryAll($qJnsFung, []);
        $dt1 = [];
        $dt1a = [];
        foreach ($rsJnsFung as $valJnsFung) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlJnsFung = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrjfId`=:id AND MONTH(c.`pjpCreate`)=:bln 
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlJnsFung = $conn->QueryRow($qJmlJnsFung, [
                    ':id' => $valJnsFung['prjfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                if (empty($rsJmlJnsFung)) {
                    $dt1['P'][] = [$valJnsFung['JNS_FUNG'], 0];
                    $dt1['L'][] = [$valJnsFung['JNS_FUNG'], 0];
                } else {
                    $dt1[$JK[$i]][] = [$valJnsFung['JNS_FUNG'], (int) $rsJmlJnsFung['JML']];
                }
            }
            if (!in_array($valJnsFung['JNS_FUNG'], $dt1a)) {
                $dt1a[] = $valJnsFung['JNS_FUNG'];
            }
        }
        for ($a = 0; $a < count($dt1a); $a++) {
            $data['BY_JENIS_FUNGSIONAL']['kategori'][] = $dt1a[$a];
        }
        if (empty($dt1)) {
            $data['BY_JENIS_FUNGSIONAL']['kolom'] = [];
        } else {
            $data['BY_JENIS_FUNGSIONAL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt1['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt1['P'],
                ],
            ];
        }

        //Berdasarkan Jenis Fungsional
        //Non PNS
        $qJnsFungNon = "SELECT a.`prjfId`,a.`prjfNama` AS JNS_FUNG
            FROM `peg_ref_jenis_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrjfId`=a.`prjfId`
            WHERE a.`prjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prjfId`";
        $rsJnsFungNon = $conn->QueryAll($qJnsFungNon, []);
        $dt1Non = [];
        $dt1aNon = [];
        foreach ($rsJnsFungNon as $valJnsFungNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlJnsFungNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrjfId`=:id AND MONTH(c.`pjpCreate`)=:bln 
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlJnsFungNon = $conn->QueryRow($qJmlJnsFungNon, [
                    ':id' => $valJnsFungNon['prjfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                if (empty($rsJmlJnsFungNon)) {
                    $dt1Non['P'][] = [$valJnsFungNon['JNS_FUNG'], 0];
                    $dt1Non['L'][] = [$valJnsFungNon['JNS_FUNG'], 0];
                } else {
                    $dt1Non[$JK[$i]][] = [$valJnsFungNon['JNS_FUNG'], (int) $rsJmlJnsFungNon['JML']];
                }
            }
            if (!in_array($valJnsFungNon['JNS_FUNG'], $dt1aNon)) {
                $dt1aNon[] = $valJnsFungNon['JNS_FUNG'];
            }
        }
        for ($a = 0; $a < count($dt1aNon); $a++) {
            $data['BY_JENIS_FUNGSIONAL_NON']['kategori'][] = $dt1aNon[$a];
        }
        if (empty($dt1Non)) {
            $data['BY_JENIS_FUNGSIONAL_NON']['kolom'] = [];
        } else {
            $data['BY_JENIS_FUNGSIONAL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt1Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt1Non['P'],
                ],
            ];
        }

        return $this->render("jmlTendikJenisFungsional", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }
    
    public function actionJmlTendikFungsional() {
        $conn = new DAO();
        $data['BY_FUNGSIONAL']['kategori'] = [];
        $data['BY_FUNGSIONAL']['kolom'] = [];
        $data['BY_FUNGSIONAL_NON']['kategori'] = [];
        $data['BY_FUNGSIONAL_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Fungsional
        //PNS
        $qFung = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prfId`";
        $rsFung = $conn->QueryAll($qFung, []);
        $dt2 = [];
        $dt2a = [];
        foreach ($rsFung as $valFung) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFung = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlFung = $conn->QueryRow($qJmlFung, [
                    ':id' => $valFung['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2[$JK[$i]][] = [$valFung['FUNG'], (int) $rsJmlFung['JML']];
            }
            if (!in_array($valFung['FUNG'], $dt2a)) {
                $dt2a[] = $valFung['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2a); $a++) {
            $data['BY_FUNGSIONAL']['kategori'][] = $dt2a[$a];
        }
        if (empty($dt2)) {
            $data['BY_FUNGSIONAL']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2['P'],
                ],
            ];
        }

        //Berdasarkan Fungsional
        //Non PNS
        $qFungNon = "SELECT a.`prfId`,a.`prfNama`AS FUNG
            FROM `peg_ref_fungsional` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrfId`=a.`prfId`
            WHERE a.`prfPrjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`prfId`";
        $rsFungNon = $conn->QueryAll($qFungNon, []);
        $dt2Non = [];
        $dt2aNon = [];
        foreach ($rsFungNon as $valFungNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlFungNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrfId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlFungNon = $conn->QueryRow($qJmlFungNon, [
                    ':id' => $valFungNon['prfId'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt2Non[$JK[$i]][] = [$valFungNon['FUNG'], (int) $rsJmlFungNon['JML']];
            }
            if (!in_array($valFungNon['FUNG'], $dt2aNon)) {
                $dt2aNon[] = $valFungNon['FUNG'];
            }
        }
        for ($a = 0; $a < count($dt2aNon); $a++) {
            $data['BY_FUNGSIONAL_NON']['kategori'][] = $dt2aNon[$a];
        }
        if (empty($dt2Non)) {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [];
        } else {
            $data['BY_FUNGSIONAL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2Non['P'],
                ],
            ];
        }

        return $this->render("jmlTendikFungsional", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }
    
    public function actionJmlTendikGolongan() {
        $conn = new DAO();
        $data['BY_GOL']['kategori'] = [];
        $data['BY_GOL']['kolom'] = [];
        $data['BY_GOL_NON']['kategori'] = [];
        $data['BY_GOL_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Golongan
        //PNS
        $qGol = "SELECT a.`prgId` AS GOL,a.`prgNama`
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`prgId`";
        $rsGol = $conn->QueryAll($qGol, []);
        $dt3 = [];
        $dt3a = [];
        foreach ($rsGol as $valGol) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGol = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlGol = $conn->QueryRow($qJmlGol, [
                    ':id' => $valGol['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3[$JK[$i]][] = [$valGol['GOL'], (int) $rsJmlGol['JML']];
            }
            if (!in_array($valGol['GOL'], $dt3a)) {
                $dt3a[] = $valGol['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3a); $a++) {
            $data['BY_GOL']['kategori'][] = $dt3a[$a];
        }
        if (empty($dt3)) {
            $data['BY_GOL']['kolom'] = [];
        } else {
            $data['BY_GOL']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3['P'],
                ],
            ];
        }

        //Berdasarkan Golongan
        //Non PNS
        $qGolNon = "SELECT a.`prgId` AS GOL,a.`prgNama` AS GOL_NM
            FROM `peg_ref_golongan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPrgId`=a.`prgId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4') 
            GROUP BY a.`prgId`";
        $rsGolNon = $conn->QueryAll($qGolNon, []);
        $dt3Non = [];
        $dt3aNon = [];
        foreach ($rsGolNon as $valGolNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlGolNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPrgId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlGolNon = $conn->QueryRow($qJmlGolNon, [
                    ':id' => $valGolNon['GOL'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt3Non[$JK[$i]][] = [($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], (int) $rsJmlGolNon['JML']];
            }
            if (!in_array(($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'], $dt3aNon)) {
                $dt3aNon[] = ($valGolNon['GOL'] == 0) ? $valGolNon['GOL_NM'] : $valGolNon['GOL'];
            }
        }
        for ($a = 0; $a < count($dt3aNon); $a++) {
            $data['BY_GOL_NON']['kategori'][] = $dt3aNon[$a];
        }
        if (empty($dt3Non)) {
            $data['BY_GOL_NON']['kolom'] = [];
        } else {
            $data['BY_GOL_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt3Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt3Non['P'],
                ],
            ];
        }

        return $this->render("jmlTendikGolongan", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }
    
    public function actionJmlTendikPendidikan() {
        $conn = new DAO();
        $data['BY_PDDK']['kategori'] = [];
        $data['BY_PDDK']['kolom'] = [];
        $data['BY_PDDK_NON']['kategori'] = [];
        $data['BY_PDDK_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Pendidikan
        //PNS
        $qPdd = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('3','5')
            GROUP BY a.`pddkId`";
        $rsPdd = $conn->QueryAll($qPdd, []);
        $dt4 = [];
        $dt4a = [];
        foreach ($rsPdd as $valPdd) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPdd = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('3','5')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlPdd = $conn->QueryRow($qJmlPdd, [
                    ':id' => $valPdd['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4[$JK[$i]][] = [$valPdd['PDDK_NAMA'], (int) $rsJmlPdd['JML']];
            }
            if (!in_array($valPdd['PDDK_NAMA'], $dt4a)) {
                $dt4a[] = $valPdd['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt4a); $a++) {
            $data['BY_PDDK']['kategori'][] = $dt4a[$a];
        }
        if (empty($dt4)) {
            $data['BY_PDDK']['kolom'] = [];
        } else {
            $data['BY_PDDK']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan
        //Non PNS
        $qPddNon = "SELECT a.`pddkId` AS PDDK_ID,a.`pddkNama` AS PDDK_NAMA
            FROM `peg_ref_pendidikan` a
            JOIN `peg_jml_pegawai` b ON b.`pjpPddkId`=a.`pddkId`
            WHERE b.`pjpPrjfId`<>'7' AND b.`pjpJnsPegId` IN('1','4')
            GROUP BY a.`pddkId`";
        $rsPddNon = $conn->QueryAll($qPddNon, []);
        $dt4Non = [];
        $dt4aNon = [];
        foreach ($rsPddNon as $valPddNon) {
            $JK = ['L', 'P'];
            for ($i = 0; $i < 2; $i++) {
                $qJmlPddNon = "SELECT c.`pjpJenkel` AS JENKEL,SUM(c.`pjpJumlah`)AS JML 
                    FROM `peg_jml_pegawai` c
                    WHERE c.`pjpPddkId`=:id AND MONTH(c.`pjpCreate`)=:bln
                    AND c.`pjpJnsPegId` IN('1','4')
                    AND c.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
                    AND c.`pjpJenkel`=:jk AND c.`pjpPrjfId`<>'7'";
                $rsJmlPddNon = $conn->QueryRow($qJmlPddNon, [
                    ':id' => $valPddNon['PDDK_ID'],
                    ':jk' => $JK[$i],
                    ':bln' => $rsBln['bln']
                ]);
                $dt4Non[$JK[$i]][] = [$valPddNon['PDDK_NAMA'], (int) $rsJmlPddNon['JML']];
            }
            if (!in_array($valPddNon['PDDK_NAMA'], $dt4aNon)) {
                $dt4aNon[] = $valPddNon['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt4aNon); $a++) {
            $data['BY_PDDK_NON']['kategori'][] = $dt4aNon[$a];
        }
        if (empty($dt4Non)) {
            $data['BY_PDDK_NON']['kolom'] = [];
        } else {
            $data['BY_PDDK_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt4Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt4Non['P'],
                ],
            ];
        }

        return $this->render("jmlTendikPendidikan", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }

    public function actionJmlPegawai() {
        $conn = new DAO();
        $data['BY_KATEGORI_PEGAWAI']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI']['kolom'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [];
        $data['BY_JENIS_PEGAWAI']['kategori'] = [];
        $data['BY_JENIS_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Kategori Pegawai PNS/CPNS
        $qJmlKat = "SELECT 
	    IF(a.`pjptKatPeg`='P','Pendidik/Dosen','Kependidikan') AS KAT_PEG,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_jenis_pegawai` b ON b.`prjpId`=a.`pjptJnsPegId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('3','5')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptKatPeg`,a.`pjptJenkel`";
        $rsJmlKat = $conn->QueryAll($qJmlKat, [
            ':bln' => $rsBln['bln']
        ]);
        $dt = [];
        $dta = [];
        foreach ($rsJmlKat as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt['L'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt['P'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if (!in_array($val['KAT_PEG'], $dta)) {
                $dta[] = $val['KAT_PEG'];
            }
        }
        for ($a = 0; $a < count($dta); $a++) {
            $data['BY_KATEGORI_PEGAWAI']['kategori'][] = $dta[$a];
        }
        if (empty($dt)) {
            $data['BY_KATEGORI_PEGAWAI']['kolom'] = [];
        } else {
            $data['BY_KATEGORI_PEGAWAI']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt['P'],
                ],
            ];
        }

        //Berdasarkan Kategori Pegawai Non PNS
        $qJmlKatNon = "SELECT 
	    IF(a.`pjptKatPeg`='P','Pendidik/Dosen','Kependidikan') AS KAT_PEG,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_jenis_pegawai` b ON b.`prjpId`=a.`pjptJnsPegId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('1','4')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptKatPeg`,a.`pjptJenkel`";
        $rsJmlKatNon = $conn->QueryAll($qJmlKatNon, [
            ':bln' => $rsBln['bln']
        ]);
        $dtNon = [];
        $dtaNon = [];
        foreach ($rsJmlKatNon as $val) {
            if ($val['JENKEL'] == 'L') {
                $dtNon['L'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dtNon['P'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if (!in_array($val['KAT_PEG'], $dtaNon)) {
                $dtaNon[] = $val['KAT_PEG'];
            }
        }
        for ($a = 0; $a < count($dtaNon); $a++) {
            $data['BY_KATEGORI_PEGAWAI_NON']['kategori'][] = $dtaNon[$a];
        }
        if (empty($dtNon)) {
            $dtaNon['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [];
        } else {
            $data['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dtNon['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dtNon['P'],
                ],
            ];
        }

        //Berdasarkan Jenis Pegawai
        $qJmlJnsPeg = "SELECT 
	    b.`prjpNama` AS JNS_PEG,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_jenis_pegawai` b ON b.`prjpId`=a.`pjptJnsPegId`
            WHERE MONTH(a.`pjptCreate`)=:bln 
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptJnsPegId`,a.`pjptJenkel`
            ORDER BY b.`prjpId` ASC,a.`pjptJenkel`";
        $rsJmlJnsPeg = $conn->QueryAll($qJmlJnsPeg, [
            ':bln' => $rsBln['bln']
        ]);
        $dt1 = [];
        $dt1a = [];
        foreach ($rsJmlJnsPeg as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt1['L'][] = [$val['JNS_PEG'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt1['P'][] = [$val['JNS_PEG'], (int) $val['JML']];
            }
            if (!in_array($val['JNS_PEG'], $dt1a)) {
                $dt1a[] = $val['JNS_PEG'];
            }
        }
        for ($a = 0; $a < count($dt1a); $a++) {
            $data['BY_JENIS_PEGAWAI']['kategori'][] = $dt1a[$a];
        }
        if (empty($dt1)) {
            $data['BY_JENIS_PEGAWAI']['kolom'] = [];
        } else {
            $data['BY_JENIS_PEGAWAI']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt1['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt1['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan Pegawai PNS
        $qJmlPddk = "SELECT 
	    b.`pddkNama` AS PDDK_NAMA,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_pendidikan` b ON b.`pddkId`=a.`pjptPddkId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('3','5')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptPddkId`,a.`pjptJenkel`
            ORDER BY b.`pddkId` ASC,a.`pjptJenkel`";
        $rsJmlPddk = $conn->QueryAll($qJmlPddk, [
            ':bln' => $rsBln['bln']
        ]);
        $dt2 = [];
        $dt2a = [];
        foreach ($rsJmlPddk as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt2['L'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt2['P'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if (!in_array($val['PDDK_NAMA'], $dt2a)) {
                $dt2a[] = $val['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt2a); $a++) {
            $data['BY_PDDK_PEGAWAI']['kategori'][] = $dt2a[$a];
        }
        if (empty($dt2)) {
            $data['BY_PDDK_PEGAWAI']['kolom'] = [];
        } else {
            $data['BY_PDDK_PEGAWAI']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan Pegawai Non PNS
        $qJmlPddkNon = "SELECT 
	    b.`pddkNama` AS PDDK_NAMA,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_pendidikan` b ON b.`pddkId`=a.`pjptPddkId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('1','4')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptPddkId`,a.`pjptJenkel`
            ORDER BY b.`pddkId` ASC,a.`pjptJenkel`";
        $rsJmlPddkNon = $conn->QueryAll($qJmlPddkNon, [
            ':bln' => $rsBln['bln']
        ]);
        $dt2Non = [];
        $dt2aNon = [];
        foreach ($rsJmlPddkNon as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt2Non['L'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt2Non['P'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if (!in_array($val['PDDK_NAMA'], $dt2aNon)) {
                $dt2aNon[] = $val['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt2aNon); $a++) {
            $data['BY_PDDK_PEGAWAI_NON']['kategori'][] = $dt2aNon[$a];
        }
        if (empty($dt2Non)) {
            $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [];
        } else {
            $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2Non['P'],
                ],
            ];
        }

        return $this->render("jmlPegawai", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }

    public function actionJmlPegawaiJenis() {
        $conn = new DAO();
        $data['BY_KATEGORI_PEGAWAI']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI']['kolom'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [];
        $data['BY_JENIS_PEGAWAI']['kategori'] = [];
        $data['BY_JENIS_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Jenis Pegawai
        $qJmlJnsPeg = "SELECT 
	    b.`prjpNama` AS JNS_PEG,
            a.`pjpJenkel` AS JENKEL,
            SUM(a.`pjpJumlah`)AS JML
            FROM `peg_jml_pegawai` a
            JOIN `peg_ref_jenis_pegawai` b ON b.`prjpId`=a.`pjpJnsPegId`
            WHERE MONTH(a.`pjpCreate`)=:bln
            AND a.`pjpCreate` IN (SELECT MAX(pjpCreate) FROM `peg_jml_pegawai`)
            GROUP BY a.`pjpJnsPegId`,a.`pjpJenkel`
            ORDER BY b.`prjpId` ASC,a.`pjpJenkel`";
        $rsJmlJnsPeg = $conn->QueryAll($qJmlJnsPeg, [
            ':bln' => $rsBln['bln']
        ]);
        $dt1 = [];
        $dt1a = [];
        foreach ($rsJmlJnsPeg as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt1['L'][] = [$val['JNS_PEG'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt1['P'][] = [$val['JNS_PEG'], (int) $val['JML']];
            }
            if (!in_array($val['JNS_PEG'], $dt1a)) {
                $dt1a[] = $val['JNS_PEG'];
            }
        }
        for ($a = 0; $a < count($dt1a); $a++) {
            $data['BY_JENIS_PEGAWAI']['kategori'][] = $dt1a[$a];
        }
        if (empty($dt1)) {
            $data['BY_JENIS_PEGAWAI']['kolom'] = [];
        } else {
            $data['BY_JENIS_PEGAWAI']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt1['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt1['P'],
                ],
            ];
        }

        return $this->render("jmlPegawaiJenis", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }

    public function actionJmlPegawaiKategori() {
        $conn = new DAO();
        $data['BY_KATEGORI_PEGAWAI']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI']['kolom'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [];
        $data['BY_JENIS_PEGAWAI']['kategori'] = [];
        $data['BY_JENIS_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Kategori Pegawai PNS/CPNS
        $qJmlKat = "SELECT 
	    IF(a.`pjptKatPeg`='P','Pendidik/Dosen','Kependidikan') AS KAT_PEG,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_jenis_pegawai` b ON b.`prjpId`=a.`pjptJnsPegId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('3','5')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptKatPeg`,a.`pjptJenkel`";
        $rsJmlKat = $conn->QueryAll($qJmlKat, [
            ':bln' => $rsBln['bln']
        ]);
        $dt = [];
        $dta = [];
        foreach ($rsJmlKat as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt['L'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt['P'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if (!in_array($val['KAT_PEG'], $dta)) {
                $dta[] = $val['KAT_PEG'];
            }
        }
        for ($a = 0; $a < count($dta); $a++) {
            $data['BY_KATEGORI_PEGAWAI']['kategori'][] = $dta[$a];
        }
        if (empty($dt)) {
            $data['BY_KATEGORI_PEGAWAI']['kolom'] = [];
        } else {
            $data['BY_KATEGORI_PEGAWAI']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt['P'],
                ],
            ];
        }

        //Berdasarkan Kategori Pegawai Non PNS
        $qJmlKatNon = "SELECT 
	    IF(a.`pjptKatPeg`='P','Pendidik/Dosen','Kependidikan') AS KAT_PEG,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_jenis_pegawai` b ON b.`prjpId`=a.`pjptJnsPegId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('1','4')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptKatPeg`,a.`pjptJenkel`";
        $rsJmlKatNon = $conn->QueryAll($qJmlKatNon, [
            ':bln' => $rsBln['bln']
        ]);
        $dtNon = [];
        $dtaNon = [];
        foreach ($rsJmlKatNon as $val) {
            if ($val['JENKEL'] == 'L') {
                $dtNon['L'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dtNon['P'][] = [$val['KAT_PEG'], (int) $val['JML']];
            }
            if (!in_array($val['KAT_PEG'], $dtaNon)) {
                $dtaNon[] = $val['KAT_PEG'];
            }
        }
        for ($a = 0; $a < count($dtaNon); $a++) {
            $data['BY_KATEGORI_PEGAWAI_NON']['kategori'][] = $dtaNon[$a];
        }
        if (empty($dtNon)) {
            $dtaNon['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [];
        } else {
            $data['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dtNon['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dtNon['P'],
                ],
            ];
        }

        return $this->render("jmlPegawaiKategori", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }

    public function actionJmlPegawaiPendidikan() {
        $conn = new DAO();
        $data['BY_KATEGORI_PEGAWAI']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI']['kolom'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kategori'] = [];
        $data['BY_KATEGORI_PEGAWAI_NON']['kolom'] = [];
        $data['BY_JENIS_PEGAWAI']['kategori'] = [];
        $data['BY_JENIS_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI']['kolom'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kategori'] = [];
        $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [];

        $qBln = "SELECT MONTH(NOW()) AS bln,YEAR(NOW()) AS thn";
        $rsBln = $conn->QueryRow($qBln, []);

        //Berdasarkan Pendidikan Pegawai PNS
        $qJmlPddk = "SELECT 
	    b.`pddkNama` AS PDDK_NAMA,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_pendidikan` b ON b.`pddkId`=a.`pjptPddkId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('3','5')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptPddkId`,a.`pjptJenkel`
            ORDER BY b.`pddkId` ASC,a.`pjptJenkel`";
        $rsJmlPddk = $conn->QueryAll($qJmlPddk, [
            ':bln' => $rsBln['bln']
        ]);
        $dt2 = [];
        $dt2a = [];
        foreach ($rsJmlPddk as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt2['L'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt2['P'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if (!in_array($val['PDDK_NAMA'], $dt2a)) {
                $dt2a[] = $val['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt2a); $a++) {
            $data['BY_PDDK_PEGAWAI']['kategori'][] = $dt2a[$a];
        }
        if (empty($dt2)) {
            $data['BY_PDDK_PEGAWAI']['kolom'] = [];
        } else {
            $data['BY_PDDK_PEGAWAI']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2['P'],
                ],
            ];
        }

        //Berdasarkan Pendidikan Pegawai Non PNS
        $qJmlPddkNon = "SELECT 
	    b.`pddkNama` AS PDDK_NAMA,
            a.`pjptJenkel` AS JENKEL,
            SUM(a.`pjptJumlah`)AS JML
            FROM `peg_jml_pegawai_total` a
            JOIN `peg_ref_pendidikan` b ON b.`pddkId`=a.`pjptPddkId`
            WHERE MONTH(a.`pjptCreate`)=:bln AND a.`pjptJnsPegId` IN('1','4')
            AND a.`pjptCreate` IN (SELECT MAX(pjptCreate) FROM `peg_jml_pegawai_total`)
            GROUP BY a.`pjptPddkId`,a.`pjptJenkel`
            ORDER BY b.`pddkId` ASC,a.`pjptJenkel`";
        $rsJmlPddkNon = $conn->QueryAll($qJmlPddkNon, [
            ':bln' => $rsBln['bln']
        ]);
        $dt2Non = [];
        $dt2aNon = [];
        foreach ($rsJmlPddkNon as $val) {
            if ($val['JENKEL'] == 'L') {
                $dt2Non['L'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if ($val['JENKEL'] == 'P') {
                $dt2Non['P'][] = [$val['PDDK_NAMA'], (int) $val['JML']];
            }
            if (!in_array($val['PDDK_NAMA'], $dt2aNon)) {
                $dt2aNon[] = $val['PDDK_NAMA'];
            }
        }
        for ($a = 0; $a < count($dt2aNon); $a++) {
            $data['BY_PDDK_PEGAWAI_NON']['kategori'][] = $dt2aNon[$a];
        }
        if (empty($dt2Non)) {
            $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [];
        } else {
            $data['BY_PDDK_PEGAWAI_NON']['kolom'] = [
                    [
                    'name' => 'Laki-Laki',
                    'data' => $dt2Non['L'],
                ],
                    [
                    'name' => 'Perempuan',
                    'data' => $dt2Non['P'],
                ],
            ];
        }

        return $this->render("jmlPegawaiPendidikan", [
                    'data' => $data,
                    'bln' => $rsBln['bln'],
                    'thn' => $rsBln['thn']
        ]);
    }

}
