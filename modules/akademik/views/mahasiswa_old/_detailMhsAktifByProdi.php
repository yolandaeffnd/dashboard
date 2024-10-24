<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;
use app\modules\akademik\models\Mahasiswa;
use app\components\Terbilang;

$inDate = new IndonesiaDate();
$terbilang = new Terbilang();
$Terdaftar = new Mahasiswa();

/* @var $this yii\web\View */
?>
<div style="overflow: scroll;height: 500px;">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;vertical-align: middle;">No</th>
                <th rowspan="2" style="text-align: center;vertical-align: middle;">Program Studi</th>
                <?php
                foreach ($dataTabel_akt as $val) {
                    ?>
                    <th colspan="2" style="text-align: center"><?php echo ($val['AKT']==$val['LAST_AKT'])?'<='.$val['AKT']:$val['AKT']; ?></th>
                    <?php
                }
                ?>
                <th rowspan="2" style="width: 50px;text-align: center;vertical-align: middle;">Total</th>
            </tr>
            <tr>
                <?php
                foreach ($dataTabel_akt as $val) {
                    ?>
                    <th style="text-align: center">L</th>
                    <th style="text-align: center">P</th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            foreach ($dataTabel as $val) {
                $total = $total + $val['JML'];
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $val['PRODI_NAMA']; ?></td>
                    <?php
                    foreach ($dataTabel_akt as $valAkt) {
                        ?>
                        <td style="text-align: center;"><?php echo $terbilang->setNumber($Terdaftar->getJmlPerJenkel('by-prodi', $valAkt['AKT'], $val['PRODI_KODE'], 'L')); ?></td>
                        <td style="text-align: center;"><?php echo $terbilang->setNumber($Terdaftar->getJmlPerJenkel('by-prodi', $valAkt['AKT'], $val['PRODI_KODE'], 'P')); ?></td>
                        <?php
                    }
                    ?>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($val['JML']); ?></td>
                </tr>
                <?php
                $no++;
            }
            ?>
        </tbody>
        <footer>
            <tr>
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <?php
                foreach ($dataTabel_akt as $valAkt) {
                    ?>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($Terdaftar->getJmlPerJenkel('by-prodi', $valAkt['AKT'], null, 'L')); ?></td>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($Terdaftar->getJmlPerJenkel('by-prodi', $valAkt['AKT'], null, 'P')); ?></td>
                    <?php
                }
                ?>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($total); ?></td>
            </tr>
        </footer>
    </table>
</div>
