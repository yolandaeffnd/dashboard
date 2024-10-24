<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\widgets\Pjax;
use app\modules\pelatihan\models\LatKelasInstruktur;
use app\modules\pelatihan\models\LatPeserta;
use app\modules\pelatihan\models\LatPesertaAbsen;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */

$this->title = 'Detail Peserta Kelas Pelatihan';
$this->params['breadcrumbs'][] = ['label' => 'Peserta Pelatihan', 'url' => ['index']];
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
                        'valueColOptions' => ['style' => 'vertical-align:top;width:10%'],
                        'value' => $model->klsMeetingMin . ' Pertemuan'
                    ],
                        [
                        'attribute' => 'klsMeetingMax',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:10%'],
                        'value' => $model->klsMeetingMax . ' Pertemuan'
                    ],
                        [
                        'attribute' => 'klsIsPublish',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:20%'],
                        'value' => ($model->klsIsPublish == 1) ? 'Ya' : 'Tidak'
                    ],
                ]
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'klsKapasitas',
                        'format' => 'raw',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:10%'],
                        'value' => $model->klsKapasitas . ' Orang'
                    ],
                        [
                        'label' => 'Jml Peserta',
                        'format' => 'raw',
                        'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;width:20%'],
                        'valueColOptions' => ['style' => 'vertical-align:top;width:50%'],
                        'value' => LatPeserta::find()->where('pesertaKlsId=:id', [':id' => $model->klsId])->count() . ' Orang'
                    ],
                ]
            ],
                [
                'label' => 'Instruktur',
                'format' => 'raw',
                'value' => $dataInstruktur
            ],
                [
                'label' => 'Jadwal Pelatihan',
                'format' => 'raw',
                'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;'],
                'value' => GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'hover' => true,
                    'options' => ['style' => 'margin-bottom:0px;'],
                    'toolbar' => [
                        '{toggleData}',
                    ],
                    'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            [
                            'attribute' => 'jdwlHariKode',
                            'value' => function ($data) {
                                return $data->jdwlHariKode0->hariInd;
                            }
                        ],
                            [
                            'attribute' => 'jdwlRuangId',
                            'value' => function ($data) {
                                return $data->jdwlRuang->ruangNama;
                            }
                        ],
                            [
                            'attribute' => 'jdwlJamMulai',
                            'value' => function ($data) {
                                return $data->jdwlJamMulai;
                            }
                        ],
                            [
                            'attribute' => 'jdwlJamSelesai',
                            'value' => function ($data) {
                                return $data->jdwlJamSelesai;
                            }
                        ],
                    ],
                    'panel' => [
                        'type' => 'success',
                        'heading' => false,
                        'after' => false,
                        'before' => false,
                        'footer' => false
                    ],
                ])
            ],
        ]
    ]);
    ?>
    <div class="box" style="margin-bottom: 10px;margin-top: -17px;">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'frm-cari-peserta-kelas-pelatihan',
                        'type' => ActiveForm::TYPE_VERTICAL
            ]);

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
                        'value' => '<div style="float:left;text-align: left; margin-top: 0px">'
                        . Html::a(' Kembali', Url::to(['index']), ['class' => 'fa fa-reply btn btn-default btn-flat', 'style' => 'margin-top:-15px;margin-right:5px;'])
                        . Html::submitButton(' Tampilkan', ['type' => 'button', 'class' => 'fa fa-search btn btn-primary btn-flat', 'style' => 'margin-top:-15px;'])
                        . '</div>'
                        . '<div style="float:right;text-align: right;  margin-top: 0px">'
                        . Html::a(' Cetak Blanko Absen', '#', ['id' => 'print-blanko-absen', 'class' => 'fa fa-print btn btn-primary btn-flat', 'style' => 'margin-top:-15px;margin-right:5px;', 'taget' => '_blank'])
                        . Html::a(' Absen Online', Url::to(['absenonline', 'id' => $model->klsId]), ['class' => 'fa fa-edit btn btn-primary btn-flat', 'style' => 'margin-top:-15px;margin-right:5px;'])
                        . Html::a(' Import Absen', Url::to(['importabsen', 'id' => $model->klsId]), ['class' => 'fa fa-upload btn btn-success btn-flat', 'style' => 'margin-top:-15px;margin-right:0px;'])
                        . '</div>'
                    ],
                ]
            ]);

            ActiveForm::end();
            ?>
            <h5 style="margin-top: 10px;padding: 0px;margin-bottom: 0px;">Petunjuk :</h5>
            <ul style="margin-left: -15px;list-style: lower-alpha;">
                <li>Pindah kelas hanya bisa dilakukan apabila periode pelatihan belum dimulai.</li>
                <li>Kelas hanya dapat dipindahkan ke kelas lain yang periodenya sama.</li>
            </ul>
        </div>
    </div>
    <div id="data-peserta-kelas-pelatihan-" style="margin-top: -3px;">
        <?php
        echo $this->render('_viewPeserta', [
            'modelPeserta' => $modelPeserta,
            'dataProviderPeserta' => $dataProviderPeserta,
            'klsid' => $model->klsId
        ]);
        ?>
    </div>
</div>
<?php
$urlPrintBlankoAbsen = Url::to(['printblankoabsen', 'id' => $model->klsId]);
$urlGetData = Url::to(['getview', 'klsid' => $model->klsId]);
$js = <<<JS
//    $('#data-peserta-kelas-pelatihan').load('{$urlGetData}');
    $('#print-blanko-absen').on('click',function(e){
        window.open('{$urlPrintBlankoAbsen}','_blank');
        return false;
    });
JS;
$this->registerJs($js);
?>