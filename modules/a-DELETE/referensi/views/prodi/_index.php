<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefProdiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-prodi',
    'enablePushState' => false,
    'enableReplaceState' => false
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'hover' => true,
    'pjax'=>true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
            'attribute' => 'prodiId',
            'width' => '40px;',
            'value' => function ($data) {
                return $data->prodiId;
            }
        ],
            [
            'attribute' => 'prodiNama',
            'value' => function ($data) {
                return $data->prodiNama;
            }
        ],
                [
            'attribute' => 'prodiJenjId',
            'value' => function ($data) {
                return $data->prodiJenj->jenjNama;
            }
        ],
            [
            'attribute' => 'prodiFakId',
            'value' => function ($data) {
                return $data->prodiFak->fakNama;
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Program Studi',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>