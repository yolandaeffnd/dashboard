<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'App Actions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-action-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create App Action', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idAction',
            'idMenu',
            'actionFn',
            'actionDesk',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
