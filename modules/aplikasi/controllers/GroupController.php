<?php

namespace app\modules\aplikasi\controllers;

use Yii;
use app\modules\aplikasi\models\AppGroup;
use app\modules\aplikasi\models\AppMenu;
use app\modules\aplikasi\models\AppGroupMenu;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\DAO;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;

/**
 * GroupController implements the CRUD actions for AppGroup model.
 */
class GroupController extends Controller {

    public $arr;

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
                'only' => ['index', 'create', 'update', 'delete', 'akses', 'view'],
                'rules' => User::userAccessRoles('ctrlModulGroup')
            ]
        ];
    }

    public function actionAkses($id) {
        $conn = new DAO();
        $transaksi = $conn->beginTransaction();
        try {
            if (isset($_POST['pilihan']) || isset($_POST['pilihanGroup'])) {
                //Menu
                $qDel = "DELETE FROM app_group_menu WHERE idGroup=:group";
                $conn->Execute($qDel, [':group' => $id]);
                $params = [];
                if (isset($_POST['pilihan'])) {
                    foreach ($_POST['pilihan'] as $a => $val) {
                        $ex = explode('.', $val);
                        $menu = $ex[0];
                        $aksi = isset($ex[1]) ? $ex[1] : '';
                        $params[] = [$menu, $id, $aksi];
                    }
                    $conn->BatchInsert('app_group_menu', ['idMenu', 'idGroup', 'actionFn'], $params);
                }
                //Group
                $qDelGroup = "DELETE FROM app_group_view WHERE idGroup=:group";
                $conn->Execute($qDelGroup, [':group' => $id]);
                $paramsGroup = [];
                if (isset($_POST['pilihanGroup'])) {
                    foreach ($_POST['pilihanGroup'] as $a => $val) {
                        $paramsGroup[] = [$id, $val];
                    }
                    $conn->BatchInsert('app_group_view', ['idGroup', 'idGroupView'], $paramsGroup);
                }
                $transaksi->commit();
            } else {
                //Menu
                $qDel = "DELETE FROM app_group_menu WHERE idGroup=:group";
                $conn->Execute($qDel, [':group' => $id]);
                //Group
                $qDel = "DELETE FROM app_group_menu WHERE idGroup=:group";
                $conn->Execute($qDel, [':group' => $id]);

                $transaksi->commit();
            }
        } catch (ErrorException $e) {
            $transaksi->rollBack();
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Lists all AppGroup models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => AppGroup::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        //Akses Menu 
        $queryMenu = AppMenu::find();
        $queryMenu->select([
            'app_action.idMenu',
            'labelMenu',
            'actionDesk', 'actionFn',
            '(SELECT idGroup FROM app_group WHERE idGroup=:group LIMIT 1)AS idGroup',
            'IFNULL(CONCAT(app_menu.idMenu,".",actionFn),app_menu.idMenu) AS idAction',
            'CONCAT(if(parentId=0,app_menu.idMenu,CONCAT(parentId,".",app_menu.idMenu))) AS kode',
            'if(parentId=0,app_menu.idMenu,app_menu.parentId)AS urut'
        ]);
        $queryMenu->join = [['LEFT JOIN', 'app_action', 'app_action.idMenu=app_menu.idMenu']];
        $queryMenu->params([':group' => $id]);
        $queryMenu->groupBy(['app_menu.idMenu', 'idAction']);
        $queryMenu->orderBy('urut ASC,app_menu.idMenu');
        $dataProvider = new ActiveDataProvider([
            'query' => $queryMenu,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        //Akses Group
        $queryGroup = AppGroup::find();
        $queryGroup->select(['*',
            '(SELECT GROUP_CONCAT(idGroupView) FROM app_group_view WHERE idGroup=:group)AS arrGroupView'
        ]);
        $queryGroup->params([':group' => $id]);
        $dataProviderGroup = new ActiveDataProvider([
            'query' => $queryGroup,
            'pagination' => [
                'pageSize' => 500
            ]
        ]);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider,
                    'dataProviderGroup' => $dataProviderGroup,
        ]);
    }

    /**
     * Creates a new AppGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new AppGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idGroup]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idGroup]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AppGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
            $this->findModel($id)->delete();
        } catch (yii\db\IntegrityException $e) {
            if ($e->errorInfo[1] == 1451) {
                $msg = "Data tersebut digunakan data lain";
            }
        }         
        return $this->redirect(['index','#'=>'status']);
    }

    /**
     * Finds the AppGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AppGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
