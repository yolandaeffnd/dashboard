<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefInstrukturSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bank';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-bank-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_index', ['dataProvider' => $dataProvider]); ?>
</div>