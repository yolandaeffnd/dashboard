<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengaturan Group';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-group-index">

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
                    'title' => 'Tambah Group',
                    'class' => 'btn btn-success',
                    'onclick' => 'js:document.location="' . Url::to(['/aplikasi/group/create']) . '";'
                ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'namaGroup',
            'ketGroup',
            [
                'attribute'=>'isMemberGroup',
                'value'=>function($data){
                    return ($data->isMemberGroup=='1')?'Ya, Front End':(($data->isMemberGroup=='2')?'Ya, Back End':'Tidak');
                }
            ],
            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Group',
        ],
    ]);
    ?>
</div>
