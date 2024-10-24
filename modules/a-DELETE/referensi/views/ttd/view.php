<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\SetTtd */

$this->title = $model->ttdId;
$this->params['breadcrumbs'][] = ['label' => 'Set Ttds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="set-ttd-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ttdId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ttdId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ttdId',
            'ttdKode',
            'ttdJabatan',
            'ttdNama',
            'ttdNip',
            'ttdPosisi',
            'ttdLastUpdate',
        ],
    ]) ?>

</div>
