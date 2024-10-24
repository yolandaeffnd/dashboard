<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeserta */

$this->title = 'Update Lat Peserta: ' . $model->pesertaId;
$this->params['breadcrumbs'][] = ['label' => 'Lat Pesertas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pesertaId, 'url' => ['view', 'id' => $model->pesertaId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lat-peserta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
