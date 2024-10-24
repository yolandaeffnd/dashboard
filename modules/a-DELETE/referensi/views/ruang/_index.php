<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefRuangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-ruang',
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
            'attribute' => 'ruangKode',
            'value' => function ($data) {
                return $data->ruangKode;
            }
        ],
            [
            'attribute' => 'ruangNama',
            'value' => function ($data) {
                return $data->ruangNama;
            }
        ],
            [
            'attribute' => 'runagKapasitas',
            'hAlign' => 'center',
            'value' => function ($data) {
                return $data->runagKapasitas;
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Ruang',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>