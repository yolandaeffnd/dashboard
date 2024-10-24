<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use app\modules\pelatihan\models\LatKelasInstruktur;
use app\modules\pelatihan\models\LatPeserta;
use app\modules\instrukturkelas\models\LatJadwal;
use app\modules\instrukturkelas\models\RefHari;
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */

$this->title = 'Detail Peserta Kelas';
$this->params['breadcrumbs'][] = ['label' => 'Peserta Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-kelas-view">
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
            'heading' => 'Detail Kelas Pelatihan',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'hidden' => true,
            'onclick' => 'document.location="' . Url::to(['update', 'id' => $model->klsId]) . '";',
        ],
        'deleteOptions' => [
            'hidden' => true,
            'ajaxSettings' => [
                'url' => Url::to(['delete', 'id' => $model->klsId]),
                'success' => 'function(){document.location="' . Url::to(['index']) . '";}'
            ]
        ],
        'attributes' => [
                [
                'attribute' => 'klsPeriodeId',
                'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;'],
                'format' => 'raw',
                'value' => '<b>' . $model->klsPeriode->periodeJnslat->jnslatNama . '</b><br/>'
                . $model->klsPeriode->periodeNama
            ],
            'klsNama',
                [
                'columns' => [
                        [
                        'attribute' => 'klsMeetingMin',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:30%'],
                        'value' => $model->klsMeetingMin . ' Pertemuan'
                    ],
                        [
                        'attribute' => 'klsMeetingMax',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:30%'],
                        'value' => $model->klsMeetingMax . ' Pertemuan'
                    ],
                ]
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'klsKapasitas',
                        'format' => 'raw',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:30%'],
                        'value' => $model->klsKapasitas . ' Orang'
                    ],
                        [
                        'label' => 'Jml Peserta',
                        'format' => 'raw',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:30%'],
                        'value' => LatPeserta::find()->where('pesertaKlsId=:id', [':id' => $model->klsId])->count() . ' Orang'
                    ],
                ]
            ],
                [
                'label' => 'Instruktur',
                'format' => 'raw',
                'value' => $dataInstruktur
            ],
        ]
    ]);
    ?>
    <div class="box" style="margin-bottom: 10px;margin-top: -17px;">
        <div class="box-body">
            <?php
            $dataJadwal = LatJadwal::find()
                    ->select(['*', 'hariInd'])
                    ->join('JOIN', 'ref_hari', 'ref_hari.hariKode=jdwlHariKode')
                    ->where('jdwlKlsId=:kls', [':kls' => $model->klsId])
                    ->all();

            $form = ActiveForm::begin([
                        'id' => 'frm-cari-peserta-kelas-pelatihan',
                        'type' => ActiveForm::TYPE_VERTICAL,
            ]);
            if ($absenJdwl != '' && $absenTgl != '' && $hariIsNotMatch == '') {
                echo Form::widget([
                    'model' => $modelPeserta,
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [
                        'hari' => [
                            'type' => Form::INPUT_RAW,
                            'value' => '<div style="text-align: left; margin-top: 0px">'
                            . '<label class="control-label" for="no-peserta">Jadwal Pertemuan</label>'
                            . '<input id="reset-jdwl" type="text" readonly="true" style="background-color:white;font-weight:bold;" class="form-control" value="' . RefHari::findOne($hari)->hariInd . '"/>'
                            . '</div>'
                        ],
                        'tanggal' => [
                            'type' => Form::INPUT_RAW,
                            'value' => '<div style="text-align: left; margin-top: 0px">'
                            . '<label class="control-label" for="no-peserta">Tanggal Pertemuan</label>'
                            . '<input id="reset-tgl" type="text" readonly="true" style="background-color:white;font-weight:bold;" class="form-control" value="' . $inDate->setDate($absenTgl) . '"/>'
                            . '</div>'
                        ],
                    ]
                ]);
            } else {
                echo Form::widget([
                    'model' => $modelPeserta,
                    'form' => $form,
                    'columns' => 2,
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
                    ]
                ]);
            }

            echo Form::widget([
                'model' => $modelPeserta,
                'form' => $form,
                'columns' => 2,
                'attributes' => [
                    'pesertaId' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nomor peserta']],
                    'pesertaNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama peserta']],
                ]
            ]);

            echo Form::widget([
                'model' => $modelPeserta,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    'actions' => [
                        'type' => Form::INPUT_RAW,
                        'value' => '<div style="margin-top: 0px">'
                        . Html::a(' Kembali', Url::to(['index']), ['class' => 'fa fa-reply btn btn-default btn-flat', 'style' => 'margin-top:-5px;float:left;'])
                        . Html::submitButton(' Tampilkan', ['type' => 'button', 'class' => 'fa fa-search btn btn-primary btn-flat', 'style' => 'margin-top:-5px;float:right;'])
                        . '</div>'
                    ],
                ]
            ]);

            ActiveForm::end();
            ?>
            <h5 style="margin-top: 10px;padding: 0px;margin-bottom: 0px;">Petunjuk :</h5>
            <ul style="margin-left: -15px;list-style: lower-alpha;">
                <li>Pilih jadwal dan tanggal pertemuan.</li>
                <li>Klik tombol tampilkan untuk menampilkan daftar peserta kelas.</li>
            </ul>
        </div>
    </div>
    <div id="data-peserta-kelas-pelatihan-" style="margin-top: -3px;">
        <?php
        if ($absenJdwl != '' && $absenTgl != '' && $dataProviderPeserta != '') {
            echo $this->render('_viewPeserta', [
                'modelPeserta' => $modelPeserta,
                'dataProviderPeserta' => $dataProviderPeserta,
                'klsid' => $model->klsId,
                'absenJdwl' => $absenJdwl,
                'absenTgl' => $absenTgl
            ]);
        } else {
            ?>
            <div class="alert alert-block alert-info">
                <h5>Hari ini tanggal <?php echo $inDate->setDate($inDate->getDate()); ?> <b>Tidak Ada</b> jadwal pelatihan yang dipilih. Silahkan pilih <b>Jadwal</b> dan <b>Tanggal Pertemuan</b> untuk menampilkan peserta kelas.</h5>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
$urlReset = Url::to(['view', 'id' => $model->klsId]);
$js = <<<JS
    $('#reset-tgl').on('focus',function(){
        document.location='{$urlReset}';
    });
    $('#reset-jdwl').on('focus',function(){
        document.location='{$urlReset}';
    });
JS;
$this->registerJs($js);
?>