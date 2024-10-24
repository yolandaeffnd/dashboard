<?php

namespace app\modules\akademik\controllers;

use Yii;
use app\modules\akademik\models\Mahasiswa;
use app\modules\aplikasi\models\AppChart;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use app\models\AppUserData;
use app\models\RefFakultas;
use app\models\IndonesiaDate;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class CartController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'fakultas' => ['POST', 'GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'fakultas',
                    'mahasiswa',
                    'angkatan',
                    'ipk',
                    'lulusan',
                    'pasca',
                ],
                'rules' => [
                    User::userAccessRoles2('ctrlAkademikCart'),
                        [
                        'allow' => true,
                        'actions' => [
                            'fakultas'
                        ],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actionFakultas()
    {
        $data = [
            'url_cart' => 'https://app.powerbi.com/view?r=eyJrIjoiZGIxYjkzM2ItNjc4NS00NGQxLWI2YmMtNGY2MmEyNzlmY2FkIiwidCI6IjM0NjI3ODc0LWVkM2EtNDk3Yy04ZmI5LTE2Y2U3ZTk3NjRmMSIsImMiOjEwfQ%3D%3D&pageName=ReportSection0410d8ac84a3ef5bad76',
            'judul' => 'Seluruh Fakultas'
            
        ];

        return $this->render('cartmahasiswa',$data);
    }

    public function actionMahasiswa()
    {
        
        $id_user = Yii::$app->user->identity->userId;
        $userData = AppUserData::find()->where(['idUser' => $id_user])->all();
        if(count($userData)>1){
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            WHERE a.urlModule=:url AND b.unitId is null";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/mahasiswa');
        }else{
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            JOIN app_user_data c ON c.unitId=b.unitId
            WHERE a.urlModule=:url AND c.idUser=:userid";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/mahasiswa');
            $_rs->bindValue(':userid', $id_user);
        }
        
        
        $rs = $_rs->queryOne();

        if(!empty($rs)){
            return $this->render('cart_view', [
                'url_cart' => $rs['url_chart'],
                'judul'=> $rs['nama_chart'],
            ]);
        }else{
            return $this->render('index');
        }


       
    }


    public function actionAngkatan()
    {
        $id_user = Yii::$app->user->identity->userId;
        $userData = AppUserData::find()->where(['idUser' => $id_user])->all();
        if(count($userData)>1){
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            WHERE a.urlModule=:url AND b.unitId is null";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/angkatan');
        }else{
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            JOIN app_user_data c ON c.unitId=b.unitId
            WHERE a.urlModule=:url AND c.idUser=:userid";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/angkatan');
            $_rs->bindValue(':userid', $id_user);
        }
        
        
        $rs = $_rs->queryOne();

        if(!empty($rs)){
            return $this->render('cart_view', [
                'url_cart' => $rs['url_chart'],
                'judul'=> $rs['nama_chart'],
            ]);
        }else{
            return $this->render('index');
        }
      
    }


    public function actionIpk()
    {
        $id_user = Yii::$app->user->identity->userId;
        $userData = AppUserData::find()->where(['idUser' => $id_user])->all();
        if(count($userData)>1){
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            WHERE a.urlModule=:url AND b.unitId is null";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/ipk');
        }else{
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            JOIN app_user_data c ON c.unitId=b.unitId
            WHERE a.urlModule=:url AND c.idUser=:userid";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/ipk');
            $_rs->bindValue(':userid', $id_user);
        }
        
        
        $rs = $_rs->queryOne();

        if(!empty($rs)){
            return $this->render('cart_view', [
                'url_cart' => $rs['url_chart'],
                'judul'=> $rs['nama_chart'],
            ]);
        }else{
            return $this->render('index');
        }
      
    }

    public function actionLulusan()
    {
        $id_user = Yii::$app->user->identity->userId;
        $userData = AppUserData::find()->where(['idUser' => $id_user])->all();
        if(count($userData)>1){
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            WHERE a.urlModule=:url AND b.unitId is null";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/lulusan');
        }else{
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            JOIN app_user_data c ON c.unitId=b.unitId
            WHERE a.urlModule=:url AND c.idUser=:userid";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/lulusan');
            $_rs->bindValue(':userid', $id_user);
        }
        
        
        $rs = $_rs->queryOne();

        if(!empty($rs)){
            return $this->render('cart_view', [
                'url_cart' => $rs['url_chart'],
                'judul'=> $rs['nama_chart'],
            ]);
        }else{
            return $this->render('index');
        }
       
    }


    public function actionPasca()
    {
        $id_user = Yii::$app->user->identity->userId;
        $userData = AppUserData::find()->where(['idUser' => $id_user])->all();
        if(count($userData)>1){
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            WHERE a.urlModule=:url AND b.unitId is null";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/pasca');
        }else{
            $sql = "SELECT * 
            FROM app_menu a
            JOIN app_chart b ON b.idMenu=a.idMenu AND b.posisiChart='1'
            JOIN app_user_data c ON c.unitId=b.unitId
            WHERE a.urlModule=:url AND c.idUser=:userid";
            $_rs = Yii::$app->db->createCommand($sql);
            $_rs->bindValue(':url','/akademik/cart/pasca');
            $_rs->bindValue(':userid', $id_user);
        }
        
        
        $rs = $_rs->queryOne();

        if(!empty($rs)){
            return $this->render('cart_view', [
                'url_cart' => $rs['url_chart'],
                'judul'=> $rs['nama_chart'],
            ]);
        }else{
            return $this->render('index');
        }
       
    }


    
  

}