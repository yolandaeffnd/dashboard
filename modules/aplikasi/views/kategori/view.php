<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\modules\aplikasi\models\AppKategori;
use app\models\RefFakultas;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppUser */

$this->title = 'Detail Kategori';
$this->params['breadcrumbs'][] = ['label' => 'Kategori', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-kategori-view">

    <?php
    
    echo DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => 'Detail Kategori',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'onclick' => 'document.location="' . Url::to(['update', 'id' => $model->idKategori]) . '";',
        ],
        'deleteOptions' => [
            'ajaxSettings' => [
                'url' => Url::to(['/aplikasi/kategori/delete', 'id' => $model->idKategori]),
                'success' => 'function(){document.location="' . Url::to(['/aplikasi/kategori/index']) . '";}'
            ]
        ],
        'attributes' => [
            'idKategori',
            'nama_kategori',
            'jenis_kategori',                
        ]
    ]);


  
    ?>

</div>
