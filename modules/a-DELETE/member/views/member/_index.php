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
    'id' => 'pjax-member',
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
            'attribute' => 'memberNama',
            'value' => function ($data) {
                return $data->memberNama;
            }
        ],
            [
            'attribute' => 'memberJenkel',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->memberJenkel;
            }
        ],
        'memberEmail:email',
        'memberTelp',
            [
            'attribute' => 'memberMemberKatId',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->memberMemberKat->memberKatNama;
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Member',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>