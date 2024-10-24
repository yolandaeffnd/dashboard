<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\informasi\models\Broadcast */

$this->title = 'Update Broadcast: ' . $model->bcId;
$this->params['breadcrumbs'][] = ['label' => 'Broadcasts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bcId, 'url' => ['view', 'id' => $model->bcId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="broadcast-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
