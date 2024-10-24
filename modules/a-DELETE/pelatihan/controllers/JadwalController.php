<?php

namespace app\modules\pelatihan\controllers;

use Yii;
use app\modules\pelatihan\models\LatJadwal;
use app\modules\pelatihan\models\LatJadwalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;

/**
 * JadwalController implements the CRUD actions for LatJadwal model.
 */
class JadwalController extends Controller {

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
                    User::userAccessRoles2('ctrlJadwalPelatihan'),
                ]
            ]
        ];
    }

    /**
     * Lists all LatJadwal models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new LatJadwalSearch();
        $searchModel->jdwlKlsId = Yii::$app->request->get('kls');
        $searchModel->jdwlRuangId = Yii::$app->request->get('ruang');
        $searchModel->jdwlHariKode = Yii::$app->request->get('hari');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single LatJadwal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LatJadwal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $conn = new DAO();
        $inDate = new IndonesiaDate();
        $model = new LatJadwal();
        $model->jdwlCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $trans = $conn->beginTransaction();
            try {
                $arrHari = $model->jdwlHariKode;
                $jml = 0;
                for ($i = 0; $i < count($arrHari); $i++) {
                    $qCek = "SELECT * FROM lat_jadwal lj "
                            . "JOIN lat_kelas lk ON lk.klsId=lj.jdwlKlsId "
                            . "JOIN lat_periode lp ON lp.periodeId=lk.klsPeriodeId "
                            . "WHERE DATE(NOW())<=periodeLakSelesai "
                            . "AND jdwlRuangId=:ruang AND jdwlHariKode=:hari "
                            . "AND ((:mulai>=jdwlJamMulai AND :mulai<=jdwlJamSelesai) OR (:selesai>=jdwlJamMulai AND :selesai<=jdwlJamSelesai))";
                    $rsCek = $conn->QueryAll($qCek, [
                        //':klsid' => $model->jdwlKlsId,
                        ':ruang' => $model->jdwlRuangId,
                        ':hari' => $arrHari[$i],
                        ':mulai' => $model->jdwlJamMulai,
                        ':selesai' => $model->jdwlJamSelesai
                    ]);
                    if (empty($rsCek)) {
                        $qInsert = "INSERT INTO lat_jadwal(jdwlKlsId,jdwlRuangId,jdwlHariKode,jdwlJamMulai,jdwlJamSelesai,jdwlCreate) "
                                . "VALUE(:klsid,:ruang,:hari,:mulai,:selesai,:buat)";
                        $rsInsert = $conn->Execute($qInsert, [
                            ':klsid' => $model->jdwlKlsId,
                            ':ruang' => $model->jdwlRuangId,
                            ':hari' => $arrHari[$i],
                            ':mulai' => $model->jdwlJamMulai,
                            ':selesai' => $model->jdwlJamSelesai,
                            ':buat' => $model->jdwlCreate
                        ]);
                        if ($rsInsert == 1) {
                            $jml = $jml + 1;
                        }
                    } else {
                        $model->addError('jdwlKlsId', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlRuangId', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlHariKode', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlJamMulai', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlJamSelesai', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                    }
                }
                if (count($arrHari) == $jml) {
                    $trans->commit();
                    return $this->redirect(['index']);
                } else {
                    $trans->rollBack();
                }
            } catch (yii\db\Exception $e) {
                $trans->rollBack();
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing LatJadwal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $conn = new DAO();
        $inDate = new IndonesiaDate();
        $model = $this->findModel($id);
        $model->jdwlUpdate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $trans = $conn->beginTransaction();
            try {
                $arrHari = $model->jdwlHariKode;
                $jml = 0;
                for ($i = 0; $i < count($arrHari); $i++) {
                    $qCek = "SELECT * FROM lat_jadwal lj "
                            . "JOIN lat_kelas lk ON lk.klsId=lj.jdwlKlsId "
                            . "JOIN lat_periode lp ON lp.periodeId=lk.klsPeriodeId "
                            . "WHERE DATE(NOW())<=periodeLakSelesai "
                            . "AND jdwlRuangId=:ruang AND jdwlHariKode=:hari "
                            . "AND ((:mulai>=jdwlJamMulai AND :mulai<=jdwlJamSelesai) OR (:selesai>=jdwlJamMulai AND :selesai<=jdwlJamSelesai)) "
                            . "AND lj.jdwlKlsId<>:klsid "
                            . "AND lp.`periodeLakSelesai`>=(SELECT lp.`periodeLakMulai` FROM lat_kelas lk "
                            . "JOIN lat_periode lp ON lp.periodeId=lk.klsPeriodeId WHERE lk.`klsId`=:klsid)";
//                            . "AND jdwlId=:id";
                    $rsCek = $conn->QueryAll($qCek, [
//                        ':id' => $id,
                        ':klsid' => $model->jdwlKlsId,
                        ':ruang' => $model->jdwlRuangId,
                        ':hari' => $arrHari[$i],
                        ':mulai' => $model->jdwlJamMulai,
                        ':selesai' => $model->jdwlJamSelesai
                    ]);
                    if (empty($rsCek)) {
                        $qUpdate = "UPDATE lat_jadwal SET jdwlKlsId=:klsid,"
                                . "jdwlRuangId=:ruang,jdwlHariKode=:hari,"
                                . "jdwlJamMulai=:mulai,jdwlJamSelesai=:selesai,"
                                . "jdwlUpdate=:buat "
                                . "WHERE jdwlId=:id";
                        $rsUpdate = $conn->Execute($qUpdate, [
                            ':klsid' => $model->jdwlKlsId,
                            ':ruang' => $model->jdwlRuangId,
                            ':hari' => $arrHari[$i],
                            ':mulai' => $model->jdwlJamMulai,
                            ':selesai' => $model->jdwlJamSelesai,
                            ':buat' => $model->jdwlUpdate,
                            ':id' => $id
                        ]);
                        if ($rsUpdate == 1) {
                            $jml = $jml + 1;
                        }
                    } else {
                        $model->addError('jdwlKlsId', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlRuangId', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlHariKode', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlJamMulai', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                        $model->addError('jdwlJamSelesai', 'Maaf, Jadwal bentrok dengan jadwal lain!');
                    }
                }
                if (count($arrHari) == $jml) {
                    $trans->commit();
                    return $this->redirect(['index']);
                } else {
                    $trans->rollBack();
                }
            } catch (yii\db\Exception $e) {
                $trans->rollBack();
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LatJadwal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LatJadwal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LatJadwal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = LatJadwal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
