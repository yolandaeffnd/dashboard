<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeriode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lat-periode-form">
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'infoJudul' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Judul Berita']],
            'infoIsi' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => CKEditor::className(),'options'=>['preset'=>'advance']],
            'infoIsPublish' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ['1' => 'Ya', '0' => 'Tidak'],
                    'size' => Select2:: MEDIUM,
                    'pluginOptions' => [
                        'allowClear' => false,
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
