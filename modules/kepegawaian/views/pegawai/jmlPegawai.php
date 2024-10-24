<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use app\models\AppUserData;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use app\modules\mahasiswa\models\ProdiNasional;
use app\modules\mahasiswa\models\Fakultas;
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\report\models\Stock */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Jumlah Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-create">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 5px;">
                        <div class="panel-heading" style="padding-top:8px;padding-bottom:8px;">
                            <h5 style="margin: 0px;"><i>Berdasarkan Jenis Pegawai</i></h5>
                        </div>
                        <div class="panel-body center" style="padding-bottom: 0px;">
                            <?php
                            echo Highcharts::widget([
                                'id' => 'col-jml-pegawai-02',
                                'options' => [
                                    'chart' => [
                                        'type' => 'column'
                                    ],
                                    'title' => [
                                        'text' => ''
                                    ],
                                    'subtitle' => [
                                        'text' => 'Data Bulan ' . $inDate->arrBulan[$bln] . ' ' . $thn
                                    ],
                                    'plotOptions' => [
                                        'series' => [
                                            'allowPointSelect' => true,
                                            'cursor' => 'pointer',
                                            'dataLabels' => [
                                                'enabled' => true,
                                            ],
                                            'showInLegend' => true,
                                            'enableMouseTracking' => true
                                        ],
                                    ],
                                    'tooltip' => [
                                        'pointFormat' => '{series.name}: <b>{point.y:.0f} Orang</b>'
                                    ],
                                    'xAxis' => [
                                        'type' => 'Label X',
                                        'labels' => [
                                            //'rotation' => 0,
                                            'style' => "fontSize: '13px';fontFamily: 'Verdana, sans-serif'"
                                        ],
                                        'categories' => $data['BY_JENIS_PEGAWAI']['kategori']
                                    ],
                                    'yAxis' => [
                                        'min' => 0,
                                        'title' => [
                                            'text' => 'Jumlah'
                                        ]
                                    ],
                                    'series' => $data['BY_JENIS_PEGAWAI']['kolom'],
                                    'dataLabels' => [
                                        'enabled' => true,
                                        'rotation' => -90,
                                        'color' => '#FFFFFF',
                                        'align' => 'right',
                                        'format' => '{point.y:.1f}', // one decimal
                                        'y' => 10, // 10 pixels down from the top
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Verdana, sans-serif'
                                        ]
                                    ],
                                    'credits' => ['enabled' => false]
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 5px;">
                        <div class="panel-heading" style="padding-top:8px;padding-bottom:8px;">
                            <h5 style="margin: 0px;"><i>Berdasarkan Kategori Pegawai</i></h5>
                        </div>
                        <div class="panel-body center" style="padding-bottom: 0px;">
                            <div class="col-md-6">
                                <?php
                                echo Highcharts::widget([
                                    'id' => 'col-jml-pegawai-01-a',
                                    'options' => [
                                        'chart' => [
                                            'type' => 'column'
                                        ],
                                        'title' => [
                                            'text' => 'PNS/CPNS'
                                        ],
                                        'subtitle' => [
                                            'text' => 'Data Bulan ' . $inDate->arrBulan[$bln] . ' ' . $thn
                                        ],
                                        'plotOptions' => [
                                            'series' => [
                                                'allowPointSelect' => true,
                                                'cursor' => 'pointer',
                                                'dataLabels' => [
                                                    'enabled' => true,
                                                ],
                                                'showInLegend' => true,
                                                'enableMouseTracking' => true
                                            ],
                                        ],
                                        'tooltip' => [
                                            'pointFormat' => '{series.name}: <b>{point.y:.0f} Orang</b>'
                                        ],
                                        'xAxis' => [
                                            'type' => 'Label X',
                                            'labels' => [
                                                //'rotation' => 0,
                                                'style' => "fontSize: '13px';fontFamily: 'Verdana, sans-serif'"
                                            ],
                                            'categories' => $data['BY_KATEGORI_PEGAWAI']['kategori']
                                        ],
                                        'yAxis' => [
                                            'min' => 0,
                                            'title' => [
                                                'text' => 'Jumlah'
                                            ]
                                        ],
                                        'series' => $data['BY_KATEGORI_PEGAWAI']['kolom'],
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'rotation' => -90,
                                            'color' => '#FFFFFF',
                                            'align' => 'right',
                                            'format' => '{point.y:.1f}', // one decimal
                                            'y' => 10, // 10 pixels down from the top
                                            'style' => [
                                                'fontSize' => '13px',
                                                'fontFamily' => 'Verdana, sans-serif'
                                            ]
                                        ],
                                        'credits' => ['enabled' => false]
                                    ]
                                ]);
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo Highcharts::widget([
                                    'id' => 'col-jml-pegawai-01-b',
                                    'options' => [
                                        'chart' => [
                                            'type' => 'column'
                                        ],
                                        'title' => [
                                            'text' => 'Non PNS/Kontrak'
                                        ],
                                        'subtitle' => [
                                            'text' => 'Data Bulan ' . $inDate->arrBulan[$bln] . ' ' . $thn
                                        ],
                                        'plotOptions' => [
                                            'series' => [
                                                'allowPointSelect' => true,
                                                'cursor' => 'pointer',
                                                'dataLabels' => [
                                                    'enabled' => true,
                                                ],
                                                'showInLegend' => true,
                                                'enableMouseTracking' => true
                                            ],
                                        ],
                                        'tooltip' => [
                                            'pointFormat' => '{series.name}: <b>{point.y:.0f} Orang</b>'
                                        ],
                                        'xAxis' => [
                                            'type' => 'Label X',
                                            'labels' => [
                                                //'rotation' => 0,
                                                'style' => "fontSize: '13px';fontFamily: 'Verdana, sans-serif'"
                                            ],
                                            'categories' => $data['BY_KATEGORI_PEGAWAI_NON']['kategori']
                                        ],
                                        'yAxis' => [
                                            'min' => 0,
                                            'title' => [
                                                'text' => 'Jumlah'
                                            ]
                                        ],
                                        'series' => $data['BY_KATEGORI_PEGAWAI_NON']['kolom'],
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'rotation' => -90,
                                            'color' => '#FFFFFF',
                                            'align' => 'right',
                                            'format' => '{point.y:.1f}', // one decimal
                                            'y' => 10, // 10 pixels down from the top
                                            'style' => [
                                                'fontSize' => '13px',
                                                'fontFamily' => 'Verdana, sans-serif'
                                            ]
                                        ],
                                        'credits' => ['enabled' => false]
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 5px;">
                        <div class="panel-heading" style="padding-top:8px;padding-bottom:8px;">
                            <h5 style="margin: 0px;"><i>Berdasarkan Jenjang Pendidikan</i></h5>
                        </div>
                        <div class="panel-body center" style="padding-bottom: 0px;">
                            <div class="col-md-8">
                                <?php
                                echo Highcharts::widget([
                                    'id' => 'col-jml-pegawai-03-a',
                                    'options' => [
                                        'chart' => [
                                            'type' => 'column'
                                        ],
                                        'title' => [
                                            'text' => 'PNS/CPNS'
                                        ],
                                        'subtitle' => [
                                            'text' => 'Data Bulan ' . $inDate->arrBulan[$bln] . ' ' . $thn
                                        ],
                                        'plotOptions' => [
                                            'series' => [
                                                'allowPointSelect' => true,
                                                'cursor' => 'pointer',
                                                'dataLabels' => [
                                                    'enabled' => true,
                                                ],
                                                'showInLegend' => true,
                                                'enableMouseTracking' => true
                                            ],
                                        ],
                                        'tooltip' => [
                                            'pointFormat' => '{series.name}: <b>{point.y:.0f} Orang</b>'
                                        ],
                                        'xAxis' => [
                                            'type' => 'Label X',
                                            'labels' => [
                                                //'rotation' => 0,
                                                'style' => "fontSize: '13px';fontFamily: 'Verdana, sans-serif'"
                                            ],
                                            'categories' => $data['BY_PDDK_PEGAWAI']['kategori']
                                        ],
                                        'yAxis' => [
                                            'min' => 0,
                                            'title' => [
                                                'text' => 'Jumlah'
                                            ]
                                        ],
                                        'series' => $data['BY_PDDK_PEGAWAI']['kolom'],
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'rotation' => -90,
                                            'color' => '#FFFFFF',
                                            'align' => 'right',
                                            'format' => '{point.y:.1f}', // one decimal
                                            'y' => 10, // 10 pixels down from the top
                                            'style' => [
                                                'fontSize' => '13px',
                                                'fontFamily' => 'Verdana, sans-serif'
                                            ]
                                        ],
                                        'credits' => ['enabled' => false]
                                    ]
                                ]);
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                echo Highcharts::widget([
                                    'id' => 'col-jml-pegawai-03-b',
                                    'options' => [
                                        'chart' => [
                                            'type' => 'column'
                                        ],
                                        'title' => [
                                            'text' => 'Non PNS/Kontrak'
                                        ],
                                        'subtitle' => [
                                            'text' => 'Data Bulan ' . $inDate->arrBulan[$bln] . ' ' . $thn
                                        ],
                                        'plotOptions' => [
                                            'series' => [
                                                'allowPointSelect' => true,
                                                'cursor' => 'pointer',
                                                'dataLabels' => [
                                                    'enabled' => true,
                                                ],
                                                'showInLegend' => true,
                                                'enableMouseTracking' => true
                                            ],
                                        ],
                                        'tooltip' => [
                                            'pointFormat' => '{series.name}: <b>{point.y:.0f} Orang</b>'
                                        ],
                                        'xAxis' => [
                                            'type' => 'Label X',
                                            'labels' => [
                                                //'rotation' => 0,
                                                'style' => "fontSize: '13px';fontFamily: 'Verdana, sans-serif'"
                                            ],
                                            'categories' => $data['BY_PDDK_PEGAWAI_NON']['kategori']
                                        ],
                                        'yAxis' => [
                                            'min' => 0,
                                            'title' => [
                                                'text' => 'Jumlah'
                                            ]
                                        ],
                                        'series' => $data['BY_PDDK_PEGAWAI_NON']['kolom'],
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'rotation' => -90,
                                            'color' => '#FFFFFF',
                                            'align' => 'right',
                                            'format' => '{point.y:.1f}', // one decimal
                                            'y' => 10, // 10 pixels down from the top
                                            'style' => [
                                                'fontSize' => '13px',
                                                'fontFamily' => 'Verdana, sans-serif'
                                            ]
                                        ],
                                        'credits' => ['enabled' => false]
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>