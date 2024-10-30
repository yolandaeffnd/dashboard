<?php

namespace app\modules\aplikasi\controllers;

use Yii;
use app\modules\aplikasi\models\AppChart;
use app\modules\aplikasi\models\AppMenu;
use app\modules\aplikasi\models\AppKategori;
use app\modules\aplikasi\models\AppMenuSearch;
use app\modules\aplikasi\models\AppAction;
use app\models\RefFakultas;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * MenuController implements the CRUD actions for AppMenu model.
 */
class ChartController extends Controller {

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
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => User::userAccessRoles('ctrlAplikasiChart')
            ]
        ];
    }

    /**
     * Lists all AppMenu models.
     * @return mixed
     */
    public function actionIndex() {
        

        // $query = AppChart::find();
        // $query->select([
        //     '*',
        //     'ref_fakultas.fakNama AS fakNama',
        // ]);
        // $query->join('JOIN', 'ref_fakultas', 'ref_fakultas.fakId=app_chart.unitId');

        $query = AppChart::find();
        $query->select([
            '*',
            // 'app_menu.labelMenu AS labelMenu',
            // 'ref_fakultas.fakNama AS fakNama',
        ]);
       
        $query->orderBy('idKategori');
        // $query->join('JOIN', 'ref_fakultas', 'ref_fakultas.fakId=app_chart.unitId');
        
        // $query->andWhere(['IN', 'app_user.idGroup', $availableGroup]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'key'=>'idChart'
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppMenu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $query = AppChart::find()->where(['idChart' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('view', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new AppMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new AppChart();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idChart]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    // 
    public function actionUpdate($id) {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->redirect(['view', 'id' => $model->idChart]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AppMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AppMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AppChart::findOne(['idChart'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
