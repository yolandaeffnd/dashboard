<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefInstruktur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-instruktur-form">
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'instNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Instruktur']],
            'instEmail' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Email']],
        ]
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'attributes' => [
            'instJenkel' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ['L' => 'Laki-Laki', 'P' => 'Perempuan'],
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Jenis Kelamin -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
            'instTelp' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Telp']],
            'instIsAktif' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ['1' => 'Aktif', '0' => 'Tidak Aktif'],
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Status -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
        ]
    ]);

     echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' => '<div style="text-align: left; margin-top: 0px">' .
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
