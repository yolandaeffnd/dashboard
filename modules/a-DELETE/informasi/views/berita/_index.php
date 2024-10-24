<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pelatihan\models\LatKelasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-berita',
    'enablePushState' => false,
    'enableReplaceState' => false
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'hover' => true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
            'attribute' => 'infoJudul',
            'value' => function ($data) {
                return $data->infoJudul;
            }
        ],
            [
            'attribute' => 'infoIsPublish',
            'format' => 'raw',
            'value' => function ($data) {
                return ($data->infoIsPublish == 1) ? 'Ya' : 'Tidak';
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Berita',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>