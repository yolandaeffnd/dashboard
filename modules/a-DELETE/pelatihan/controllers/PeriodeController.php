<?php

namespace app\modules\pelatihan\controllers;

use Yii;
use app\modules\pelatihan\models\LatPeriode;
use app\modules\pelatihan\models\LatPeriodeSearch;
use app\modules\pelatihan\models\LatPeriodeRuleAngkatan;
use app\modules\pelatihan\models\LatPeriodeRuleMemberKategori;
use app\modules\pelatihan\models\LatPeriodeRulePeriode;
use app\modules\pelatihan\models\LatPeriodeRuleTarif;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;

/**
 * PeriodeController implements the CRUD actions for LatPeriode model.
 */
class PeriodeController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'index' => ['POST', 'GET'],
                    'create' => ['POST', 'GET'],
                    'update' => ['POST', 'GET'],
                    'view' => ['GET'],
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
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlPeriodePelatihan'),
                ]
            ]
        ];
    }

    /**
     * Lists all LatPeriode models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new LatPeriodeSearch();
        $searchModel->periodeNama = Yii::$app->request->get('periode');
        $searchModel->periodeJnslatId = Yii::$app->request->get('pelatihan');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LatPeriode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = LatPeriode::findOne($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new LatPeriode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $inDate = new IndonesiaDate();
        $conn = new DAO();
        $model = new LatPeriode();
        $model->periodeCreate = $inDate->getNow();
        $model->periodeMaxSkor = '';
        if ($model->load(Yii::$app->request->post())) {
            if (\Yii::$app->request->post('btn-simpan')) {
                if ($model->periodeNama == '') {
                    $model->addError('periodeNama', 'Periode cannot be blank.');
                } else if ($model->periodeJnslatId == '') {
                    $model->addError('periodeJnslatId', 'Jenis Pelatihan cannot be blank.');
                } else if ($model->periodeRegAwal == '') {
                    $model->addError('periodeRegAwal', 'Awal Registrasi cannot be blank.');
                } else if ($model->periodeRegAkhir == '') {
                    $model->addError('periodeRegAkhir', 'Akhir Registrasi cannot be blank.');
                } else if ($model->periodeLakMulai == '') {
                    $model->addError('periodeLakMulai', 'Awal Periode cannot be blank.');
                } else if ($model->periodeLakSelesai == '') {
                    $model->addError('periodeLakSelesai', 'Akhir Periode cannot be blank.');
                } else if ($model->periodeMaxSkor == '') {
                    $model->addError('periodeMaxSkor', 'Skor Maksimal Yang Diizinkan cannot be blank.');
                } else {
                    if ($model->save()) {
                        //Rule Angkatan
                        $jmlAkt = 0;
                        if (!empty($model->ruleAngkatan)) {
                            $akt = count($model->ruleAngkatan);
                            $qDelAkt = "DELETE FROM lat_periode_rule_angkatan WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelAkt, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->ruleAngkatan); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_angkatan VALUE(:periode,:angkatan)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':angkatan' => $model->ruleAngkatan[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlAkt = $jmlAkt + 1;
                                }
                            }
                        } else {
                            $qDelAkt = "DELETE FROM lat_periode_rule_angkatan WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelAkt, [':id' => $model->periodeId]);
                            $akt = 0;
                        }
                        //Rule Member kategori
                        $jmlKat = 0;
                        if (!empty($model->ruleMemberKat)) {
                            $kat = count($model->ruleMemberKat);
                            $qDelKat = "DELETE FROM lat_periode_rule_member_kategori WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelKat, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->ruleMemberKat); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_member_kategori VALUE(:periode,:kat)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':kat' => $model->ruleMemberKat[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlKat = $jmlKat + 1;
                                }
                            }
                        } else {
                            $qDelKat = "DELETE FROM lat_periode_rule_member_kategori WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelKat, [':id' => $model->periodeId]);
                            $kat = 0;
                        }
                        //Rule Periode
                        $jmlPer = 0;
                        if (!empty($model->rulePeriode)) {
                            $per = count($model->rulePeriode);
                            $qDelPer = "DELETE FROM lat_periode_rule_periode WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelPer, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->rulePeriode); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_periode VALUE(:periode,:periodenotallow)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':periodenotallow' => $model->rulePeriode[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlPer = $jmlPer + 1;
                                }
                            }
                        } else {
                            $qDelPer = "DELETE FROM lat_periode_rule_periode WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelPer, [':id' => $model->periodeId]);
                            $per = 0;
                        }
                        //Rule Tarif
                        $jmlTar = 0;
                        if (!empty($model->ruleTarif)) {
                            $tar = count($model->ruleTarif);
                            $qDelTar = "DELETE FROM lat_periode_rule_tarif WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelTar, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->ruleTarif); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_tarif VALUE(:periode,:tarif)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':tarif' => $model->ruleTarif[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlTar = $jmlTar + 1;
                                }
                            }
                        } else {
                            $qDelTar = "DELETE FROM lat_periode_rule_tarif WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelTar, [':id' => $model->periodeId]);
                            $tar = 0;
                        }
                        //Redirect
                        if ($akt == $jmlAkt && $kat == $jmlKat && $per == $jmlPer && $tar == $jmlTar) {
                            return $this->redirect(['view', 'id' => $model->periodeId]);
                        }
                    }
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing LatPeriode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $inDate = new IndonesiaDate();
        $conn = new DAO();
        $model = $this->findModel($id);
        //Rule Angkatan
        $modelRuleAngkatan = LatPeriodeRuleAngkatan::find()
                ->where('rulePeriodeId=:id', [':id' => $model->periodeId])
                ->each();
        $akt = [];
        foreach ($modelRuleAngkatan as $val) {
            $akt[] = $val['ruleAllowAngkatan'];
        }
        $model->ruleAngkatan = $akt;
        //Rule Member Kategori
        $modelRuleMemberKat = LatPeriodeRuleMemberKategori::find()
                ->where('rulePeriodeId=:id', [':id' => $model->periodeId])
                ->each();
        $kat = [];
        foreach ($modelRuleMemberKat as $val) {
            $kat[] = $val['ruleAllowMemberKatId'];
        }
        $model->ruleMemberKat = $kat;
        //Rule Periode
        $modelRulePeriode = LatPeriodeRulePeriode::find()
                ->where('rulePeriodeId=:id', [':id' => $model->periodeId])
                ->each();
        $per = [];
        foreach ($modelRulePeriode as $val) {
            $per[] = $val['ruleNotAllowPeriode'];
        }
        $model->rulePeriode = $per;
        //Rule Tarif
        $modelRuleTarif = LatPeriodeRuleTarif::find()
                ->where('rulePeriodeId=:id', [':id' => $model->periodeId])
                ->each();
        $tar = [];
        foreach ($modelRuleTarif as $val) {
            $tar[] = $val['ruleTarifId'];
        }
        $model->ruleTarif = $tar;
        
        $model->periodeUpdate = $inDate->getNow();
        $model->periodeMaxSkor = ($model->periodeMaxSkor == 0) ? '' : $model->periodeMaxSkor;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (\Yii::$app->request->post('btn-simpan')) {
                if ($model->periodeNama == '') {
                    $model->addError('periodeNama', 'Periode cannot be blank.');
                } else if ($model->periodeJnslatId == '') {
                    $model->addError('periodeJnslatId', 'Jenis Pelatihan cannot be blank.');
                } else if ($model->periodeRegAwal == '') {
                    $model->addError('periodeRegAwal', 'Awal Registrasi cannot be blank.');
                } else if ($model->periodeRegAkhir == '') {
                    $model->addError('periodeRegAkhir', 'Akhir Registrasi cannot be blank.');
                } else if ($model->periodeLakMulai == '') {
                    $model->addError('periodeLakMulai', 'Awal Periode cannot be blank.');
                } else if ($model->periodeLakSelesai == '') {
                    $model->addError('periodeLakSelesai', 'Akhir Periode cannot be blank.');
                } else if ($model->periodeMaxSkor == '') {
                    $model->addError('periodeMaxSkor', 'Skor Maksimal Yang Diizinkan cannot be blank.');
                } else {
                    if ($model->save()) {
                        //Rule Angkatan
                        $jmlAkt = 0;
                        if (!empty($model->ruleAngkatan)) {
                            $akt = count($model->ruleAngkatan);
                            $qDelAkt = "DELETE FROM lat_periode_rule_angkatan WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelAkt, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->ruleAngkatan); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_angkatan VALUE(:periode,:angkatan)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':angkatan' => $model->ruleAngkatan[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlAkt = $jmlAkt + 1;
                                }
                            }
                        } else {
                            $qDelAkt = "DELETE FROM lat_periode_rule_angkatan WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelAkt, [':id' => $model->periodeId]);
                            $akt = 0;
                        }
                        //Rule Member kategori
                        $jmlKat = 0;
                        if (!empty($model->ruleMemberKat)) {
                            $kat = count($model->ruleMemberKat);
                            $qDelKat = "DELETE FROM lat_periode_rule_member_kategori WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelKat, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->ruleMemberKat); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_member_kategori VALUE(:periode,:kat)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':kat' => $model->ruleMemberKat[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlKat = $jmlKat + 1;
                                }
                            }
                        } else {
                            $qDelKat = "DELETE FROM lat_periode_rule_member_kategori WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelKat, [':id' => $model->periodeId]);
                            $kat = 0;
                        }
                        //Rule Periode
                        $jmlPer = 0;
                        if (!empty($model->rulePeriode)) {
                            $per = count($model->rulePeriode);
                            $qDelPer = "DELETE FROM lat_periode_rule_periode WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelPer, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->rulePeriode); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_periode VALUE(:periode,:periodenotallow)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':periodenotallow' => $model->rulePeriode[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlPer = $jmlPer + 1;
                                }
                            }
                        } else {
                            $qDelPer = "DELETE FROM lat_periode_rule_periode WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelPer, [':id' => $model->periodeId]);
                            $per = 0;
                        }
                        //Rule Tarif
                        $jmlTar = 0;
                        if (!empty($model->ruleTarif)) {
                            $tar = count($model->ruleTarif);
                            $qDelTar = "DELETE FROM lat_periode_rule_tarif WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelTar, [':id' => $model->periodeId]);
                            for ($i = 0; $i < count($model->ruleTarif); $i++) {
                                $qInsert = "INSERT INTO lat_periode_rule_tarif VALUE(:periode,:tarif)";
                                $rsInsert = $conn->Execute($qInsert, [':periode' => $model->periodeId, ':tarif' => $model->ruleTarif[$i]]);
                                if ($rsInsert == 1) {
                                    $jmlTar = $jmlTar + 1;
                                }
                            }
                        } else {
                            $qDelTar = "DELETE FROM lat_periode_rule_tarif WHERE rulePeriodeId=:id";
                            $conn->Execute($qDelTar, [':id' => $model->periodeId]);
                            $tar = 0;
                        }
                        //Redirect
                        if ($akt == $jmlAkt && $kat == $jmlKat && $per == $jmlPer && $tar == $jmlTar) {
                            return $this->redirect(['view', 'id' => $model->periodeId]);
                        }
                    }
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LatPeriode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $conn = new DAO();
        $trans = $conn->beginTransaction();
        try {
            $qDel = "DELETE FROM lat_periode_rule_angkatan WHERE rulePeriodeId=:id";
            $conn->Execute($qDel, [':id' => $id]);
            $this->findModel($id)->delete();
            $trans->commit();
        } catch (yii\db\Exception $e) {
            $trans->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the LatPeriode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LatPeriode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = LatPeriode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
