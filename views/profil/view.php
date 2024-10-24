<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = 'Profil';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-user-view">

    <?php
    if ($isView == 'member') {
        echo DetailView::widget([
            'model' => $model,
            'condensed' => true,
            'hover' => true,
            'mode' => DetailView::MODE_VIEW,
            'panel' => [
                'heading' => 'Profil',
                'type' => DetailView::TYPE_SUCCESS,
            ],
            'updateOptions' => [
//            'label'=>'[ Ubah Password ]',
                'hidden' => true,
//                'onclick' => 'document.location="' . Url::to(['update']) . '";',
            ],
            'deleteOptions' => [
                'hidden' => true,
            ],
            'attributes' => [
                [
                    'attribute' => 'memberFoto',
                    'label'=>'',
                    'format'=>'raw',
                    'value' => Html::img(AppAsset::register($this)->baseUrl . (empty($model->memberFoto)?'/images/nobody.png':'/photos/' . $model->memberFoto), ['style' => 'width:150px;height:190px;border-radius:5px;'])
                ],
                'memberNama',
                'memberNip',
                'memberEmail',
                'memberTelp',
                'memberJabatan',
                [
                    'attribute' => 'memberUnitId',
                    'value' => $model->memberUnit->unitNama
                ],
                [
                    'attribute' => 'memberStatus',
                    'value' => ($model->memberStatus == 1) ? 'Aktif' : 'Tidak Aktif'
                ],
                'memberCreate'
            ]
        ]);
    } else {
        echo DetailView::widget([
            'model' => $model,
            'condensed' => true,
            'hover' => true,
            'mode' => DetailView::MODE_VIEW,
            'panel' => [
                'heading' => 'Profil',
                'type' => DetailView::TYPE_SUCCESS,
            ],
            'updateOptions' => [
//            'label'=>'[ Ubah Password ]',
                'hidden' => true,
//                'onclick' => 'document.location="' . Url::to(['update']) . '";',
            ],
            'deleteOptions' => [
                'hidden' => true,
            ],
            'attributes' => [
                'nama',
                'telp',
                'usernameApp',
                [
                    'attribute' => 'isAktif',
                    'value' => ($model->isAktif == 1) ? 'Aktif' : 'Tidak Aktif'
                ],
                'tglEntri'
            ]
        ]);
    }
    echo Html::resetButton(' Ubah Data', ['class' => 'fa fa-pencil btn btn-primary', 'style' => 'margin-top:-15px;margin-right:5px;', 'onclick' => 'js:document.location="' . Url::to(['update']) . '";']);
    echo Html::resetButton(' Ganti Password', ['class' => 'fa fa-lock btn btn-primary', 'style' => 'margin-top:-15px;margin-right:5px;', 'onclick' => 'js:document.location="' . Url::to(['gantipassword']) . '";']);
    if ($isView == 'member') {
        echo Html::resetButton(' Ganti Foto Profil', ['class' => 'fa fa-camera btn btn-primary', 'style' => 'margin-top:-15px;', 'onclick' => 'js:document.location="' . Url::to(['fotoprofil']) . '";']);
    }
    ?>     

</div>
