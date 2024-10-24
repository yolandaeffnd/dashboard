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
<div style="">
    <table class="table table-bordered table-striped">
        <thead>
            <tr style="background: #dff0d8">
                <th style="width: 80px;vertical-align: middle;">No</th>
                <th style="text-align: center;vertical-align: middle;">Fakultas/ Program Studi</th>
                <th style="width: 40px;text-align: center;vertical-align: middle;">Akreditasi</th>
                <th style="width: 250px;text-align: center;vertical-align: middle;">Nomor SK</th>
                <th style="width: 150px;text-align: center;vertical-align: middle;">Berlaku</th>
                <th style="width: 100px;text-align: center;vertical-align: middle;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            for ($i = 0; $i < count($dataTabel['fakultas']); $i++) {
                $fakId = $dataTabel['fakultas'][$i]['fakId'];
                $fakNama = $dataTabel['fakultas'][$i]['fakNama'];
                ?>
                <tr style="font-weight: bold;">
                    <td><?php echo $no; ?></td>
                    <td colspan="5"><?php echo $fakNama; ?></td>
                </tr>
                <?php
                $n = 1;
                foreach ($dataTabel['prodi'][$fakId] as $val) {
                    ?>
                    <tr>
                        <td style="text-align: right;"><?php echo $no . '.' . $n; ?></td>
                        <td><?php echo $val['prodiNama']; ?></td>
                        <td style="text-align: center;"><?php echo $val['prodiAkreditasi']; ?></td>
                        <td><?php echo $val['prodiAkreditasiSK']; ?></td>
                        <td><?php echo $inDate->setDate($val['prodiAkreditasiBerlaku']); ?></td>
                        <td>
                            <?php
                            if ($val['prodiAkreditasiStatus'] == 'Berlaku') {
                                ?>
                                <span class="label label-info">Berlaku</span>
                                <?php
                            } else {
                                ?>
                                <span class="label label-danger">Kadaluarsa</span>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $n++;
                }
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>