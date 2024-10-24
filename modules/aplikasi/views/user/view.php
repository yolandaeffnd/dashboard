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

    $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_VERTICAL,
                'action' => Url::to(['userdata', 'id' => $model->idUser])
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' => '<div style="text-align: left; margin-top: 0px;margin-bottom:5px;">' .
                Html::resetButton(' Selesai', ['class' => 'fa fa-check-circle-o btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    //Akses Data
    echo GridView::widget([
        'dataProvider' => $dataProviderData,
        'responsive' => true,
        'hover' => true,
        'toolbar' => [],
        'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
            'fakNama',
                [
                'class' => 'kartik\grid\CheckboxColumn',
                'name' => 'pilihanUnit',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    $arr = explode(',', $model->arrUserData);
                    if (in_array($model->fakId, $arr)) {
                        return ['checked' => true, 'value' => $model->fakId];
                    } else {
                        return ['checked' => false, 'value' => $model->fakId];
                    }
                }
            ],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Akses Data',
            'footer' => false,
            'before'=>false,
            'after'=>false
        ],
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' => '<div style="text-align: left; margin-top: 0px">' .
                Html::resetButton(' Selesai', ['class' => 'fa fa-check-circle-o btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>

</div>
