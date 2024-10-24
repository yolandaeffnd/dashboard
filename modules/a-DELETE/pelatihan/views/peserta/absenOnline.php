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
use app\modules\pelatihan\models\LatKelasInstruktur;
use app\modules\pelatihan\models\LatPeserta;
use app\modules\pelatihan\models\LatJadwal;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */

$this->title = 'Absen Peserta Kelas Pelatihan';
$this->params['breadcrumbs'][] = ['label' => 'Peserta Pelatihan', 'url' => ['index']];
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
            //Menampilkan Instruktur
            $instruktur = LatKelasInstruktur::find()
                    ->select(['instNama'])
                    ->join('JOIN', 'ref_instruktur', 'ref_instruktur.instId=lat_kelas_instruktur.instId')
                    ->where('klsId=:kls', [':kls' => $model->klsId])
                    ->each();
            $inst = '';
            foreach ($instruktur as $val) {
                if ($inst == '') {
                    $inst = '<li>' . $val['instNama'] . '</li>';
                } else {
                    $inst = $inst . '<li>' . $val['instNama'] . '</li>';
                }
            }
            $dataInstruktur = '<ul style="list-style:lower-alpha;margin-left:-25px;margin-bottom:0px;">' . $inst . '</ul>';

            echo DetailView::widget([
                'model' => $model,
                'condensed' => true,
                'hover' => true,
                'mode' => DetailView::MODE_VIEW,
                'panel' => [
                ],
                'updateOptions' => [
                    'hidden' => true,
                ],
                'deleteOptions' => [
                    'hidden' => true,
                ],
                'attributes' => [
                        [
                        'columns' => [
                                [
                                'attribute' => 'klsPeriodeId',
                                'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                                'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:40%'],
                                'format' => 'raw',
                                'value' => '<b>' . $model->klsPeriode->periodeJnslat->jnslatNama . '</b><br/>'
                                . $model->klsPeriode->periodeNama
                            ],
                                [
                                'label' => 'Instruktur',
                                'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;'],
                                'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:30%'],
                                'format' => 'raw',
                                'value' => $dataInstruktur
                            ],
                        ]
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'klsNama',
                                'format' => 'raw',
                                'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
//                                'valueColOptions' => ['style' => 'vertical-align:top;width:40%'],
                                'value' => $model->klsNama
                            ],
//                                [
//                                'attribute' => 'klsKapasitas',
//                                'format' => 'raw',
//                                'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:10%'],
//                                'valueColOptions' => ['style' => 'vertical-align:top;width:10%'],
//                                'value' => $model->klsKapasitas . ' Orang'
//                            ],
//                                [
//                                'label' => 'Jml Peserta',
//                                'format' => 'raw',
//                                'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:10%'],
//                                'valueColOptions' => ['style' => 'vertical-align:top;width:10%'],
//                                'value' => LatPeserta::find()->where('pesertaKlsId=:id', [':id' => $model->klsId])->count() . ' Orang'
//                            ],
                        ]
                    ],
                ]
            ]);

            //Form Absensi
            $dataJadwal = LatJadwal::find()
                    ->select(['*', 'hariInd'])
                    ->join('JOIN', 'ref_hari', 'ref_hari.hariKode=jdwlHariKode')
                    ->where('jdwlKlsId=:kls', [':kls' => $model->klsId])
                    ->all();
            $form = ActiveForm::begin([
                        'id' => 'frm-check-absensi-peserta',
                        'type' => ActiveForm::TYPE_VERTICAL,
            ]);

            echo Form::widget([
                'model' => $modelAbsen,
                'form' => $form,
                'columns' => 3,
                'attributes' => [
                    'absenJdwlId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                            'data' => ArrayHelper::map($dataJadwal, 'jdwlId', 'hariInd'),
                            'size' => Select2:: MEDIUM,
                            'options' => [
                                'placeholder' => '- Pilih Hari -',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => false,
                            ],
                        ],
                    ],
                    'absenTgl' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DatePicker::className(), 'options' => [
                            'convertFormat' => true,
                            'options' => ['placeholder' => 'Tanggal'],
                            'pluginOptions' => [
                                'format' => 'yyyy-MM-dd',
                                'todayHighlight' => true,
                                'autoclose' => true
                            ]
                        ],
                    ],
                    'absenPesertaId' => ['type' => Form::INPUT_TEXT, 'options' => ['id' => 'no-peserta', 'placeholder' => 'Scan/Ketikan Nomor Peserta']],
