<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefJenisBiayaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Biaya';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-jenis-biaya-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_index', ['dataProvider' => $dataProvider]); ?>
</div>
