<?php

namespace app\controllers;

use Yii;
use app\models\AppUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Member;
use app\models\DAO;
use yii\web\UploadedFile;

/**
 * ProfilController implements the CRUD actions for AppUser model.
 */
class ProfilController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'update', 'gantipassword', 'fotoprofil'],
                'rules' => [
                        [
                        'allow' => true,
                        'actions' => ['view', 'update', 'gantipassword', 'fotoprofil'],
                        'roles' => ["@"],
                    ]
                ]
            ]
        ];
    }

    public function actionFotoprofil() {
        $id = Yii::$app->user->identity->userId;
        $conn = new DAO();
        $model = Member::find()->where('memberId=:id', [':id' => $id])->One();
//        $fotoOld = $model->memberFoto;
        if ($model->load(Yii::$app->request->post())) {
            $imageName = $model->memberId;
            $model->memberFoto = UploadedFile::getInstance($model, 'memberFoto');
            if ($model->memberFoto != null) {
                $uploaded = $model->memberFoto->saveAs(Yii::getAlias('photos/') . $imageName . '.' . $model->memberFoto->extension);
                if ($uploaded) {
                    $model->memberFoto = $imageName . '.' . $model->memberFoto->extension;
                    $qUpdate = 'UPDATE member SET memberFoto=:foto WHERE memberId=:id';
                    $conn->Execute($qUpdate, [':foto' => $model->memberFoto, ':id' => $id]);
                    $model->save();
                }
                return $this->redirect(['view']);
            }
        }
        return $this->render('fotoProfil', [
                    'model' => $model,
        ]);
    }

    public function actionGantipassword() {
        $id = Yii::$app->user->identity->userId;
        if (AppUser::find()->where('idUser=:id', [':id' => $id])->count() > 0) {
            $ket = '';
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post())) {
                if ($model->passwordAppNew != $model->passwordAppNewUlang) {
                    $model->addError('passwordAppNewUlang', 'Maaf, Password baru anda tidak cocok!');
                } else if ($model->passwordApp != md5($model->passwordAppOld)) {
                    $model->addError('passwordAppOld', 'Maaf, Anda tidak berhak mengubah password!');
                } else {
                    $model->passwordApp = md5($model->passwordAppNew);
                    if ($model->save()) {
                        return $this->redirect(['view']);
                    }
                }
            }
        } else if (Member::find()->where('memberId=:id', [':id' => $id])->count() > 0) {
            $ket = 'member';
            $model = Member::find()->where('memberId=:id', [':id' => $id])->One();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->passwordAppNew != $model->passwordAppNewUlang) {
                    $model->addError('passwordAppNewUlang', 'Maaf, Password baru anda tidak cocok!');
                } else if ($model->memberPassword != md5($model->passwordAppOld)) {
                    $model->addError('passwordAppOld', 'Maaf, Anda tidak berhak mengubah password!');
                } else {
                    $model->memberPassword = md5($model->passwordAppNew);
                    if ($model->save()) {
                        return $this->redirect(['view']);
                    }
                }
            }
        }
        return $this->render('updatePassword', [
                    'ket' => $ket,
                    'model' => $model,
        ]);
    }

    /**
     * Displays a single AppUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView() {
        $id = Yii::$app->user->identity->userId;
        if (AppUser::find()->where('idUser=:id', [':id' => $id])->count() > 0) {
            $model = $this->findModel($id);
            $isView = '';
        } else if (Member::find()->where('memberId=:id', [':id' => $id])->count() > 0) {
            $model = Member::find()->where('memberId=:id', [':id' => $id])->One();
            $isView = 'member';
        }
        return $this->render('view', [
                    'model' => $model,
                    'isView' => $isView
        ]);
    }

    /**
     * Updates an existing AppUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate() {
        $id = Yii::$app->user->identity->userId;
        $conn = new DAO();

        if (AppUser::find()->where('idUser=:id', [':id' => $id])->count() > 0) {
            $ket = '';
            $model = AppUser::find()->where('idUser=:id', [':id' => $id])->one();
            if ($model->load(Yii::$app->request->post())) {
                $qUpdate = "UPDATE app_user SET nama=:nama,telp=:telp WHERE idUser=:id";
                $result = $conn->Execute($qUpdate, [
                    ':nama' => $model->nama,
                    ':telp' => $model->telp,
                    ':id' => $id
                ]);
                return $this->redirect(['view']);
            }
        } else if (Member::find()->where('memberId=:id', [':id' => $id])->count() > 0) {
            $ket = 'member';
            $model = Member::find()->where('memberId=:id', [':id' => $id])->One();
            if ($model->load(Yii::$app->request->post())) {
                $qUpdate = "UPDATE member SET memberNama=:nama,memberTelp=:telp,memberEmail=:email,memberNip=:nip "
                        . "WHERE memberId=:id";
                $result = $conn->Execute($qUpdate, [
                    ':nama' => $model->memberNama,
                    ':nip' => $model->memberNip,
                    ':email' => $model->memberEmail,
                    ':telp' => $model->memberTelp,
                    ':id' => $id
                ]);
                return $this->redirect(['view']);
            }
        }
        return $this->render('update', [
                    'ket' => $ket,
                    'model' => $model,
        ]);
    }

    /**
     * Finds the AppUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (AppUser::find()->select(['*'])->where('idUser=:id', [':id' => $id])->count() > 0) {
            $model = AppUser::find()->select(['*'])->where('idUser=:id', [':id' => $id])->one();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
