<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
?>
<div class="lat-periode-view" style="margin-top: -15px;">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title" style="font-size: 16px;"><?= Html::encode('Grafik'); ?></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body" style="">
            <div class="row">
                <div class="col-sm-12" style="">
                    <?php
                    echo Highcharts::widget([
                        'id' => 'grafik-column',
                        'options' => [
                            'chart' => [
                                'type' => 'column'
                            ],
                            'title' => [
                                'text' => ''
                            ],
                            'subtitle' => [
                                'text' => ''
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
//                                'column'=>[
//                                    'stacking'=>'normal'
//                                ]
                            ],
                            'tooltip' => [
                                'pointFormat' => '{series.name}: <b>{point.y:.0f} Orang</b>'
                            ],
                            'xAxis' => [
                                'type' => 'Label X',
                                'labels' => [
                                    'rotation' => -45,
                                    'style' => "fontSize: '13px';fontFamily: 'Verdana, sans-serif'"
                                ],
                                'categories' => $dataKategori
                            ],
                            'yAxis' => [
                                'min' => 0,
                                'title' => [
                                    'text' => 'Jumlah'
                                ]
                            ],
                            'series' =>$dataColumn,
//                            'series' => [
//                                    [
//                                    'name' => 'Laki-Laki',
//                                    'data' => $dataColumn['L']
//                                ],
//                                [
//                                    'name' => 'Perempuan',
//                                    'data' => $dataColumn['P']
//                                ],
//                            ],
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
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
