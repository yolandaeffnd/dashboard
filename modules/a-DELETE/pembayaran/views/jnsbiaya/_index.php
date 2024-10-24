<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefJenisBiayaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


Pjax::begin([
    'id' => 'pjax-jenis-biaya',
    'enablePushState' => false,
    'enableReplaceState' => false
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'hover' => true,
    'pjax' => true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
            'attribute' => 'jnsBiayaNama',
            'value' => function ($data) {
                return $data->jnsBiayaNama;
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Jenis Biaya',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>