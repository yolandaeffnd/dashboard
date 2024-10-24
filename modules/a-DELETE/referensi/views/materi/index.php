<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\MateriPelatihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Materi Pelatihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-materi-pelatihan-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_index', ['dataProvider' => $dataProvider]); ?>
</div>
