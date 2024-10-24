<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefMateriPelatihan */

$this->title = $model->mapelId;
$this->params['breadcrumbs'][] = ['label' => 'Ref Materi Pelatihans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-materi-pelatihan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mapelId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mapelId], [
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
            'mapelId',
            'mapelJnslatId',
            'mapelNama',
            'mapelDeskripsi:ntext',
            'mapelIsAktif',
            'mapelCreate',
            'mapelUpdate',
        ],
    ]) ?>

</div>
