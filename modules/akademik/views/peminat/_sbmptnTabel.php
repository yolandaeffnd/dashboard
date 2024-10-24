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
                <th rowspan="2" style="width: 80px;text-align: center;vertical-align: middle;">Pilihan 1</th>
                <th rowspan="2" style="width: 80px;text-align: center;vertical-align: middle;">Pilihan 2</th>
                <th rowspan="2" style="width: 80px;text-align: center;vertical-align: middle;">Pilihan 3</th>
                <th rowspan="2" style="width: 100px;text-align: center;vertical-align: middle;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            $tot1 = 0;
            $tot2 = 0;
            $tot3 = 0;
            foreach ($dataTabel as $val) {
                $total = $total + $val['jml'];
                $tot1 = $tot1 + $val['peminatPil1'];
                $tot2 = $tot2 + $val['peminatPil2'];
                $tot3 = $tot3 + $val['peminatPil3'];
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $val['namaProdi']; ?></td>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($val['peminatPil1']); ?></td>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($val['peminatPil2']); ?></td>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($val['peminatPil3']); ?></td>
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
                <td style="text-align: center;"><?php echo $terbilang->setNumber($tot1); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($tot2); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($tot3); ?></td>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($total); ?></td>
            </tr>
        </footer>
    </table>
</div>
