<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pelatihan\models\LatPeriodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-jenis-pelatihan',
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
            'attribute' => 'periodeNama',
            'value' => function ($data) {
                return $data->periodeNama;
            }
        ],
            [
            'attribute' => 'periodeJnslatId',
            'group' => true,
            'value' => function ($data) {
                return $data->periodeJnslat->jnslatNama;
            }
        ],
            [
            'attribute' => 'periodeRegAwal',
            'label' => 'Pendaftaran Online',
            'format' => 'raw',
            'hAlign' => 'center',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDateTime($data->periodeRegAwal) . '<br/> s/d <br/>' . $inDate->setDateTime($data->periodeRegAkhir);
            }
        ],
            [
            'attribute' => 'periodeLakMulai',
            'label' => 'Masa Periode',
            'format' => 'raw',
            'hAlign' => 'center',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data->periodeLakMulai) . '<br/> s/d <br/>' . $inDate->setDate($data->periodeLakSelesai);
            }
        ],
            [
            'attribute' => 'periodeIsAktif',
            'format' => 'raw',
            'hAlign' => 'center',
            'value' => function ($data) {
                return ($data->periodeIsAktif == 1) ? '<b>Aktif</b>' : '<b><i>Tidak Aktif</i></b>';
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Periode Pelatihan',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>