<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppAction */

$this->title = $model->idAction;
$this->params['breadcrumbs'][] = ['label' => 'App Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-action-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idAction], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idAction], [
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
            'idAction',
            'idMenu',
            'actionFn',
            'actionDesk',
        ],
    ]) ?>

</div>
