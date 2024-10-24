<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\modules\pelatihan\models\LatKelasInstruktur;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */

$this->title = 'Detail Kelas Pelatihan';
$this->params['breadcrumbs'][] = ['label' => 'Kelas Pelatihan', 'url' => ['index']];
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
                        'value' => $model->klsMeetingMin . ' Pertemuan'
                    ],
                        [
                        'attribute' => 'klsMeetingMax',
                        'value' => $model->klsMeetingMax . ' Pertemuan'
                    ],
                        [
                        'attribute' => 'klsIsPublish',
                        'value' => ($model->klsIsPublish == 1) ? 'Ya' : 'Tidak'
                    ],
                ]
            ],
                [
                'label' => 'Instruktur',
                'format' => 'raw',
                'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;'],
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
    echo Html::a(' Kembali', Url::to(['index']), ['class' => 'fa fa-reply btn btn-default', 'style' => 'margin-top:-20px;margin-right:5px;']);
    echo Html::a(' Ubah', Url::to(['update', 'id' => $model->klsId]), ['class' => 'fa fa-edit btn btn-primary', 'style' => 'margin-top:-20px;']);
    ?>
</div>
