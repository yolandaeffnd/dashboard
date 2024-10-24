<?php

namespace app\modules\kemahasiswaan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\DAO;
use yii\db\Query;
use app\models\IndonesiaDate;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class MahasiswaController extends Controller {

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
                    User::userAccessRoles2('ctrlMahasiswa'),
                ]
            ]
        ];
    }

    public function actionIndex() {
        ini_set('memory_limit', '128M');
        ini_set('innodb_buffer_pool_size', '128M');
        $dbSireg = Yii::$app->dbSireg;
        $conn = new DAO();
        $page = empty(Yii::$app->request->get('page')) ? 0 : Yii::$app->request->get('page');
        $sqlMahasiswa = (new Query())
                ->select([
                    'b.*',
                    'ps.namaProgramStudi',
                    'f.namaFakultas',
                    'jl.namaJalur',
                    'jenj.namaJenjang'
                ])
                ->from('biodata b')
                ->join('JOIN', 'program_studi ps', 'ps.idProgramStudi=b.idProgramStudi')
                ->join('JOIN', 'fakultas f', 'f.idFakultas=ps.idFak')
                ->join('JOIN', 'jenjang jenj', 'jenj.idJenjang=ps.idJenjang')
                ->join('JOIN', 'jalur jl', 'jl.idJalur=b.idJalur')
                ->where('angkatan>(YEAR(NOW())-10)')
                ->all($dbSireg);
        //$jmlData = $sqlMahasiswa->count('*', $dbSireg);
        //$dataProvider = $sqlMahasiswa->all($dbSireg);
        //Cara Lain
        $query = "SELECT * 
                FROM biodata b
                JOIN program_studi ps ON ps.idProgramStudi=b.idProgramStudi
                JOIN fakultas f ON f.idFakultas=ps.idFak
                JOIN jenjang jenj ON jenj.idJenjang=ps.idJenjang
                JOIN jalur jl ON jl.idJalur=b.idJalur
                WHERE angkatan>(YEAR(NOW())-10)";
        $sqlMahasiswa1 = $conn->dbAllQueryAll($dbSireg, $query, []);

        $dataProvider = new ArrayDataProvider([
            //'allModels' => $sqlMahasiswa,
            'allModels' => $sqlMahasiswa,
            'pagination' => [
//                'totalCount' =>$jmlData,
                'pageSize' => 20,
//                'page' => Yii::$app->request->get('page') - 1,
//                'params' => [
//                    'nim' => $modelSearch->mhsNim,
//                    'nama' => $modelSearch->mhsNama,
//                    'keluar' => $modelSearch->mhsKeluar
//                ]
            ]
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider
        ]);
    }

}