//                    'absenIsHadir' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
//                            'data' => ['1' => 'Hadir', '0' => 'Tidak Hadir'],
//                            'size' => Select2:: MEDIUM,
//                            'options' => [
//                            ],
//                            'pluginOptions' => [
//                                'allowClear' => false,
//                                'multiple' => false,
//                            ],
//                        ],
//                    ],
                ]
            ]);
            ?>
            <div style="float: left;">
                <ul style="list-style: lower-alpha;margin-left: -20px;margin-top: -5px;">
                    <li><b>Hari Pertemuan</b> :  isi hari pertemuan berdasarkan jadwal pelatihan.</li>
                    <li><b>Tanggal Pertemuan</b> :  isi tanggal pertemuan berdasarkan tanggal dilaksanakan pelatihan.</li>
                    <li><b>Nomor Peserta</b> :  ketikan/scan nomor peserta pelatihan.</li>
                    <li><b>Tombol Tampilkan</b> :  Untuk menampilkan berdasarkan hari dan tanggal diatas.</li>
                    <li><b>Tombol Set Hadir</b> :  Untuk set HADIR yang belum ambil absen pada tanggal diatas.</li>
                    <li><b>Tombol Set Tidak Hadir</b> :  Untuk set TIDAK HADIR yang belum ambil absen pada tanggal diatas.</li>
                </ul>
            </div>
            <div style="float: right;width:20%;">
                <ul style="list-style: none;margin-left: -20px;margin-top: -5px;text-align: right;">
                    <li><b>Kapasitas : </b><b><?php echo $model->klsKapasitas; ?></b> Orang</li>
                    <li><b>Jml Peserta : </b><b><?php echo LatPeserta::find()->where('pesertaKlsId=:id', [':id' => $model->klsId])->count(); ?></b> Orang</li>
                    <li><b>Belum Absen : </b><b><span id="label-belum-absen">0</span></b> Orang</li>
                    <li><b>Sudah Absen : </b><span id="label-sudah-absen">0</span> Orang</li>
                    <li><b>Jml Hadir : </b><span id="label-jml-hadir">0</span> Orang</li>
                    <li><b>Jml Tidak Hadir : </b><span id="label-jml-tidak-hadir">0</span> Orang</li>
                </ul>
            </div>
            <div style="clear: both;"></div>
            <?php
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    'actions' => [
                        'type' => Form::INPUT_RAW,
                        'value' => '<div style="text-align: left; margin-top: 0px">' .
                        Html::a(' Kembali', Url::to(['view', 'id' => $model->klsId]), ['class' => 'fa fa-reply btn btn-default btn-flat', 'style' => 'margin-top:0px;margin-right:5px;']) .
                        Html::submitButton(' Tampilkan', ['type' => 'button', 'class' => 'fa fa-search btn btn-primary btn-flat'])
                        . '<span id="set-tidak-hadir" style="float:right;margin-left:5px;"></span>'
                        . '<span id="set-hadir" style="float:right;margin-left:5px;"></span>'
                        . '</div>'
                    ],
                ]
            ]);

            ActiveForm::end();
            ?>
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
//Menampilkan Tombol Set Hadir & Set Tidak Hadir
if ($modelAbsen->absenJdwlId != '' && $modelAbsen->absenTgl != '' && $hariTglMatch=='1') {
    $btnSetTidakHadir = Html::a(' Set Tidak Hadir','#', ['id' => 'btn-set-tidak-hadir', 'class' => 'fa fa-times-circle btn btn-warning btn-flat']);
    $btnSetHadir = Html::a(' Set Hadir','#', ['id' => 'btn-set-hadir', 'class' => 'fa fa-check-circle btn btn-success btn-flat']);
} else {
    $btnSetTidakHadir = '';
    $btnSetHadir = '';
}
//Set Hadir Masal
$paramAbsen = urlencode(serialize(['kls' => $model->klsId, 'jdwl' => $modelAbsen->absenJdwlId, 'tgl' => $modelAbsen->absenTgl]));
$urlSetHadir = Url::to(['simpanpeserta','act'=>'masal-set-hadir' ,'params' => $paramAbsen]);
$urlSetTidakHadir = Url::to(['simpanpeserta','act'=>'masal-set-tidak-hadir' ,'params' => $paramAbsen]);
 
