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
                <th rowspan="2" style="width: 150px;text-align: center;vertical-align: middle;">Pilihan 1</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $tot1 = 0;
            foreach ($dataTabel as $val) {
                $tot1 = $tot1 + $val['peminatPil1'];
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $val['namaProdi']; ?></td>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($val['peminatPil1']); ?></td>
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
            </tr>
        </footer>
    </table>
</div>
