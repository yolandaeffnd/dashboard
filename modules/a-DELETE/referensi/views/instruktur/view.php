<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefInstruktur */

$this->title = $model->instId;
$this->params['breadcrumbs'][] = ['label' => 'Ref Instrukturs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-instruktur-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->instId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->instId], [
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
            'instId',
            'instNama',
            'instJenkel',
            'instTelp',
            'instEmail:email',
            'instIsAktif',
            'instCreate',
            'instUpdate',
        ],
    ]) ?>

</div>
