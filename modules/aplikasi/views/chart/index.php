<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengaturan Chart Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-chart-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        
        'responsive' => true,
        'hover' => true,
        'toolbar' => [
            [
                'content' =>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'type' => 'button',
                    'title' => 'Tambah Chart',
                    'class' => 'btn btn-success',
                    'onclick' => 'js:document.location="' . Url::to(['/aplikasi/chart/create']) . '";'
                ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//            'idMenu',
            [
                'attribute'=>'idMenu',
                'filter'=>true,
                'group'=>true,
                'groupedRow'=>true,
                'value'=>function($data){
                    return empty($data->idMenu0->idMenu)?null:$data->idMenu0->labelMenu;
                }
            ],
            [
                'attribute'=>'nama_chart',
                'filter'=>false,
                'value'=>function($data){
                    return $data->nama_chart;
                }
            ],
            // [
            //     'attribute'=>'url_chart',
            //     'filter'=>false,
            //     'value'=>function($data){
            //         return $data->url_chart;
            //     }
            // ],
            
            // 'isSubAction',
            [
                'attribute'=>'unitId',
                'filter'=>false,
                'value'=>function($data){
                    return empty($data->unitId)?null:$data->idUnit0->fakNama;
                }
            ],
            [
                'attribute'=>'posisiChart',
                'filter'=>false,
                'value'=>function($data){
                    return ($data->posisiChart=='1')?'Di Dalam':'Di Luar';
                }
                
            ],
            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Chart',
        ],
    ]);
    ?>

</div>
