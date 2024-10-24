<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\referensi\models\RefJenisPelatihan;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefMateriPelatihan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-materi-pelatihan-form">
    <?php
    //Jenis Pelatihan
    $dataJenisPelatihan = RefJenisPelatihan::find()->all();

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'mapelNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Materi Pelatihan']],
        ]
    ]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'mapelJnslatId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataJenisPelatihan, 'jnslatId', 'jnslatNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Semua Jenis Pelatihan -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
            'mapelIsAktif' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
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
            'mapelDeskripsi' => ['type' => Form::INPUT_TEXTAREA, 'options' => ['placeholder' => 'Deskripsi Materi Pelatihan']],
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
