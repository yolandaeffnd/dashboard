<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;

echo Highcharts::widget([
    'id' => 'grafik-pie',
    'options' => [
        'chart' => [
            'plotBackgroundColor' => null,
            'plotBorderWidth' => null,
            'plotShadow' => false,
            'type' => 'pie',
            'options3d'=>[
                'enabled'=>true,
                'alpha'=>45
            ]
        ],
        'title' => [
            'text' => 'Semua Mahasiswa Berdasarkan Jenjang Pendidikan'
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
                'showInLegend' => true
            ],
            'pie'=>[
                'innerSize'=>100,
                'depth'=>45
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
