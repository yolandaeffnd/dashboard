<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use app\models\AppUserData;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\report\models\Stock */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Angka Efesiensi Edukasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-create">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 5px;">
                        <div class="panel-heading" style="padding-top:8px;padding-bottom:8px;">
                            <h5 style="margin: 0px;"><i>Berdasarkan Jenjang Pendidikan Sarjana (S1)</i></h5>
                        </div>
                        <div class="panel-body center" style="">
                            <div style="overflow: scroll;height: 800px;">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="background-color: #dff0d8;">
                                            <th rowspan="2" style="width: 50px;vertical-align: middle;">NO</th>
                                            <th rowspan="2" style="text-align: center;vertical-align: middle;">PROGRAM STUDI</th>
                                            <?php
                                            for ($x = 0; $x < count($data['AEE_S1_TAHUN']); $x++) {
                                                $tahun = $data['AEE_S1_TAHUN'][$x];
                                                ?>
                                                <th colspan="3" style="text-align: center;"><?php echo $tahun; ?></th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        <tr style="background-color: #dff0d8;">
                                            <?php
                                            for ($x = 0; $x < count($data['AEE_S1_TAHUN']); $x++) {
                                                $tahun = $data['AEE_S1_TAHUN'][$x];
                                                ?>
                                                <th style="width: 50px;text-align: center;">SB</th>
                                                <th style="width: 50px;text-align: center;">LULUS</th>
                                                <th style="width: 50px;text-align: center;">AEE</th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $noB = 1;
                                        for ($a = 0; $a < count($data['AEE_S1_FAKULTAS']); $a++) {
                                            $fakId = $data['AEE_S1_FAKULTAS'][$a]['id'];
                                            $fakNama = $data['AEE_S1_FAKULTAS'][$a]['nama'];
                                            ?>
                                            <tr>
                                                <td colspan="<?php echo 2 + (count($data['AEE_S1_TAHUN']) * 3); ?>" style="text-align: left;"><b><?php echo $fakNama; ?></b></td>
                                            </tr>
                                            <?php
                                            
                                            for ($b = 0; $b < count($data['AEE_S1_PRODI'][$fakId]); $b++) {
                                                $prodiId = $data['AEE_S1_PRODI'][$fakId][$b]['id'];
                                                $prodiNama = $data['AEE_S1_PRODI'][$fakId][$b]['nama'];
                                                ?>
                                                <tr>
                                                    <td style="text-align: right;"><?php echo $noB; ?></td>
                                                    <td><?php echo $prodiNama; ?></td>
                                                    <?php
                                                    for ($x = 0; $x < count($data['AEE_S1_TAHUN']); $x++) {
                                                        $tahun = $data['AEE_S1_TAHUN'][$x];
                                                        for ($c = 0; $c < count($data['AEE_S1_NILAI'][$fakId][$prodiId][$tahun]); $c++) {
                                                            $jmlSB = $data['AEE_S1_NILAI'][$fakId][$prodiId][$tahun][$c]['sb'];
                                                            $jmlLulus = $data['AEE_S1_NILAI'][$fakId][$prodiId][$tahun][$c]['lulus'];
                                                            $jmlAEE = $data['AEE_S1_NILAI'][$fakId][$prodiId][$tahun][$c]['aee'];
                                                            ?>
                                                            <td style="text-align: center;"><?php echo $jmlSB; ?></td>
                                                            <td style="text-align: center;"><?php echo $jmlLulus; ?></td>
                                                            <td style="text-align: center;"><?php echo $jmlAEE; ?></td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                $noB++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <footer>

                                    </footer>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 5px;">
                        <div class="panel-heading" style="padding-top:8px;padding-bottom:8px;">
                            <h5 style="margin: 0px;"><i>Berdasarkan Jenjang Pendidikan Magister (S2)</i></h5>
                        </div>
                        <div class="panel-body center" style="">
                            <div style="overflow: scroll;height: 800px;">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="background-color: #dff0d8;">
                                            <th rowspan="2" style="width: 50px;vertical-align: middle;">NO</th>
                                            <th rowspan="2" style="text-align: center;vertical-align: middle;">PROGRAM STUDI</th>
                                            <?php
                                            for ($x = 0; $x < count($data['AEE_S2_TAHUN']); $x++) {
                                                $tahun = $data['AEE_S2_TAHUN'][$x];
                                                ?>
                                                <th colspan="3" style="text-align: center;"><?php echo $tahun; ?></th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        <tr style="background-color: #dff0d8;">
                                            <?php
                                            for ($x = 0; $x < count($data['AEE_S2_TAHUN']); $x++) {
                                                $tahun = $data['AEE_S2_TAHUN'][$x];
                                                ?>
                                                <th style="width: 50px;text-align: center;">SB</th>
                                                <th style="width: 50px;text-align: center;">LULUS</th>
                                                <th style="width: 50px;text-align: center;">AEE</th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $noB = 1;
                                        for ($a = 0; $a < count($data['AEE_S2_FAKULTAS']); $a++) {
                                            $fakId = $data['AEE_S2_FAKULTAS'][$a]['id'];
                                            $fakNama = $data['AEE_S2_FAKULTAS'][$a]['nama'];
                                            ?>
                                            <tr>
                                                <td colspan="<?php echo 2 + (count($data['AEE_S2_TAHUN']) * 3); ?>" style="text-align: left;"><b><?php echo $fakNama; ?></b></td>
                                            </tr>
                                            <?php
                                            
                                            for ($b = 0; $b < count($data['AEE_S2_PRODI'][$fakId]); $b++) {
                                                $prodiId = $data['AEE_S2_PRODI'][$fakId][$b]['id'];
                                                $prodiNama = $data['AEE_S2_PRODI'][$fakId][$b]['nama'];
                                                ?>
                                                <tr>
                                                    <td style="text-align: right;"><?php echo $noB; ?></td>
                                                    <td><?php echo $prodiNama; ?></td>
                                                    <?php
                                                    for ($x = 0; $x < count($data['AEE_S2_TAHUN']); $x++) {
                                                        $tahun = $data['AEE_S2_TAHUN'][$x];
                                                        if (isset($data['AEE_S2_NILAI'][$fakId][$prodiId][$tahun])) {
                                                            for ($c = 0; $c < count($data['AEE_S2_NILAI'][$fakId][$prodiId][$tahun]); $c++) {
                                                                $jmlSB = $data['AEE_S2_NILAI'][$fakId][$prodiId][$tahun][$c]['sb'];
                                                                $jmlLulus = $data['AEE_S2_NILAI'][$fakId][$prodiId][$tahun][$c]['lulus'];
                                                                $jmlAEE = $data['AEE_S2_NILAI'][$fakId][$prodiId][$tahun][$c]['aee'];
                                                                ?>
                                                                <td style="text-align: center;"><?php echo $jmlSB; ?></td>
                                                                <td style="text-align: center;"><?php echo $jmlLulus; ?></td>
                                                                <td style="text-align: center;"><?php echo $jmlAEE; ?></td>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <td style="text-align: center;">-</td>
                                                            <td style="text-align: center;">-</td>
                                                            <td style="text-align: center;">-</td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                $noB++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <footer>

                                    </footer>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>