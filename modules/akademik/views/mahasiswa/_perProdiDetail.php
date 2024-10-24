<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;
use app\components\Terbilang;
use app\modules\akademik\models\Mahasiswa;

$terbilang = new Terbilang();
$Terdaftar = new Mahasiwa();
$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
?>
<div class="box box-default collapsed-box-" style="margin-top:-20px;">
    <div class="box-header with-border">
        <h3 class="box-title">Detail</h3>

        <!--<div class="box-tools pull-right">-->
        <!--            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>-->
        <!--</div>-->
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30px;vertical-align: middle;">No</th>
                    <th rowspan="2" style="text-align: center;vertical-align: middle;">Program Studi</th>
                    <?php
                    for ($i = 0; $i < count($rsAkt); $i++) {
                        ?>
                        <th colspan="2" style="text-align: center"><?php echo $rsAkt[$i]; ?></th>
                        <?php
                    }
                    ?>
                    <th rowspan="2" style="width: 50px;text-align: center;vertical-align: middle;">Total</th>
                </tr>
                <tr>
                    <?php
                    for ($i = 0; $i < count($rsAkt); $i++) {
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
                        <td><?php echo $val['NAMA_PRODI']; ?></td>
                        <?php
                        for ($i = 0; $i < count($rsAkt); $i++) {
                            ?>
                            <td style="text-align: center;"><?php echo $Terdaftar->getJmlMhs('mhs-aktif-per-prodi', $rsAkt[$i], $val['KODE_PRODI'], 'L');       ?></td>
                            <td style="text-align: center;"><?php echo $Terdaftar->getJmlMhs('mhs-aktif-per-prodi', $rsAkt[$i], $val['KODE_PRODI'], 'P');       ?></td>
                            <?php
                        }
                        ?>
                        <td style="text-align: center;"><?php echo $terbilang->setCurrency($val['JML']); ?></td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
            </tbody>
            <footer>
                <tr>
                    <td colspan="2" rowspan="2" style="text-align: center;vertical-align: middle;">JUMLAH</td>
                    <?php
                    for ($i = 0; $i < count($rsAkt); $i++) {
                        ?>
                        <td style="text-align: center;"><?php echo $Terdaftar->getJmlMhs('mhs-aktif-per-prodi', $rsAkt[$i], null, 'L');       ?></td>
                        <td style="text-align: center;"><?php echo $Terdaftar->getJmlMhs('mhs-aktif-per-prodi', $rsAkt[$i], null, 'P');       ?></td>
                        <?php
                    }
                    ?>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;"><?php echo $terbilang->setCurrency($total);       ?></td>
                </tr>
                <tr>
                    <?php
                    for ($i = 0; $i < count($rsAkt); $i++) {
                        ?>
                        <td colspan="2" style="text-align: center;"><?php echo $Terdaftar->getJmlMhs('mhs-aktif-per-prodi', $rsAkt[$i], null, 'L')+$Terdaftar->getJmlMhs('mhs-aktif-per-prodi', $rsAkt[$i], null, 'P');       ?></td>
                        <?php
                    }
                    ?>
                </tr>
            </footer>
        </table>
    </div>
    <!-- /.box-body -->
</div>