<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use app\modules\member\models\RefFakultas;
use app\modules\member\models\RefProdi;
use app\modules\member\models\Member;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeriode */

$this->title = 'Detail Berita';
$this->params['breadcrumbs'][] = ['label' => 'Berita', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-periode-view">
    <?php
    echo DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => 'Detail Berita',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'hidden' => true,
        ],
        'deleteOptions' => [
            'hidden' => true,
        ],
        'attributes' => [
            'infoJudul',
                [
                'attribute' => 'infoAlias',
                'label' => 'Alias',
                'value' => $model->infoAlias
            ],
                [
                'attribute' => 'infoIsi',
                'group' => true,
                'format' => 'raw',
                'label' => Html::beginTag('div',['style'=>'font-weight:normal;']).$model->infoIsi.Html::endTag('div')
            ],
                [
                'attribute' => 'infoIsPublish',
                'value' => ($model->infoIsPublish == 1) ? 'Ya' : 'Tidak'
            ],
                [
                'attribute' => 'infoCreate',
                'value' => $inDate->setDateTime($model->infoCreate)
            ],
                [
                'attribute' => 'infoUpdate',
                'value' => $inDate->setDateTime($model->infoUpdate)
            ],
        ]
    ]);

    echo Html::a(' Kembali', Url::to(['index']), ['class' => 'fa fa-reply btn btn-default', 'style' => 'margin-top:-25px;margin-right:5px;']);
    echo Html::a(' Ubah', Url::to(['update', 'id' => $model->infoId]), ['class' => 'fa fa-edit btn btn-primary', 'style' => 'margin-top:-25px;']);
    ?>
</div>
