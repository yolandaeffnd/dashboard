<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\referensi\models\RefFakultas;
use app\modules\referensi\models\RefJenjang;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefProdi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-prodi-form">
    <?php
    //Fakultas
    $dataFakultas = RefFakultas::find()->all();
    //Jenjang
    $dataJenjang = RefJenjang::find()
            ->where('jenjDeskripsi IS NOT NULL')
            ->all();

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'prodiId' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Kode']],
            'prodiNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Program Studi']],
            'prodiJenjId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataJenjang, 'jenjId', 'jenjNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Jenjang -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
            'prodiFakId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataFakultas, 'fakId', 'fakNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Fakultas -',
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
