<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\MateriPelatihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-materi-pelatihan',
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
            'attribute' => 'mapelNama',
            'value' => function ($data) {
                return $data->mapelNama;
            }
        ],
            [
            'attribute' => 'mapelJnslatId',
            'value' => function ($data) {
                return $data->mapelJnslat->jnslatNama;
            }
        ],
        'mapelDeskripsi:ntext',
            [
            'attribute' => 'mapelIsAktif',
            'value' => function ($data) {
                return ($data->mapelIsAktif == 1) ? 'Aktif' : 'Tidak Aktif';
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Materi Pelatihan',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>
