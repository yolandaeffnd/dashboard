<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\modules\aplikasi\models\AppGroup;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppUser */

$this->title = 'Detail Pengguna';
$this->params['breadcrumbs'][] = ['label' => 'Pengguna', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-user-view">

    <?php
    $a = AppGroup::findOne($model->idGroup);
    echo DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => 'Detail Pengguna',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'onclick' => 'document.location="' . Url::to(['update', 'id' => $model->idUser]) . '";',
        ],
        'deleteOptions' => [
            'ajaxSettings' => [
                'url' => Url::to(['/aplikasi/user/delete', 'id' => $model->idUser]),
                'success' => 'function(){document.location="' . Url::to(['/aplikasi/user/index']) . '";}'
            ]
        ],
        'attributes' => [
            'nama',
            'telp',
            'usernameApp',
            [
                'attribute' => 'namaGroup',
                'value' => $a->namaGroup
            ],
            [
                'attribute' => 'isAktif',
                'value' => ($model->isAktif == 1) ? 'Ya' : 'Tidak'
            ],
            'tglEntri'
        ]
    ]);
    echo Html::resetButton(' Kembali', ['class' => 'fa fa-mail-reply btn btn-default','style'=>'margin-top:-15px;', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']);
    ?>
    
</div>
