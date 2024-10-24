<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\detail\DetailView;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use app\models\IndonesiaDate;
use app\modules\pelatihan\models\RefHari;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */

$this->title = 'Absen Global Peserta Pelatihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-kelas-view">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'frm-check-absensi-peserta',
                        'type' => ActiveForm::TYPE_VERTICAL,
            ]);

            echo Form::widget([
                'model' => $modelAbsen,
                'form' => $form,
                'columns' => 3,
                'attributes' => [
                    'hari' => [
                        'type' => Form::INPUT_RAW,
                        'value' => '<div style="text-align: left; margin-top: 0px">'
                        . '<label class="control-label" for="no-peserta">Hari</label>'
                        . '<input type="text" readonly="true" style="background-color:white;" class="form-control" value="' . RefHari::findOne($hari)->hariInd . '"/>'
                        . '</div>'
                    ],
                    'tanggal' => [
                        'type' => Form::INPUT_RAW,
                        'value' => '<div style="text-align: left; margin-top: 0px">'
                        . '<label class="control-label" for="no-peserta">Tanggal</label>'
                        . '<input type="text" readonly="true" style="background-color:white;" class="form-control" value="' . $inDate->setDate($inDate->getDate()) . '"/>'
                        . '</div>'
                    ],
                    'absenPesertaId' => ['type' => Form::INPUT_TEXT, 'options' => ['id' => 'no-peserta', 'placeholder' => 'Scan/Ketikan Nomor Peserta']],
                ]
            ]);

            ActiveForm::end();
            ?>
            <ul style="list-style: lower-alpha;margin-left: -20px;margin-top: -5px;">
                <li><b>Hari</b> adalah hari ini saat peserta pelatihan mengambil absen.</li>
                <li><b>Tanggal</b> adalah tanggal sekarang dimana peserta pelatihan mengambil absen.</li>
                <li><b>Nomor Peserta</b> adalah nomor peserta pelatihan, pastikan kursor berada di <b>Nomor Peserta</b>. Scan/ketikkan nomor peserta pelatihan.</li>
                <li>Absen pelatihan hanya bisa diambil apabila belum melewati jadwal pelatihan. <i>Contoh : Anda pelatihan hari ini Shift I jam 08:00 - 09:00, maka pengambilan absen <b>tidak dapat</b> dilakukan disini apabila jam sudah menunjukkan pukul 09:01.</i></li>
                <li>Absen pelatihan hari ini hanya bisa dilakukan <b>1 kali</b> pada <b>hari dan shift</b> yang sama.</li>
            </ul>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <div id="data-absen-pelatihan" style="margin-top: -15px;">
        <!-- Menampilkan Data -->
        <!--<div class="label-inverse" style="text-align: center;margin-bottom: 20px;padding: 0px;font-size: 18px;">Loading...</div>-->
    </div>
</div>
<?php
Modal::begin([
    'header' => 'Data Peserta',
    'id' => 'modal',
    'size' => 'modal-lg',
]);
echo '<div id="modalContent"></div>';
Modal::end();
//=============================
//Cek Data Peserta
$urlCekData = Url::to(['checkpesertaglobal']);
$js = <<<JS
    $('#no-peserta').focus();
    $('#no-peserta').on('keyup',function(e){
        if($('#no-peserta').val().length>=10){
            $.ajax({
                type: 'GET',
                contentType:false,
                processData:false,
                url: '{$urlCekData}',
                data: $('#frm-check-absensi-peserta').serialize(),
                success: function (data) {
                    if(data==401){
                        alert('Data Peserta Tidak Ditemukan!');
                    }else if(data==402){
                        alert('Peserta tidak ditemukan atau bukan jadwal pengambilan absen!');
                    }else if(data==403){
                        alert('Hari dan tanggal tidak sesuai, silahkan periksa kembali!');
                    }else if(data==404){
                        //sengaja tidak ada pesan
                    }else if(data==406){
                        alert('Pertemuan Sudah Terpenuhi!');
                    }else{
                        $('#modal').modal('show');
                        $('#modalContent').html(data);
                    }
                    $('#no-peserta').val('');
                    return false;
                },
            });
        }
        return false;
    });
JS;
$this->registerJs($js);
?>