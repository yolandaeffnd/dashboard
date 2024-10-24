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

$this->title = 'Import Absen Pelatihan';
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
                                'value' => $model->klsNama
                            ],
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
                        'options' => ['enctype' => 'multipart/form-data']
            ]);
            
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    'actions' => [
                        'type' => Form::INPUT_RAW,
                        'value' => '<label class="control-label">Template Import Absen</label>'
                        . '<div style="text-align: left; margin-bottom: 10px">'
                        . 'Silahkan download template import data dahulu '.Html::a('<b>[ Template Import Absensi ]</b>', Url::to(['/site/gettemplate','filename'=>'template-import-absen.xlsx']),['target'=>'_blank'])
                        . '</div>'
                    ],
                ]
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
                    'absenFile' => ['type' => Form::INPUT_FILE, 'options' => ['id' => 'no-peserta', 'placeholder' => 'Scan/Ketikan Nomor Peserta']],
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
                        Html::a(' Kembali', Url::to(['view', 'id' => $model->klsId]), ['class' => 'fa fa-reply btn btn-default btn-flat', 'style' => 'margin-top:0px;margin-right:5px;']) .
                        Html::submitButton(' Import', ['type' => 'button', 'class' => 'fa fa-upload btn btn-primary btn-flat'])
                        . '</div>'
                    ],
                ]
            ]);

            ActiveForm::end();
            ?>
            <h5 style="margin-top: 10px;padding: 0px;margin-bottom: 0px;">Petunjuk :</h5>
            <ul style="margin-left: -15px;list-style: lower-alpha;">
                <li>Siapkan data sesuai dengan templete yang telah disediakan.</li>
                <li>Pilihlah hari pertemuan.</li>
                <li>Pilihlah tanggal pertemuan.</li>
                <li>Pilih file excel yang akan diimport.</li>
                <li>Nomor Peserta yang tidak ada di dalam file excel tersebut dinyatakan <b>Tidak Hadir</b>.</li>
            </ul>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    
    <div id="data-absen-pelatihan" style="margin-top: -15px;">
        <?php
        if ($jdwl != '' && $tgl != '') {
            echo $this->render('_indexImportAbsensi', [
                'dataProvider' => $dataProvider,
                'klsid' => $id,
                'jdwlid' => $jdwl,
                'tgl' => $tgl
            ]);
        }
        ?>
    </div>
</div>