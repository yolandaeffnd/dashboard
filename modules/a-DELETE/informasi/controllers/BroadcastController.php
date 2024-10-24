<?php

namespace app\modules\informasi\controllers;

use Yii;
use app\modules\informasi\models\Broadcast;
use app\modules\informasi\models\BroadcastSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;

/**
 * BroadcastController implements the CRUD actions for Broadcast model.
 */
class BroadcastController extends Controller {

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
                    User::userAccessRoles2('ctrlBroadcast'),
                ]
            ]
        ];
    }

    /**
     * Lists all Broadcast models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new BroadcastSearch();
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Broadcast model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Broadcast model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $inDate = new IndonesiaDate();
        $conn = new DAO();
        $model = new Broadcast();
        $model->bcCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('btn-kirim')) {
                if ($model->bcTo == '') {
                    $model->addError('bcTo', 'Tujuan cannot be blank.');
                } else if ($model->bcJudul == '') {
                    $model->addError('bcJudul', 'Judul cannot be blank.');
                } else if ($model->bcIsi == '') {
                    $model->addError('bcIsi', 'Uraian/Isi cannot be blank.');
                } else {
                    $jmlExc = 0;
                    for ($i = 0; $i < count($model->bcTo); $i++) {
                        $qInsert = "INSERT INTO broadcast(bcTo,bcJudul,bcIsi,bcCreate) "
                                . "VALUE(:tujuan,:judul,:isi,:buat)";
                        $rsInsert = $conn->Execute($qInsert, [
                            ':tujuan' => $model->bcTo[$i],
                            ':judul' => $model->bcJudul,
                            ':isi' => $model->bcIsi,
                            ':buat' => $model->bcCreate
                        ]);
                        if ($rsInsert == 1) {
                            $send = Yii::$app->mailer->compose(['html' => 'layouts/html'], [
                                        'content' => $this->renderPartial('mailBroadcast', [
                                            'judul' => $model->bcJudul,
                                            'isi' => $model->bcIsi,
                                        ])
                                    ])
                                    ->setFrom(Yii::$app->params['adminEmail'])
                                    ->setTo($model->bcTo[$i])
                                    ->setSubject('SimPB : Informasi - UPT.Pusat Bahasa UNAND')
                                    ->send();
                            if ($send == 1) {
                                $jmlExc = $jmlExc + 1;
                            }
                        }
                    }
                    if(count($model->bcTo)==$jmlExc){
                        return $this->redirect(['index']);
                    }
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Broadcast model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bcId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Broadcast model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Broadcast model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Broadcast the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Broadcast::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
