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
            'id' => 'grafik-column-prodi-by-akreditasi',
            'options' => [
                'chart' => [
                    'type' => 'column'
                ],
                'title' => [
                    'text' => 'Jumlah Program Studi Berdasarkan Akreditasi',
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
                        'style' => "fontSize: '12px';fontFamily: 'Verdana, sans-serif'"
                    ],
                    'crosshair' => true,
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
                <th colspan="4" style="width: 50px;text-align: center;vertical-align: middle;">Akreditasi</th>
                <th rowspan="3" style="width: 50px;text-align: center;vertical-align: middle;">Jumlah</th>
            </tr>
            <tr style="background: #dff0d8">
                <th style="width: 50px;text-align: center;vertical-align: middle;">A</th>
                <th style="width: 50px;text-align: center;vertical-align: middle;">B</th>
                <th style="width: 50px;text-align: center;vertical-align: middle;">C</th>
                <th style="width: 50px;text-align: center;vertical-align: middle;">*</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $jmlA=0;
            $jmlB=0;
            $jmlC=0;
            $jmlN=0;
            foreach ($dataTabel['fakultas'] as $valFak) {
                $jmlA = $jmlA + (isset($dataTabel['item']['A'][$valFak['fakId']]) ? $dataTabel['item']['A'][$valFak['fakId']] : 0);
                $jmlB = $jmlB + (isset($dataTabel['item']['B'][$valFak['fakId']]) ? $dataTabel['item']['B'][$valFak['fakId']] : 0);
                $jmlC = $jmlC + (isset($dataTabel['item']['C'][$valFak['fakId']]) ? $dataTabel['item']['C'][$valFak['fakId']] : 0);
                $jmlN = $jmlN + (isset($dataTabel['item']['N'][$valFak['fakId']]) ? $dataTabel['item']['N'][$valFak['fakId']] : 0);
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $valFak['fakNama']; ?></td>
                    <td style="text-align: center;"><?php echo isset($dataTabel['item']['A'][$valFak['fakId']]) ? $dataTabel['item']['A'][$valFak['fakId']] : 0; ?></td>
                    <td style="text-align: center;"><?php echo isset($dataTabel['item']['B'][$valFak['fakId']]) ? $dataTabel['item']['B'][$valFak['fakId']] : 0; ?></td>
                    <td style="text-align: center;"><?php echo isset($dataTabel['item']['C'][$valFak['fakId']]) ? $dataTabel['item']['C'][$valFak['fakId']] : 0; ?></td>
                    <td style="text-align: center;"><?php echo isset($dataTabel['item']['N'][$valFak['fakId']]) ? $dataTabel['item']['N'][$valFak['fakId']] : 0; ?></td>
                    <td style="text-align: center;">
                        <?php
                        $jml = (isset($dataTabel['item']['A'][$valFak['fakId']]) ? $dataTabel['item']['A'][$valFak['fakId']] : 0) + (isset($dataTabel['item']['B'][$valFak['fakId']]) ? $dataTabel['item']['B'][$valFak['fakId']] : 0) + (isset($dataTabel['item']['C'][$valFak['fakId']]) ? $dataTabel['item']['C'][$valFak['fakId']] : 0) + (isset($dataTabel['item']['N'][$valFak['fakId']]) ? $dataTabel['item']['N'][$valFak['fakId']] : 0);
                        echo $jml;
                        ?>
                    </td>
                </tr>
                <?php
                $no++;
            }
            ?>
        </tbody>
        <footer>
            <tr>
                <th colspan="2" style="text-align: center;">TOTAL</th>
                <th style="text-align: center;"><?php echo $jmlA;  ?></th>
                <th style="text-align: center;"><?php echo $jmlB;  ?></th>
                <th style="text-align: center;"><?php echo $jmlC;  ?></th>
                <th style="text-align: center;"><?php echo $jmlN;  ?></th>
                <th style="text-align: center;"><?php echo $jmlA+$jmlB+$jmlC+$jmlN;  ?></th>
            </tr>
        </footer>
    </table>
<div style="margin-top:-15px;">
Catatan:
<ul style="margin-left:-10px;">
   <li>A : Akreditasi A</li>
   <li>B : Akreditasi B</li>
   <li>C : Akreditasi C</li>
   <li>* : Terakreditasi</li>
</ul>
</div>
</div>