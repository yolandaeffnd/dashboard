<?php

namespace app\modules\maping\controllers;

use Yii;
use app\modules\maping\models\Jalur;
use app\modules\maping\models\JalurSearch;
use app\modules\maping\models\JalurMap;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;

/**
 * JalurController implements the CRUD actions for Jalur model.
 */
class JalurController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['POST', 'GET'],
                    'create' => ['POST', 'GET'],
                    'update' => ['POST', 'GET'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlMapingJalur'),
                ]
            ]
        ];
    }

    /**
     * Lists all Jalur models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new JalurSearch();
        $searchModel->load(\Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Jalur model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Jalur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Jalur();
        $conn = new DAO();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(0)) {
                $jml = count($model->jalurMap);
                $jmlEx = 0;
                for ($i = 0; $i < $jml; $i++) {
                    $model->jalurMap[$i];
                    $query = "INSERT INTO ref_jalur_map(idJalur,mapIdJalur) "
                            . "VALUE(:jalur,:map)";
                    $result = $conn->Execute($query, [
                        ':jalur' => $model->primaryKey,
                        ':map' => $model->jalurMap[$i]
                    ]);
                    if ($result == 1) {
                        $jmlEx = $jmlEx + 1;
                    }
                }
                if ($jml == $jmlEx) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Jalur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $conn = new DAO();
        $model = $this->findModel($id);
        $mapJalur = JalurMap::find()
                ->where('idJalur=:id', [':id' => $id])
                ->each();
        foreach ($mapJalur as $val) {
            $model->jalurMap[] = $val['mapIdJalur'];
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(0)) {
                if (!empty($mapJalur)) {
                    $conn->Execute('DELETE FROM ref_jalur_map WHERE idJalur=:id', [':id' => $id]);
                }
                if (!empty($model->jalurMap)) {
                    $jml = count($model->jalurMap);
                    $jmlEx = 0;
                    for ($i = 0; $i < $jml; $i++) {
                        $model->jalurMap[$i];
                        $query = "INSERT INTO ref_jalur_map(idJalur,mapIdJalur) "
                                . "VALUE(:jalur,:map)";
                        $result = $conn->Execute($query, [
                            ':jalur' => $model->primaryKey,
                            ':map' => $model->jalurMap[$i]
                        ]);
                        if ($result == 1) {
                            $jmlEx = $jmlEx + 1;
                        }
                    }

                    if ($jml == $jmlEx) {
                        return $this->redirect(['index']);
                    }
                } else {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Jalur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Jalur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jalur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Jalur::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
