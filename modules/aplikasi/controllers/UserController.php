<?php

namespace app\modules\aplikasi\controllers;

use Yii;
use app\modules\aplikasi\models\AppUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\aplikasi\models\RefFakultas;
use app\models\DAO;
use yii\filters\AccessControl;
use app\models\User;
use app\modules\aplikasi\models\AppGroupView;
use yii\db\Query;
use app\models\IndonesiaDate;

/**
 * UserController implements the CRUD actions for AppUser model.
 */
class UserController extends Controller {

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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'userdata', 'getdatagroup'],
                'rules' => [
                    User::userAccessRoles2('ctrlUser'),
                    [
                        'allow' => true,
                        'actions' => ['getdatagroup'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionGetdatagroup() {
        if (\Yii::$app->request->isAjax) {
            $id = $_GET['id'];
            $data = (new Query())
                    ->select(['*'])
                    ->from('app_group')
                    ->where('idGroup=:id', [':id' => $id])
                    ->one();
            return $data['ketGroup'];
        } else {
            return $this->goHome();
        }
    }

    public function actionUserdata($id) {
        $conn = new DAO();
        $transaksi = $conn->beginTransaction();
        try {
            if (isset($_POST['pilihanUnit'])) {
                //Unit
                $qDelUserData = "DELETE FROM app_user_data WHERE idUser=:id";
                $conn->Execute($qDelUserData, [':id' => $id]);
                $paramsUnit = [];
                if (isset($_POST['pilihanUnit'])) {
                    foreach ($_POST['pilihanUnit'] as $a => $val) {
                        $paramsUnit[] = [$id, $val];
                    }
                    $conn->BatchInsert('app_user_data', ['idUser', 'unitId'], $paramsUnit);
                }
                $transaksi->commit();
            } else {
                //Unit
                $qDelUserData = "DELETE FROM app_user_data WHERE idUser=:id";
                $conn->Execute($qDelUserData, [':id' => $id]);

                $transaksi->commit();
            }
        } catch (ErrorException $e) {
            $transaksi->rollBack();
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Lists all AppUser models.
     * @return mixed
     */
    public function actionIndex() {
        $availableGroup = [];
        foreach (AppGroupView::find()->where(['idGroup' => Yii::$app->user->identity->userGroupId])->each() as $val) {
            $availableGroup[] = $val->idGroupView;
        }
        $query = AppUser::find();
        $query->select([
            '*',
            'app_group.namaGroup AS namaGroup',
        ]);
        $query->join('JOIN', 'app_group', 'app_group.idGroup=app_user.idGroup');
        $query->andWhere(['IN', 'app_user.idGroup', $availableGroup]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        //Akses Group
        $queryUnit = RefFakultas::find();
        $queryUnit->select(['*',
            '(SELECT GROUP_CONCAT(unitId) FROM app_user_data WHERE idUser=:id)AS arrUserData'
        ]);
        $queryUnit->params([':id' => $id]);
        $dataProviderData = new ActiveDataProvider([
            'query' => $queryUnit,
            'pagination' => [
                'pageSize' => 500
            ]
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProviderData' => $dataProviderData
        ]);
    }

    /**
     * Creates a new AppUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $conn = new DAO;
        $model = new AppUser();
        $inDate = new IndonesiaDate();
        $model->tglEntri = $inDate->getNow();
        if ($model->load(Yii::$app->request->post())) {
            $qCek = "SELECT * FROM app_user WHERE usernameApp=:username";
            $rsCek = $conn->QueryAll($qCek, [':username' => $model->usernameApp]);
            if (empty($rsCek)) {
                $model->passwordApp = md5($model->passwordApp);
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->idUser]);
                }
            } else {
                $model->addError('usernameApp', 'Maaf, Username sudah digunakan pengguna lain!');
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing AppUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $conn = new DAO;
        $model = $this->findModel($id);
        $passAwal = $model->passwordApp;
        if ($model->load(Yii::$app->request->post())) {
            $qCek = "SELECT * FROM app_user WHERE usernameApp=:username AND idUser<>:id";
            $rsCek = $conn->QueryAll($qCek, [':username' => $model->usernameApp, ':id' => $model->idUser]);
            if (empty($rsCek)) {
                if ($model->passwordApp != $passAwal) {
                    $model->passwordApp = md5($model->passwordApp);
                }
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->idUser]);
                }
            } else {
                $model->addError('usernameApp', 'Maaf, Username sudah digunakan pengguna lain!');
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AppUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AppUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AppUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
