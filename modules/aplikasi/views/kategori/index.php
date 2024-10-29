<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengaturan Kategori';
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
                    'title' => 'Tambah Kategori',
                    'class' => 'btn btn-success',
                    'onclick' => 'js:document.location="' . Url::to(['/aplikasi/kategori/create']) . '";'
                ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'=>'nama_kategori',
                'filter'=>false,
                'value' => function($data) {
                    return $data->nama_kategori; 
                }
            ],

            [
                'attribute'=>'jenis_kategori',
                'filter'=>false,
                'value' => function($data) {
                    return $data->jenis_kategori;
                }
            ],

            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Kategori',
        ],
    ]);
    ?>

</div>
