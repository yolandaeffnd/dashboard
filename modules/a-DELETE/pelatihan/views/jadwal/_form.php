<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use app\models\IndonesiaDate;
use app\modules\pelatihan\models\LatKelas;
use app\modules\pelatihan\models\RefRuang;
use app\modules\pelatihan\models\RefHari;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatJadwal */
/* @var $form yii\widgets\ActiveForm */

$inDate = new IndonesiaDate();
?>

<div class="lat-jadwal-form">
    <?php
    //Kelas Pelatihan
    if ($model->isNewRecord) {
        $dataKelas = LatKelas::find()
                ->join('JOIN', 'lat_periode', 'lat_periode.periodeId=lat_kelas.klsPeriodeId')
                ->where(':sekarang<=periodeLakSelesai AND klsId NOT IN(SELECT jdwlKlsId FROM lat_jadwal)', [
                    ':sekarang' => $inDate->getDate(),
                ])
                ->all();
    } else {
        $dataKelas = LatKelas::find()
                ->join('JOIN', 'lat_periode', 'lat_periode.periodeId=lat_kelas.klsPeriodeId')
                ->where('klsId=:kls', [
                    ':kls' => $model->jdwlKlsId
                ])
                ->all();
    }
    //Ruang
    $dataRuang = RefRuang::find()->all();
    //Hari
    $dataHari = RefHari::find()->orderBy('hariUrut ASC')->all();

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'jdwlKlsId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataKelas, 'klsId', 'klsNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Kelas Pelatihan -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
            'jdwlRuangId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataRuang, 'ruangId', 'ruangNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Ruang -',
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
        'columns' => 3,
        'attributes' => [
            'jdwlHariKode' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataHari, 'hariKode', 'hariInd'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Hari -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ],
                ],
            ],
            'jdwlJamMulai' => ['type' => Form::INPUT_TEXT, 'options' => ['class' => 'clockpicker', '']],
            'jdwlJamSelesai' => ['type' => Form::INPUT_TEXT, 'options' => ['class' => 'clockpicker']],
//            'jdwlJamMulai' => [
//                'type' => Form::INPUT_RAW,
//                'value' => '<label class="control-label" for="latjadwal-jdwljammulai">Jam Mulai</label>
//                        <div class="input-group clockpicker pull-center" data-placement="left" data-align="top" data-autoclose="true">
//				<input type="text" id="latjadwal-jdwljammulai" class="form-control" name="LatJadwal[jdwlJamMulai]" aria-required="true">
//				<span class="input-group-addon">
//					<span class="glyphicon glyphicon-time"></span>
//				</span>
//			</div>'
//            ],
//            'jdwlJamSelesai' => [
//                'type' => Form::INPUT_RAW,
//                'value' => '<label class="control-label" for="latjadwal-jdwljamselesai">Jam Selesai</label>
//                        <div class="input-group clockpicker pull-center" data-placement="left" data-align="top" data-autoclose="true">
//				<input type="text" id="latjadwal-jdwljamselesai" class="form-control" name="LatJadwal[jdwlJamSelesai]" aria-required="true">
//				<span class="input-group-addon">
//					<span class="glyphicon glyphicon-time"></span>
//				</span>
//			</div>'
//            ],
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
<?php
//Url
$js = <<< JS
    $('.clockpicker').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Done',
        autoclose: true,
    });
JS;
$this->registerJs($js);
?>