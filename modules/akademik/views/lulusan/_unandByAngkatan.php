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
            'id' => 'grafik-column-lulusan-unand-by-angkatan',
            'options' => [
                'chart' => [
                    'type' => 'column'
                ],
                'title' => [
                    'text' => 'Data Lulusan 7 (tujuh) Tahun Terakhir',
                ],
                'subtitle' => [
                    'text' => 'Grafik Lulusan Berdasarkan Tahun Masuk/Angkatan'
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
                <th colspan="<?php echo (count($dataTabel['thn']) * 2); ?>" style="width: 80px;text-align: center;vertical-align: middle;">Tahun Masuk/Angkatan</th>
                <th rowspan="3" style="width: 50px;text-align: center;vertical-align: middle;">Jumlah</th>
            </tr>
            <tr style="background: #dff0d8">
                <?php
                for ($i = 0; $i < count($dataTabel['thn']); $i++) {
                    ?>
                    <th colspan="2" style="width: 80px;text-align: center;vertical-align: middle;"><?php echo $dataTabel['thn'][$i]; ?></th>
                    <?php
                }
                ?>
            </tr>
            <tr style="background: #dff0d8">
                <?php
                for ($i = 0; $i < count($dataTabel['thn']); $i++) {
                    ?>
                    <th style="text-align: center;vertical-align: middle;">L</th>
                    <th style="text-align: center;vertical-align: middle;">P</th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            foreach ($dataTabel['fakultas'] as $valFak) {
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $valFak['fakNama']; ?></td>
                    <?php
                    for ($i = 0; $i < count($dataTabel['thn']); $i++) {
                        ?>
                        <td style="text-align: center;"><?php echo isset($dataTabel['dimValue'][$dataTabel['thn'][$i]]['L'][$valFak['fakId']]) ? $dataTabel['dimValue'][$dataTabel['thn'][$i]]['L'][$valFak['fakId']] : 0; ?></td>
                        <td style="text-align: center;"><?php echo isset($dataTabel['dimValue'][$dataTabel['thn'][$i]]['P'][$valFak['fakId']]) ? $dataTabel['dimValue'][$dataTabel['thn'][$i]]['P'][$valFak['fakId']] : 0; ?></td>
                        <?php
                    }
                    ?>
                    <td style="text-align: center;">
                        <?php
                        $tot = 0;
                        for ($i = 0; $i < count($dataTabel['thn']); $i++) {
                            $jmlL = isset($dataTabel['dimValue'][$dataTabel['thn'][$i]]['L'][$valFak['fakId']]) ? $dataTabel['dimValue'][$dataTabel['thn'][$i]]['L'][$valFak['fakId']] : 0;
                            $jmlP = isset($dataTabel['dimValue'][$dataTabel['thn'][$i]]['P'][$valFak['fakId']]) ? $dataTabel['dimValue'][$dataTabel['thn'][$i]]['P'][$valFak['fakId']] : 0;
                            $tot = $tot + ($jmlL + $jmlP);
                        }
                        $total = $total + $tot;
                        echo $terbilang->setNumber($tot);
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
                <?php
                for ($i = 0; $i < count($dataTabel['thn']); $i++) {
                    ?>
                    <th colspan="2" style="text-align: center;"><?php echo $terbilang->setNumber(isset($dataTabel['subTotal'][$dataTabel['thn'][$i]]) ? $dataTabel['subTotal'][$dataTabel['thn'][$i]] : 0); ?></th>
                        <?php
                    }
                    ?>
                <th><?php echo $terbilang->setNumber($total); ?></th>
            </tr>
        </footer>
    </table>
</div>