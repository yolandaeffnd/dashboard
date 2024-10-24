<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengaturan Menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-menu-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive' => true,
        'hover' => true,
        'toolbar' => [
            [
                'content' =>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'type' => 'button',
                    'title' => 'Tambah Menu',
                    'class' => 'btn btn-success',
                    'onclick' => 'js:document.location="' . Url::to(['/aplikasi/menu/create']) . '";'
                ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//            'idMenu',
            [
                'attribute'=>'kode',
                'filter'=>false,
                'value'=>function($data){
                    return $data->kode;
                }
            ],
            [
                'attribute'=>'labelMenu',
                'filter'=>true,
                'value'=>function($data){
                    return $data->labelMenu;
                }
            ],
            [
                'attribute'=>'urlModule',
                'filter'=>false,
                'value'=>function($data){
                    return $data->urlModule;
                }
            ],
            [
                'attribute'=>'controllerName',
                'filter'=>false,
                'value'=>function($data){
                    return $data->controllerName;
                }
            ],
            [
                'attribute'=>'isAktif',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ['1'=>'Ya','0'=>'Tidak'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>'Status'],
                'format'=>'raw',
//                'group'=>true,
                'width'=>'30px',
                'value'=>function($data){
                    return ($data->isAktif=='1')?'Ya':'Tidak';
                }
            ],
            // 'isSubAction',
            [
                'attribute'=>'noUrut',
                'filter'=>false,
                'value'=>function($data){
                    return $data->noUrut;
                }
            ],
            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Menu',
        ],
    ]);
    ?>

</div>
