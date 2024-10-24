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

$this->title = 'Detail Broadcast';
$this->params['breadcrumbs'][] = ['label' => 'Broadcast', 'url' => ['index']];
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
            'heading' => 'Detail Broadcast',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'hidden' => true,
        ],
        'deleteOptions' => [
            'hidden' => true,
        ],
        'attributes' => [
                [
                'attribute' => 'bcTo',
                'value' => $model->bcTo
            ],
                [
                'attribute' => 'bcJudul',
                'value' => $model->bcJudul
            ],
                [
                'attribute' => 'bcIsi',
                'group' => true,
                'format' => 'raw',
                'label' => Html::beginTag('div', ['style' => 'font-weight:normal;']) . $model->bcIsi . Html::endTag('div')
            ],
                [
                'attribute' => 'bcCreate',
                'value' => $inDate->setDateTime($model->bcCreate)
            ],
        ]
    ]);

    echo Html::a(' Kembali', Url::to(['index']), ['class' => 'fa fa-reply btn btn-default', 'style' => 'margin-top:-25px;margin-right:5px;']);
    ?>
</div>
