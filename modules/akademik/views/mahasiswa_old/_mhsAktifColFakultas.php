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

echo Highcharts::widget([
    'id' => 'grafik-column-fakultas',
    'options' => [
        'chart' => [
            'type' => 'column'
        ],
        'title' => [
            'text' => 'Semua Mahasiswa Berdasarkan Fakultas'
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
        'series' => $dataColumn,
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
                
