<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\maping\models\SiregJalur;

/* @var $this yii\web\View */
/* @var $model app\modules\maping\models\Jalur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jalur-form">
    <?php
    $dataSiregJalur = SiregJalur::find()->all();
    
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'namaJalur' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Jalur']],
            'jalurMap'=>['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataSiregJalur, 'idJalur', 'namaJalur'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Jalur Pada Sireg -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ],
                ],
            ]
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default btn-flat', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary btn-flat']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
