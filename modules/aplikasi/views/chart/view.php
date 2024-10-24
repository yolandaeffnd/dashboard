<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\modules\aplikasi\models\AppChart;
use app\models\RefFakultas;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppUser */

$this->title = 'Detail Chart';
$this->params['breadcrumbs'][] = ['label' => 'Chart', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-chart-view">

    <?php
    if($model->unitId!=0){
        $a = RefFakultas::findOne($model->unitId);
        $namafakultas = $a->fakNama;
    }else{
        $namafakultas = "Universitas Andalas";
    }
    
    echo DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => 'Detail Chart',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'onclick' => 'document.location="' . Url::to(['update', 'id' => $model->idChart]) . '";',
        ],
        'deleteOptions' => [
            'ajaxSettings' => [
                'url' => Url::to(['/aplikasi/user/delete', 'id' => $model->idChart]),
                'success' => 'function(){document.location="' . Url::to(['/aplikasi/chart/index']) . '";}'
            ]
        ],
        'attributes' => [
            'idMenu',
            'nama_chart',
            'url_chart',
                [
                'attribute' => 'unitId',
                'value' => $namafakultas
            ],
            [
                'attribute' => 'posisiChart',
                'value' => ($model->posisiChart == 1) ? 'Di Dalam' : 'Di Luar'
            ],
            // 'posisiChart',
                
        ]
    ]);



    //Akses Data
  
    ?>

</div>
