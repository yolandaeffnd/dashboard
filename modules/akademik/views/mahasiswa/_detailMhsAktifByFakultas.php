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
<div style="overflow: scroll;height: 500px;">
    <table class="table table-bordered table-striped">
        <thead>
            <tr style="background-color: #dff0d8;">
                <th rowspan="2" style="width: 30px;vertical-align: middle;">No</th>
                <th rowspan="2" style="text-align: center;vertical-align: middle;">Fakultas</th>
                <?php
                for ($i = 0; $i < count($dataTabel['akt']); $i++) {
                    ?>
                    <th colspan="2" style="text-align: center">
                        <?php
                        echo ($dataTabel['akt'][$i] == $dataTabel['last_akt']) ? '<=' . $dataTabel['akt'][$i] : $dataTabel['akt'][$i];
                        ?>
                    </th>
                    <?php
                }
                ?>
                <th rowspan="2" style="width: 60px;text-align: center;vertical-align: middle;">Total</th>
            </tr>
            <tr style="background-color: #dff0d8;">
                <?php
                for ($i = 0; $i < count($dataTabel['akt']); $i++) {
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
            foreach ($dataTabel['fakultas'] as $val) {
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $val['fakNama']; ?></td>
                    <?php
                    for ($i = 0; $i < count($dataTabel['akt']); $i++) {
                        $jmlL = 0;
                        $jmlP = 0;
                        if (isset($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']])) {
                            if (is_array($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']])) {
                                $jmlL = array_sum($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']]);
                            } else {
                                $jmlL = $dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']];
                            }
                        }
                        if (isset($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']])) {
                            if (is_array($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']])) {
                                $jmlP = array_sum($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']]);
                            } else {
                                $jmlP = $dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']];
                            }
                        }
                        ?>
                        <td style="text-align: center;"><?php echo $terbilang->setNumber($jmlL); ?></td>
                        <td style="text-align: center;"><?php echo $terbilang->setNumber($jmlP); ?></td>
                        <?php
                    }
                    ?>
                    <td style="text-align: center;">
                        <?php
                        $tot = 0;
                        for ($i = 0; $i < count($dataTabel['akt']); $i++) {
                            $jmlL = 0;
                            $jmlP = 0;
                            if (isset($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']])) {
                                if (is_array($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']])) {
                                    $jmlL = array_sum($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']]);
                                } else {
                                    $jmlL = $dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']];
                                }
                            }
                            if (isset($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']])) {
                                if (is_array($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']])) {
                                    $jmlP = array_sum($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']]);
                                } else {
                                    $jmlP = $dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']];
                                }
                            }
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
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <?php
                for ($i = 0; $i < count($dataTabel['akt']); $i++) {
                    $subL = 0;
                    $subP = 0;
                    foreach ($dataTabel['fakultas'] as $val) {
                        $jmlL = 0;
                        $jmlP = 0;
                        if (isset($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']])) {
                            if (is_array($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']])) {
                                $jmlL = array_sum($dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']]);
                            } else {
                                $jmlL = $dataTabel['dimValue'][$dataTabel['akt'][$i]]['L'][$val['fakId']];
                            }
                        }
                        if (isset($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']])) {
                            if (is_array($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']])) {
                                $jmlP = array_sum($dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']]);
                            } else {
                                $jmlP = $dataTabel['dimValue'][$dataTabel['akt'][$i]]['P'][$val['fakId']];
                            }
                        }
                        $subL = $subL + $jmlL;
                        $subP = $subP + $jmlP;
                    }
                    ?>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($subL); ?></td>
                    <td style="text-align: center;"><?php echo $terbilang->setNumber($subP); ?></td>
                    <?php
                }
                ?>
                <td style="text-align: center;"><?php echo $terbilang->setNumber($total); ?></td>
            </tr>
        </footer>
    </table>
</div>
