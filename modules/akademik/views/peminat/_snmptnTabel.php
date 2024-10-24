<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;
use app\modules\mahasiswa\models\Terdaftar;
use app\components\Terbilang;

$inDate = new IndonesiaDate();
$terbilang = new Terbilang();
$Terdaftar = new Terdaftar();

/* @var $this yii\web\View */
?>
<div style="margin-top: 30px;">
    <table class="table table-bordered table-striped">
        <thead>
            <tr style="background: #dff0d8">
                <th rowspan="2" style="width: 30px;vertical-align: middle;">No</th>
                <th rowspan="2" style="text-align: center;vertical-align: middle;">Program Studi</th>
                <th colspan="3" style="text-align: center;vertical-align: middle;">PTN 1</th>
                <th colspan="3" style="text-align: center;vertical-align: middle;">PTN 2</th>
                <th rowspan="2" style="width: 100px;text-align: center;vertical-align: middle;">Jumlah</th>
            </tr>
            <tr style="background: #dff0d8">
                <th style="width: 80px;text-align: center;vertical-align: middle;">Pilihan 1</th>
                <th style="width: 80px;text-align: center;vertical-align: middle;">Pilihan 2</th>
                <th style="width: 80px;text-align: center;vertical-align: middle;">Jumlah</th>
                <th style="width: 80px;text-align: center;vertical-align: middle;">Pilihan 1</th>
                <th style="width: 80px;text-align: center;vertical-align: middle;">Pilihan 2</th>
                <th style="width: 80px;text-align: center;vertical-align: middle;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            $tot11 = 0;
            $tot12 = 0;
            $tot21 = 0;
            $tot22 = 0;
            $totPtn1 = 0;
            $totPtn2 = 0;
            foreach ($dataTabel as $val) {
                $total = $total + $val['jml'];
                $jmlPt1 = $val['peminatPt1Pil1'] + $val['peminatPt1Pil2'];
                $jmlPt2 = $val['peminatPt2Pil1'] + $val['peminatPt2Pil2'];
                $tot11 = $tot11 + $val['peminatPt1Pil1'];
                $tot12 = $tot12 + $val['peminatPt1Pil2'];
                $tot21 = $tot21 + $val['peminatPt2Pil1'];
                $tot22 = $tot22 + $val['peminatPt2Pil2'];
                $totPtn1 = $totPtn1 + $jmlPt1;
                $totPtn2 = $totPtn2 + $jmlPt2;
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $val['namaProdi']; ?></td>
                    <td style="text-align: center;"><?php echo $val['peminatPt1Pil1']; ?></td>
                    <td style="text-align: center;"><?php echo $val['peminatPt1Pil2']; ?></td>
                    <td style="text-align: center;"><?php echo $jmlPt1; ?></td>
                    <td style="text-align: center;"><?php echo $val['peminatPt2Pil1']; ?></td>
                    <td style="text-align: center;"><?php echo $val['peminatPt2Pil2']; ?></td>
                    <td style="text-align: center;"><?php echo $jmlPt2; ?></td>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($val['jml']); ?></td>
                </tr>
                <?php
                $no++;
            }
            ?>
        </tbody>
        <footer>
            <tr>
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($tot11); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($tot12); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($totPtn1); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($tot21); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($tot22); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($totPtn2); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($total); ?></td>
            </tr>
        </footer>
    </table>
</div>
