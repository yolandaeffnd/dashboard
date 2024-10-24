<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefJenisPelatihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Pelatihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-jenis-pelatihan-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_index', ['dataProvider' => $dataProvider]); ?>
</div>