<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use app\modules\pelatihan\models\LatPeriode;
use app\modules\pelatihan\models\RefInstruktur;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lat-kelas-form">
    <?php
    //Periode
    $dataPeriode = LatPeriode::find()
            ->select(['*','jnslatNama'])
            ->join('JOIN', 'ref_jenis_pelatihan', 'ref_jenis_pelatihan.jnslatId=lat_periode.periodeJnslatId')
            ->where('periodeIsAktif="1"')->all();
    //Instruktur
    $dataInstruktur = RefInstruktur::find()->where('instIsAktif="1"')->all();

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'klsNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Kelas']],
        ]
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'klsPeriodeId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataPeriode, 'periodeId', 'periodeNama','jnslatNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Periode Pelatihan -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
            'klsInstId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataInstruktur, 'instId', 'instNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Instruktur -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ],
                ],
            ],
        ]
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 4,
        'attributes' => [
            'klsKapasitas' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Kapasitas']],
            'klsMeetingMin' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Pertemuan Minimal']],
            'klsMeetingMax' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Pertemuan Maksimal']],
            'klsIsPublish' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ['1' => 'Ya', '0' => 'Tidak'],
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Publish -',
                    ],
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
