<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;
use app\components\Terbilang;

$inDate = new IndonesiaDate();
$terbilang = new Terbilang();

/* @var $this yii\web\View */
?>
<div style="">
    <table class="table table-bordered table-striped">
        <thead>
            <tr style="background: #dff0d8">
                <th rowspan="3" style="width: 30px;vertical-align: middle;">No</th>
                <th rowspan="3" style="text-align: center;vertical-align: middle;">Fakultas</th>
                    <th colspan="<?php echo (count($dataTabel['thn'])*2); ?>" style="width: 80px;text-align: center;vertical-align: middle;">Tahun Lulus</th>
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
                        echo $terbilang->setNumber($tot);
                        ?>
                    </td>
                </tr>
                <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>
