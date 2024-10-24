<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pelatihan\models\LatJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-jadwal-pelatihan',
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
            'attribute' => 'jdwlKlsId',
            'group' => true,
            'value' => function ($data) {
                return $data->jdwlKls->klsNama;
            }
        ],
            [
            'attribute' => 'jdwlRuangId',
            'value' => function ($data) {
                return $data->jdwlRuang->ruangNama;
            }
        ],
            [
            'attribute' => 'jdwlHariKode',
            'hAlign' => 'center',
            'group' => true,
            'value' => function ($data) {
                return $data->jdwlHariKode0->hariInd;
            }
        ],
            [
            'attribute' => 'jdwlJamMulai',
            'hAlign' => 'center',
            'value' => function ($data) {
                return $data->jdwlJamMulai;
            }
        ],
            [
            'attribute' => 'jdwlJamSelesai',
            'format' => 'raw',
            'hAlign' => 'center',
            'value' => function ($data) {
                return $data->jdwlJamSelesai;
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Jadwal Pelatihan',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>