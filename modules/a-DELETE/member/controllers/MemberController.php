<?php

namespace app\modules\member\controllers;

use Yii;
use app\modules\member\models\Member;
use app\modules\member\models\MemberSearch;
use app\modules\member\models\LatPeserta;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\IndonesiaDate;
use yii\data\ActiveDataProvider;
use app\modules\member\models\AppGroup;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends Controller {

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
                    'resetpassword' => ['GET'],
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
                    'resetpassword'
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlMember'),
                        [
                        'allow' => true,
                        'actions' => [
                            'resetpassword'
                        ],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MemberSearch();
        $searchModel->memberId = Yii::$app->request->get('id');
        $searchModel->memberNama = Yii::$app->request->get('nama');
        $searchModel->memberEmail = Yii::$app->request->get('email');
        $searchModel->memberMemberKatId = Yii::$app->request->get('kat');
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Member model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        //Historis Pelatihan
        $modelPeserta = LatPeserta::find()
                ->where([
            'pesertaMemberId' => $model->memberId
        ]);
        $dataProviderPeserta = new ActiveDataProvider([
            'query' => $modelPeserta,
            'pagination' => [
                'pagesize' => 50
            ]
        ]);
        return $this->render('view', [
                    'model' => $model,
                    'dataProviderPeserta' => $dataProviderPeserta
        ]);
    }

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Member();
        $inDate = new IndonesiaDate();
        $model->memberIsAkunPortal = '0';
        $defaultPass = $this->RandomChar(8);
        $model->memberPassword = md5($defaultPass);
        $model->memberGroupId = AppGroup::find()->where('isMemberGroup="1"')->one()->idGroup;
        $model->memberCreate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $model->memberId = $this->CreateMemberID();
            if ($model->save()) {
                $content = \yii\helpers\Html::beginTag('div', ['style' => "border: solid 1px #cccccc;border-radius: 2px;width:800px;"])
                        . \yii\helpers\Html::beginTag('div', ['style' => "border-bottom: solid 5px blue;"])
                        . "<h2 style='margin-bottom:0px;'>Sistem Informasi Manajemen Pelatihan Bahasa (SimPB)</h2>"
                        . "<h1 style='margin-top:0px;'>Universitas Andalas</h1>"
                        . \yii\helpers\Html::endTag('div')
                        . \yii\helpers\Html::beginTag('div', ['style' => "padding: 10px;"])
                        . \yii\helpers\Html::beginTag('p', ['style' => "font-size:14px;"])
                        . "Selamat " . $model->memberNama . "!<br/>"
                        . "Anda telah terdaftar sebagai member pelatihan di UPT. Pusat Bahasa Universitas Andalas.<br/>"
                        . "Berikut dikirimkan keterangan akun anda :<br/>"
                        . "ID Member : " . $model->memberId . "<br/>"
                        . "Email : " . $model->memberEmail . "<br/>"
                        . "Password : " . $defaultPass . "<br/>"
                        . "Demikian informasi ini disampaikan, silahkan gunakan untuk login di " . \yii\helpers\Html::a('simpb.lc.unand.ac.id', 'http://simpb.lc.unand.ac.id')
                        . \yii\helpers\Html::endTag('p')
                        . \yii\helpers\Html::endTag('div')
                        . \yii\helpers\Html::beginTag('div', ['style' => "border-top: solid 3px #cccccc;margin:10px;"])
                        . \yii\helpers\Html::beginTag('p', ['style' => "font-size:11px;"])
                        . "Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke email ini."
                        . \yii\helpers\Html::endTag('p')
                        . \yii\helpers\Html::endTag('div')
                        . \yii\helpers\Html::endTag('div');

                $send = Yii::$app->mailer->compose(['html' => 'layouts/html'], [
                            'content' => $content
                        ])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($model->memberEmail)
                        ->setSubject('Akun SimPB - Universitas Andalas')
                        ->send();
                if ($send == 1) {
                    return $this->redirect(['view', 'id' => $model->memberId]);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $inDate = new IndonesiaDate();
        $model = $this->findModel($id);
        $model->memberUpdate = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())&&$model->save(0)) {
            return $this->redirect(['view', 'id' => $model->memberId]);
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Member model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $del = $this->findModel($id);
        $file = \Yii::getAlias('@webroot/../../berkas/photos/' . $del->memberFoto);
        if (!empty($del)) {
            $del->delete();
            if (file_exists($file) && $del->memberFoto != '') {
                unlink($file);
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function CreateMemberID() {
        $conn = new DAO();
        $inDate = new IndonesiaDate();
        $qCek = "SELECT * FROM counter_member WHERE memberThn=:thn";
        $rsCek = $conn->QueryRow($qCek, [':thn' => $inDate->getYear()]);
        $thn = $inDate->getYear();
        if (empty($rsCek)) {
            $nextUrutan = sprintf("%05s", 1);
            $kode = 'LCPB' . $thn . $nextUrutan;
            $qInsert = "INSERT INTO counter_member VALUE(:thn,:urut)";
            $conn->Execute($qInsert, [
                ':urut' => $nextUrutan,
                ':thn' => $inDate->getYear(),
            ]);
        } else {
            $nextUrutan = sprintf("%05s", (1 + (int) $rsCek['memberUrut']));
            $kode = 'LCPB' . $thn . $nextUrutan;
            $qUpdate = "UPDATE counter_member SET memberUrut=:urut WHERE memberThn=:thn";
            $conn->Execute($qUpdate, [
                ':urut' => $nextUrutan,
                ':thn' => $inDate->getYear(),
            ]);
        }
        return $kode;
    }

    public function actionResetpassword($id) {
        if (Yii::$app->request->isAjax) {
            $conn = new DAO();
            $defaultPass = $this->RandomChar(8);
            $qUpdate = "UPDATE member SET memberPassword=:pass WHERE memberId=:id";
            $rsUpdate = $conn->Execute($qUpdate, [
                ':pass' => md5($defaultPass),
                ':id' => $id,
            ]);
            if ($rsUpdate) {
                try {
                    $model = $this->findModel($id);
                    $content = \yii\helpers\Html::beginTag('div', ['style' => "border: solid 1px #cccccc;border-radius: 2px;width:800px;"])
                            . \yii\helpers\Html::beginTag('div', ['style' => "border-bottom: solid 5px blue;"])
                            . "<h2 style='margin-bottom:0px;'>Sistem Informasi Manajemen Pelatihan Bahasa (SimPB)</h2>"
                            . "<h1 style='margin-top:0px;'>Universitas Andalas</h1>"
                            . \yii\helpers\Html::endTag('div')
                            . \yii\helpers\Html::beginTag('div', ['style' => "padding: 10px;"])
                            . \yii\helpers\Html::beginTag('p', ['style' => "font-size:14px;"])
                            . "Selamat " . $model->memberNama . "!<br/>"
                            . "Password akun SimPB anda telah direset dengan password baru : " . $defaultPass . "<br/>"
                            . "Demikian informasi ini disampaikan, silahkan gunakan untuk login di " . \yii\helpers\Html::a('simpb.lc.unand.ac.id', 'http://simpb.lc.unand.ac.id')
                            . \yii\helpers\Html::endTag('p')
                            . \yii\helpers\Html::endTag('div')
                            . \yii\helpers\Html::beginTag('div', ['style' => "border-top: solid 3px #cccccc;margin:10px;"])
                            . \yii\helpers\Html::beginTag('p', ['style' => "font-size:11px;"])
                            . "Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke email ini."
                            . \yii\helpers\Html::endTag('p')
                            . \yii\helpers\Html::endTag('div')
                            . \yii\helpers\Html::endTag('div');

                    $send = Yii::$app->mailer->compose(['html' => 'layouts/html'], [
                                'content' => $content
                            ])
                            ->setFrom(Yii::$app->params['adminEmail'])
                            ->setTo($model->memberEmail)
                            ->setSubject('Reset Akun SimPB - Universitas Andalas')
                            ->send();
                    if ($send == 1) {
                        return 410;
                    } else {
                        return 401;
                    }
                } catch (\Swift_TransportException $e) {
                    return 400;
                }
            }
        }
    }

    function RandomChar($length) {
        $data = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = rand(0, strlen($data) - 1);
            $string .= $data{$pos};
        }
        return $string;
    }

}
