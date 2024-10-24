<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;
use app\modules\mahasiswa\models\Fakultas;

$dataFakultas = Fakultas::findOne($fakId);


echo Highcharts::widget([
    'id' => 'grafik-pie-prodi',
    'options' => [
        'chart' => [
            'plotBackgroundColor' => null,
            'plotBorderWidth' => null,
            'plotShadow' => false,
            'type' => 'pie',
        ],
        'title' => [
            'text' => 'Mahasiswa ' . $dataFakultas->fakNama . ' Berdasarkan Program Studi'
        ],
//                            'subtitle' => [
//                                'text' => 'Sub Judul'
//                            ],
        'plotOptions' => [
            'series' => [
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => [
                    'enabled' => true,
                //'format' => '{point.name}: {point.y:.2f}%'
                ],
                'showInLegend' => false
            ]
        ],
        'tooltip' => [
            'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
//                                'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
//                                'pointFormat' => '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b><br/>'
        ],
        'series' => [
                [
                'name' => 'Persentase',
                'colorByPoint' => true,
                'data' => $dataPie
            ],
        ]
    ]
]);
