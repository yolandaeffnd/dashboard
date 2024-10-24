<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penandatangan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="set-ttd-index">

    <?php
    Pjax::begin([
        'id' => 'pjax-set-ttd',
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
                'attribute' => 'ttdKode',
                'value' => function ($data) {
                    return $data->ttdKode;
                }
            ],
                [
                'attribute' => 'ttdJabatan',
                'value' => function ($data) {
                    return $data->ttdJabatan;
                }
            ],
                [
                'attribute' => 'ttdNama',
                'value' => function ($data) {
                    return $data->ttdNama;
                }
            ],
                [
                'attribute' => 'ttdNip',
                'value' => function ($data) {
                    return $data->ttdNip;
                }
            ],
                [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Penandatangan',
            'after' => false,
            'before' => false
        ],
    ]);
    Pjax::end();
    ?>
</div>
