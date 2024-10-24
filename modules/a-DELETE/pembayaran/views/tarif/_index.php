<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\components\Terbilang;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pembayaran\models\RefTarifSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


Pjax::begin([
    'id' => 'pjax-bank',
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
            'attribute' => 'tarifJnslatId',
            'group' => true,
            'value' => function ($data) {
                return $data->tarifJnslat->jnslatNama;
            }
        ],
            [
            'attribute' => 'tarifJnsBiayaId',
            'value' => function ($data) {
                return $data->tarifJnsBiaya->jnsBiayaNama;
            }
        ],
            [
            'attribute' => 'tarifBankId',
            'group' => true,
            'value' => function ($data) {
                return $data->tarifBank->bankNama;
            }
        ],
            [
            'attribute' => 'tarifJumlah',
            'hAlign' => 'right',
            'value' => function ($data) {
                $terbilang = new Terbilang();
                return $terbilang->setCurrency($data->tarifJumlah);
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Tarif Pelatihan',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>