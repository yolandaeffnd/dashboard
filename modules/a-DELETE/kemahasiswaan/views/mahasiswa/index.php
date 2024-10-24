<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefFakultasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-index">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_index', ['dataProvider' => $dataProvider]); ?>
</div>
