<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\aplikasi\models\AppKategori;

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
                'attribute'=>'idKategori',
                'filter'=>true,
                'group'=>true,
                'groupedRow'=>true,
                'value'=>function($data){
                    // $test = AppKategori::findone($data->idKategori);
                    // var_dump($test);
                    return empty($data->idKategori0->idKategori)?null:$data->idKategori0->nama_kategori;
                    // return $data->nama_kategori;
                    
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
