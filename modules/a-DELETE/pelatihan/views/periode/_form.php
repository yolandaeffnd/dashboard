<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use app\modules\pelatihan\models\RefJenisPelatihan;
use app\modules\pelatihan\models\RefAngkatan;
use app\modules\pelatihan\models\MemberKategori;
use app\modules\pelatihan\models\LatPeriode;
use app\modules\pelatihan\models\RefTarif;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeriode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lat-periode-form">
    <?php
    //Tarif
    $dataTarif = RefTarif::find()
            ->select(['*','CONCAT(jnsBiayaNama," : ",tarifJumlah)AS tarifNama'])
            ->join('JOIN', 'ref_jenis_biaya', 'ref_jenis_biaya.jnsBiayaId=ref_tarif.tarifJnsBiayaId')
            ->where('tarifJnslatId=:jnslat', [
                ':jnslat'=>$model->periodeJnslatId
            ])
            ->all();
    //Jenis Pelatihan
    $dataJenisPelatihan = RefJenisPelatihan::find()->all();
    //Angkatan
    $dataAngkatan = RefAngkatan::find()->all();
    //Member Kategori
    $dataMemberKat = MemberKategori::find()->all();
    //Data Periode
    $dataPeriode = LatPeriode::find()
            ->select(['*','jnslatNama'])
            ->join('JOIN', 'ref_jenis_pelatihan', 'ref_jenis_pelatihan.jnslatId=lat_periode.periodeJnslatId');
    if(!$model->isNewRecord){
        $dataPeriode->where('periodeId<>:id', [':id'=>$model->periodeId]);
    }

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'periodeNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Periode Pelatihan']],
        ]
    ]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'periodeJnslatId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataJenisPelatihan, 'jnslatId', 'jnslatNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Jenis Pelatihan -',
                        'onchange'=>'$(this).submit();'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
            'periodeIsAktif' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ['0'=>'Tidak Aktif','1'=>'Aktif'],
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Status -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => false,
                    ],
                ],
            ],
            'periodeRegAwal' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateTimePicker::className(), 'options' => [
                    'convertFormat' => true,
                    'options' => ['placeholder' => 'Awal registrasi Online'],
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd H:i',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ]
                ],
            ],
            'periodeRegAkhir' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateTimePicker::className(), 'options' => [
                    'convertFormat' => true,
                    'options' => ['placeholder' => 'Akhir Registrasi Online'],
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd H:i',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ]
                ],
            ],
            'periodeLakMulai' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DatePicker::className(), 'options' => [
                    'convertFormat' => true,
                    'options' => ['placeholder' => 'Awal Periode'],
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ]
                ],
            ],
            'periodeLakSelesai' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DatePicker::className(), 'options' => [
                    'convertFormat' => true,
                    'options' => ['placeholder' => 'Akhir Periode'],
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ]
                ],
            ],
        ]
    ]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'periodeMaxSkor'=>['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Skor Maksimal Yang Diizinkan'],'hint'=>'Pembatasan skor kepada calon peserta yang mendaftar'],
            'ruleTarif' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataTarif, 'tarifId', 'tarifNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Komponen Biaya -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => true,
                    ],
                ],
                'hint'=>'Pilih <i>Komponen Biaya Pelatihan</i> yang akan ditagihkan kepada calon peserta pelatihan ini.'
            ],
            'ruleMemberKat' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataMemberKat, 'memberKatId', 'memberKatNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Kategori Member -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => true,
                    ],
                ],
                'hint'=>'Pilih Kategori Member yang diizinkan mengikuti pelatihan ini.'
            ],
            'ruleAngkatan' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataAngkatan, 'angkatan', 'angkatanNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Tidak Ada Batasan -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ],
                ],
                'hint'=>'Pembatasan berdasarkan <i>Angkatan</i> ini berlaku jika kategori member <b>Mahasiswa Unand</b>, Tidak berpengaruh untuk kategori member yang lain.'
            ],
            'rulePeriode' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataPeriode->all(), 'periodeId', 'periodeNama','jnslatNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Tidak Ada Batasan -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ],
                ],
                'hint'=>'Jika member telah mengambil pelatihan di <i>Periode Tidak Diizinkan</i> maka periode pelatihan ini tidak muncul.'
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
                Html::submitButton(' Simpan', ['id'=>'btn-simpan','name'=>'btn-simpan','onclick'=>'$("#btn-simpan").val(1);','type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>

</div>
