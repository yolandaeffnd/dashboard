<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\DAO;
use app\models\Member;
use app\models\IndonesiaDate;
use app\modules\aplikasi\models\AppChart;
use yii\data\ActiveDataProvider;

class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','getfoto','gettemplate'],
                'rules' => [
                    [
                        'actions' => ['logout','getfoto','gettemplate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        
        if (!Yii::$app->user->isGuest) {
            return $this->render('indexDashboard', [
            ]);
        } else {
            //return $this->render('index', []);
            return $this->redirect(['view']);
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
//        return $this->goHome();
        $ps_chart = "0";
        $query = AppChart::find()->where(['posisiChart'=>$ps_chart]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }
    
    public function actionImage($filename){
        $file = \Yii::getAlias('@webroot/images/' . $filename);
        return \Yii::$app->response->sendFile($file, NULL, ['inline' => true]);
    }
    
    public function actionGetfoto($filename) {
        $file = \Yii::getAlias('@webroot/../../berkas/photos/' . $filename);
        return \Yii::$app->response->sendFile($file, NULL, ['inline' => true]);
    }
    
    public function actionGettemplate($filename) {
        $file = \Yii::getAlias('@webroot/../berkas/berkas-template/' . $filename);
        return \Yii::$app->response->sendFile($file, NULL, ['inline' => true]);
    }


    public function actionView($id='') {
        $this->layout='//main-fo';
       
        $nama_fakultas = 'Universitas Andalas';
        $nama_chart = 'Mahasiswa';

        if(!empty($id)){
            $query = AppChart::find()->where(['idChart' => $id])->one();
            $data = [
                'url_cart' => $query->url_chart,
                'judul' => $query->nama_chart,
            ];
            
        }else{
            $query = AppChart::find()->where(['posisiChart' => '0','nama_chart'=>'Home'])->one();
            $data=[
                'judul'=> $nama_fakultas,
                'url_cart'=>empty($query->url_chart)?null:$query->url_chart,
            ];
        }
       
        // $query = AppChart::find()->where(['unitId' => $id_fakultas])->andWhere(['nama_chart' => $nama_chart]);
        // $query = AppChart::find()->where(['idChart' => $id])->one();
            // $data = [
                // 'url_cart' => $query->url_chart,
                // 'judul' =>  $nama_fakultas,
            // ];

            return $this->render('view',$data);
       
    }

    



}
