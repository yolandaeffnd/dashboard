<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;
use app\components\Terbilang;

$inDate = new IndonesiaDate();
$terbilang = new Terbilang();
//$dataFakultas = Fakultas::findOne($fakId);

/* @var $this yii\web\View */
?>
<div class="row" style="padding-right: 8px;">
    <div class="col-sm-12">
        <?php
        echo Highcharts::widget([
            'id' => 'grafik-column-prodi-per-fakultas',
            'options' => [
                'chart' => [
                    'type' => 'column'
                ],
                'title' => [
                    'text' => 'Jumlah Program Studi Berdasarkan Fakultas',
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
                        'showInLegend' => false,
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
                        'style' => "fontSize: '12px';fontFamily: 'Verdana, sans-serif'"
                    ],
                    'categories' => $dataCategories
                ],
                'yAxis' => [
                    'min' => 0,
                    'title' => [
                        'text' => 'Jumlah'
                    ]
                ],
                'series' => $dataSeries,
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
                    'rotation' => 90,
                    'color' => '#FFFFFF',
                    'align' => 'right',
                    'format' => '{point.y:.1f}', // one decimal
                    'y' => 10, // 10 pixels down from the top
                    'style' => [
                        'fontSize' => '12px',
                        'fontFamily' => 'Verdana, sans-serif'
                    ]
                ],
                'credits' => ['enabled' => false]
            ]
        ]);
        ?>
    </div>
</div>
<div style="">
    <table class="table table-bordered table-striped">
        <thead>
            <tr style="background: #dff0d8">
                <th rowspan="3" style="width: 30px;vertical-align: middle;">No</th>
                <th rowspan="3" style="text-align: center;vertical-align: middle;">Fakultas</th>
                <th rowspan="3" style="width: 50px;text-align: center;vertical-align: middle;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            foreach ($dataTabel['fakultas'] as $valFak) {
                $total = $total + $valFak['jml'];
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $valFak['fakNama']; ?></td>
                    <td style="text-align: center;"><?php echo $valFak['jml']; ?></td>
                </tr>
                <?php
                $no++;
            }
            ?>
        </tbody>
        <footer>
            <tr>
                <th colspan="2" style="text-align: center;">TOTAL</th>
                <th style="text-align: center;"><?php echo $total;  ?></th>
            </tr>
        </footer>
    </table>
</div>