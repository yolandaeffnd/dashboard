<?php

namespace app\modules\report\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use app\models\DAO;

/**
 * Default controller for the `report` module
 */
class DefaultController extends Controller {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $conn = new DAO();
        $query = (new Query())
                ->from('fakultas')
                ->all(Yii::$app->dbSireg);
        //$fakultas = $conn->dbMultiQueryAll(\Yii::$app->dbSireg, $query);
        echo '<pre>';
        print_r($query);
        echo '</pre>';
        
        return $this->render('index');
    }

}
