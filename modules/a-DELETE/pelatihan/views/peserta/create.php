<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeserta */

$this->title = 'Create Lat Peserta';
$this->params['breadcrumbs'][] = ['label' => 'Lat Pesertas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-peserta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
