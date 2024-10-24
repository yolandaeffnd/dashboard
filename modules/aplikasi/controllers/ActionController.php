<?php

namespace app\modules\aplikasi\controllers;

use Yii;
use app\modules\aplikasi\models\AppAction;
use app\modules\aplikasi\models\AppMenu;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * ActionController implements the CRUD actions for AppAction model.
 */
class ActionController extends Controller {

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
                'rules' => User::userAccessRoles('ctrlActionMenu')
            ]
        ];
    }

    /**
     * Lists all AppAction models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => AppAction::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppAction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AppAction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($menu) {
        $modelMenu = AppMenu::findOne($menu);
        $model = new AppAction();
        $model->idMenu = $modelMenu->idMenu;
        $model->labelMenu = $modelMenu->labelMenu;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/aplikasi/menu/view', 'id' => $menu]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppAction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $menu) {
        $model = $this->findModel($id);
        if (empty($model)) {
            return $this->redirect(['/aplikasi/menu/view', 'id' => $menu]);
        } else {
            $modelMenu = AppMenu::findOne($menu);
            $model->idMenu = $modelMenu->idMenu;
            $model->labelMenu = $modelMenu->labelMenu;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['/aplikasi/menu/view', 'id' => $menu]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing AppAction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $menu) {
        $this->findModel($id)->delete();
        
        return $this->redirect(['/aplikasi/menu/view', 'id' => $menu]);
    }

    /**
     * Finds the AppAction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppAction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AppAction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