//Menampilkan Status Jumlah 
$param = [
    'kls' => $model->klsId,
    'jdwl' => $modelAbsen->absenJdwlId,
    'tgl' => $modelAbsen->absenTgl
];
$urlBelumAbsen = Url::to(['countabsensi', 'act' => 'belum-absen', 'params' => urlencode(serialize($param))]);
$urlSudahAbsen = Url::to(['countabsensi', 'act' => 'sudah-absen', 'params' => urlencode(serialize($param))]);
$urlJmlHadir = Url::to(['countabsensi', 'act' => 'jml-hadir', 'params' => urlencode(serialize($param))]);
$urlJmlTidakHadir = Url::to(['countabsensi', 'act' => 'jml-tidak-hadir', 'params' => urlencode(serialize($param))]);
//Get Kehadiran Peserta
$urlGetData = Url::to(['getkehadiran', 'klsid' => $model->klsId, 'jdwlid' => $modelAbsen->absenJdwlId, 'tgl' => $modelAbsen->absenTgl]);
//Cek Data Peserta
$urlCekData = Url::to(['checkpeserta']);
$js = <<<JS
    $('#data-absen-pelatihan').load('{$urlGetData}');
    $('#set-tidak-hadir').html('{$btnSetTidakHadir}');
    $('#set-hadir').html('{$btnSetHadir}');
    $('#label-belum-absen').load('{$urlBelumAbsen}');
    $('#label-sudah-absen').load('{$urlSudahAbsen}');
    $('#label-jml-hadir').load('{$urlJmlHadir}');
    $('#label-jml-tidak-hadir').load('{$urlJmlTidakHadir}');
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
                        alert('Absensi Sudah Tercatat!');
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
    //Set Hadir Masal
    $('#btn-set-hadir').on('click',function(e){
        krajeeDialog.confirm("Apakah anda akan melakukan Set Hadir Masal kepada peserta yang belum ambil absen?", function (result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    url: '{$urlSetHadir}',
                    success: function (data) {
                        if(data==410){
                            alert('Set Hadir Selesai...');
                        }else if(data==401){
                            alert('Gagal Menyimpan Absensi!');
                        }else if(data==400){
                            alert('Absensi Sudah Tercatat...');
                        }
                        $('#data-absen-pelatihan').load('{$urlGetData}');
                        $('#label-belum-absen').load('{$urlBelumAbsen}');
                        $('#label-sudah-absen').load('{$urlSudahAbsen}');
                        $('#label-jml-hadir').load('{$urlJmlHadir}');
                        $('#label-jml-tidak-hadir').load('{$urlJmlTidakHadir}');
                        krajeeDialog.remove();
                        return false;
                    },
                });
            } else {
                this.remove();
            }
        });
    });
    //Set Tidak Hadir Masal
    $('#btn-set-tidak-hadir').on('click',function(e){
        krajeeDialog.confirm("Apakah anda akan melakukan Set Tidak Hadir Masal kepada peserta yang belum ambil absen?", function (result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    url: '{$urlSetTidakHadir}',
                    success: function (data) {
                        if(data==410){
                            alert('Set Tidak Hadir Selesai...');
                        }else if(data==401){
                            alert('Gagal Menyimpan Absensi!');
                        }else if(data==400){
                            alert('Absensi Sudah Tercatat...');
                        }
                        $('#data-absen-pelatihan').load('{$urlGetData}');
                        $('#label-belum-absen').load('{$urlBelumAbsen}');
                        $('#label-sudah-absen').load('{$urlSudahAbsen}');
                        $('#label-jml-hadir').load('{$urlJmlHadir}');
                        $('#label-jml-tidak-hadir').load('{$urlJmlTidakHadir}');
                        krajeeDialog.remove();
                        return false;
                    },
                });
            } else {
                this.remove();
            }
        });
    });
JS;
$this->registerJs($js);
?>