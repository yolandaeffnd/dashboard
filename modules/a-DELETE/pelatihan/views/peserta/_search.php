<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use app\modules\pelatihan\models\LatPeriode;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lat-peserta-search" style="margin-bottom: -15px;">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b>Pencarian</b></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <?php
            //Periode
            $dataPeriode = LatPeriode::find()
                    ->select(['*','jnslatNama'])
            ->join('JOIN', 'ref_jenis_pelatihan', 'ref_jenis_pelatihan.jnslatId=lat_periode.periodeJnslatId')
                    ->where('periodeIsAktif="1"')->all();

            $form = ActiveForm::begin([
                        'id' => 'frm-cari-peserta-pelatihan',
                        'type' => ActiveForm::TYPE_VERTICAL
            ]);

            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 2,
                'attributes' => [
                    'klsNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Kelas Pelatihan']],
                    'klsPeriodeId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                            'data' => ArrayHelper::map($dataPeriode, 'periodeId', 'periodeNama','jnslatNama'),
                            'size' => Select2:: MEDIUM,
                            'options' => [
                                'placeholder' => '- Semua Periode Pelatihan -',
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
                        Html::submitButton(' Tampilkan', ['type' => 'button', 'class' => 'fa fa-search btn btn-primary']) .
//                        Html::a(' Tambah', Url::to(['create']), ['class' => 'btn btn-success fa fa-plus', 'style' => 'margin-left:5px;']) .
                        '</div>'
                    ],
                ]
            ]);

            ActiveForm::end();
            ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
