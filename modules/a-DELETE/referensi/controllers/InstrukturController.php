<?php

namespace app\modules\referensi\controllers;

use Yii;
use app\modules\referensi\models\RefInstruktur;
use app\modules\referensi\models\RefInstrukturSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;
use app\models\AppGroup;

/**
 * InstrukturController implements the CRUD actions for RefInstruktur model.
 */
class InstrukturController extends Controller {

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
                    User::userAccessRoles2('ctrlInstruktur'),
                ]
            ]
        ];
    }

    /**
     * Lists all RefInstruktur models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RefInstrukturSearch();
        $searchModel->instNama = Yii::$app->request->get('nama');
        $searchModel->instEmail = Yii::$app->request->get('email');
        $searchModel->instJenkel = Yii::$app->request->get('jenkel');
        $searchModel->instTelp = Yii::$app->request->get('telp');
        $searchModel->instIsAktif = Yii::$app->request->get('aktif');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RefInstruktur model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RefInstruktur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $conn = new DAO();
        $inDate = new IndonesiaDate();
        $passDefault = $this->RandomChar(8);
        $model = new RefInstruktur();
        $model->instCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $qCek = "SELECT * FROM ref_instruktur WHERE instEmail=:email";
            $rsCek = $conn->QueryAll($qCek, [
                ':email' => $model->instEmail
            ]);
            if (!empty($rsCek)) {
                $model->addError('instEmail', 'Maaf, Email sudah digunakan!');
            } else {
                $qCekAkun = "SELECT * FROM app_user WHERE usernameApp=:username";
                $rsCekAkun = $conn->QueryRow($qCekAkun, [':username' => $model->instEmail]);
                if (empty($rsCekAkun)) {
                    $qInsert = "INSERT INTO app_user(nama,telp,usernameApp,passwordApp,idGroup,isAktif,tglEntri) "
                            . "VALUE(:nama,:telp,:usern,:pass,:group,:aktif,:tglentri)";
                    $rsInsert = $conn->Execute($qInsert, [
                        ':nama' => $model->instNama,
                        ':telp' => $model->instTelp,
                        ':usern' => $model->instEmail,
                        ':pass' => md5($passDefault),
                        ':group' => AppGroup::find()->where('isMemberGroup="2"')->one()->idGroup,
                        ':aktif' => $model->instIsAktif,
                        ':tglentri' => $inDate->getNow()
                    ]);
                    if ($rsInsert == 1) {
                        Yii::$app->mailer->compose(['html' => 'layouts/html'], [
                                    'content' => $this->renderPartial('mailNewAkun', [
                                        'nama' => $model->instNama,
                                        'email' => $model->instEmail,
                                        'pass' => $passDefault
                                    ])
                                ])
                                ->setFrom(Yii::$app->params['adminEmail'])
                                ->setTo($model->instEmail)
                                ->setSubject('Akun Instruktur SimPB - UPT.Pusat Bahasa UNAND')
                                ->send();
                    }
                } else {
                    $qUpdate= "UPDATE app_user SET nama=:nama,telp=:telp,"
                            . "passwordApp=:pass,isAktif=:aktif,tglEntri=:tglentri "
                            . "WHERE usernameApp=:usern";
                    $rsUpdate = $conn->Execute($qUpdate, [
                        ':nama' => $model->instNama,
                        ':telp' => $model->instTelp,
                        ':usern' => $model->instEmail,
                        ':pass' => md5($passDefault),
                        ':aktif' => $model->instIsAktif,
                        ':tglentri' => $inDate->getNow()
                    ]);
                    if ($rsUpdate == 1) {
                        Yii::$app->mailer->compose(['html' => 'layouts/html'], [
                                    'content' => $this->renderPartial('mailResetAkun', [
                                        'nama' => $model->instNama,
                                        'email' => $model->instEmail,
                                        'pass' => $passDefault
                                    ])
                                ])
                                ->setFrom(Yii::$app->params['adminEmail'])
                                ->setTo($model->instEmail)
                                ->setSubject('Reset Akun Instruktur SimPB - UPT.Pusat Bahasa UNAND')
                                ->send();
                    }
                }
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RefInstruktur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $conn = new DAO();
        $inDate = new IndonesiaDate();
        $passDefault = $this->RandomChar(8);
        $model = $this->findModel($id);
        $model->instUpdate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $qCek = "SELECT * FROM ref_instruktur WHERE instId<>:id AND instEmail=:email";
            $rsCek = $conn->QueryAll($qCek, [
                ':id' => $model->instId,
                ':email' => $model->instEmail
            ]);
            if (!empty($rsCek)) {
                $model->addError('instEmail', 'Maaf, Email sudah digunakan!');
            } else {
                if ($model->save()) {
                    $qCekAkun = "SELECT * FROM app_user WHERE usernameApp=:username";
                    $rsCekAkun = $conn->QueryRow($qCekAkun, [':username' => $model->instEmail]);
                    if (empty($rsCekAkun)) {
                        $qInsert = "INSERT INTO app_user(nama,telp,usernameApp,passwordApp,idGroup,isAktif,tglEntri) "
                                . "VALUE(:nama,:telp,:usern,:pass,:group,:aktif,:tglentri)";
                        $rsInsert = $conn->Execute($qInsert, [
                            ':nama' => $model->instNama,
                            ':telp' => $model->instTelp,
                            ':usern' => $model->instEmail,
                            ':pass' => md5($passDefault),
                            ':group' => AppGroup::find()->where('isMemberGroup="2"')->one()->idGroup,
                            ':aktif' => $model->instIsAktif,
                            ':tglentri' => $inDate->getNow()
                        ]);
                        if ($rsInsert == 1) {
                            Yii::$app->mailer->compose(['html' => 'layouts/html'], [
                                        'content' => $this->renderPartial('mailNewAkun', [
                                            'nama' => $model->instNama,
                                            'email' => $model->instEmail,
                                            'pass' => $passDefault
                                        ])
                                    ])
                                    ->setFrom(Yii::$app->params['adminEmail'])
                                    ->setTo($model->instEmail)
                                    ->setSubject('Akun Instruktur SimPB - UPT.Pusat Bahasa UNAND')
                                    ->send();
                        }
                    } else {
                        $qUpdate = "UPDATE app_user SET nama=:nama,telp=:telp,"
                                . "passwordApp=:pass,isAktif=:aktif,tglEntri=:tglentri "
                                . "WHERE usernameApp=:usern";
                        $rsUpdate = $conn->Execute($qUpdate, [
                            ':nama' => $model->instNama,
                            ':telp' => $model->instTelp,
                            ':usern' => $model->instEmail,
                            ':pass' => md5($passDefault),
                            ':aktif' => $model->instIsAktif,
                            ':tglentri' => $inDate->getNow()
                        ]);
                        if ($rsUpdate == 1) {
                            Yii::$app->mailer->compose(['html' => 'layouts/html'], [
                                        'content' => $this->renderPartial('mailResetAkun', [
                                            'nama' => $model->instNama,
                                            'email' => $model->instEmail,
                                            'pass' => $passDefault
                                        ])
                                    ])
                                    ->setFrom(Yii::$app->params['adminEmail'])
                                    ->setTo($model->instEmail)
                                    ->setSubject('Reset Akun Instruktur SimPB - UPT.Pusat Bahasa UNAND')
                                    ->send();
                        }
                    }
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RefInstruktur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $conn = new DAO();
        $inst = $this->findModel($id);
        $qDel = "DELETE FROM app_user WHERE usernameApp=:email";
        $conn->Execute($qDel, [':email' => $inst->instEmail]);
        $inst->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RefInstruktur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RefInstruktur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RefInstruktur::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function RandomChar($length) {
        $data = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = rand(0, strlen($data) - 1);
            $string .= $data{$pos};
        }
        return $string;
    }

}
