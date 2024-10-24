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
<div class="lat-periode-view" style="margin-top: -15px;">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title" style="font-size: 16px;"><?= Html::encode('Grafik'); ?></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body" style="">
            <div class="row">
                <div class="col-sm-10" style="padding-bottom: 10px;">
                    <?php
                    echo TabsX::widget([
                        'items' => [
                                [
                                'label' => '<i class="glyphicon glyphicon-tag"></i> Berdasarkan Jenjang Pendidikan',
                                'content' => $this->render('_mhsAktifPieJenjang', [
                                    'dataPie' => $dataPie,
                                ]),
                                'active' => true
                            ],
                                [
                                'label' => '<i class="glyphicon glyphicon-tag"></i> Berdasarkan Fakultas',
                                'linkOptions' => ['data-url' => Url::to(['get-aktif', 'act' => 'by-fakultas', 'params' => urlencode(serialize(['akt' => $arrAkt]))])]
                            ],
                                [
                                'label' => '<i class="glyphicon glyphicon-tag"></i> Berdasarkan Program Studi',
                                'linkOptions' => ['data-url' => Url::to(['get-aktif', 'act' => 'by-prodi', 'params' => urlencode(serialize(['akt' => $arrAkt]))])]
                            ],
                                [
                                'label' => '<i class="glyphicon glyphicon-tag"></i> Berdasarkan Angkatan',
                                'linkOptions' => ['data-url' => Url::to(['get-aktif', 'act' => 'by-angkatan', 'params' => urlencode(serialize(['akt' => $arrAkt]))])]
                            ],
                        ],
                        'position' => TabsX::POS_ABOVE,
                        'bordered' => true,
                        'encodeLabels' => false,
                        'options' => ['style' => 'margin-top:5px;']
                    ]);
                    ?>
                </div>
                <div class="col-sm-2" style="">
                    <div class="small-box bg-aqua">
                        <div class="inner" style="text-align: center;padding-bottom: 0px;">
                            <h3><?php echo $terbilang->setCurrency($jmlAktif); ?></h3>
                        </div>
                        <span class="small-box-footer"><i class="fa fa-users"></i> Mahasiswa Aktif</span>
                    </div>
                    <div class="small-box bg-yellow">
                        <div class="inner" style="text-align: center;padding-bottom: 0px;">
                            <h3><?php echo $terbilang->setCurrency($jmlCuti); ?></h3>
                        </div>
                        <span class="small-box-footer"><i class="fa fa-users"></i> Mahasiswa Cuti</span>
                    </div>
                    <div class="small-box bg-red">
                        <div class="inner" style="text-align: center;padding-bottom: 0px;">
                            <h3><?php echo $terbilang->setCurrency($jmlNonAktif); ?></h3>
                        </div>
                        <span class="small-box-footer"><i class="fa fa-users"></i> Mahasiswa Non Aktif</span>
                    </div>
                    <div class="small-box bg-green">
                        <div class="inner" style="text-align: center;padding-bottom: 0px;">
                            <h3><?php echo $terbilang->setCurrency($totalMhs); ?></h3>
                        </div>
                        <span class="small-box-footer"><i class="fa fa-users"></i> Total Mahasiswa</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo TabsX::widget([
                        'items' => [
                            [
                                'label' => '<i class="glyphicon glyphicon-tag"></i> Detail Berdasarkan Angkatan',
                                'content' => '<i>Klik untuk menampilkan detail tabel</i>',
                                'active' => false,
                                'linkOptions' => ['data-url' => Url::to(['get-aktif-detail', 'act' => 'by-angkatan', 'params' => urlencode(serialize(['akt' => $arrAkt]))])]
                            ],
                                [
                                'label' => '<i class="glyphicon glyphicon-tag"></i> Detail Berdasarkan Fakultas',
                                'content' => '<i>Klik untuk menampilkan detail tabel</i>',
                                'active' => false,
                                'linkOptions' => ['data-url' => Url::to(['get-aktif-detail', 'act' => 'by-fakultas', 'params' => urlencode(serialize(['akt' => $arrAkt]))])]
                            ],
                                [
                                'label' => '<i class="glyphicon glyphicon-tag"></i> Detail Berdasarkan Program Studi',
                                'content' => '<i>Klik untuk menampilkan detail tabel</i>',
                                'active' => false,
                                'linkOptions' => ['data-url' => Url::to(['get-aktif-detail', 'act' => 'by-prodi', 'params' => urlencode(serialize(['akt' => $arrAkt]))])]
                            ],
                        ],
                        'position' => TabsX::POS_ABOVE,
                        'bordered' => true,
                        'encodeLabels' => false,
                        'options' => ['style' => 'margin-top:0px;']
                    ]);
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
                                        <th colspan="2" style="text-align: center"><?php echo $val['AKT']; ?></th>
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
                                        <td><?php echo $val['NAMA_PRODI']; ?></td>
                                        <?php
                                        foreach ($dataTabel_akt as $valAkt) {
                                            ?>
                                            <td style="text-align: center;"><?php echo $Terdaftar->getJmlPerJenkel($valAkt['AKT'], $val['KODE_PRODI'], 'L'); ?></td>
                                            <td style="text-align: center;"><?php echo $Terdaftar->getJmlPerJenkel($valAkt['AKT'], $val['KODE_PRODI'], 'P'); ?></td>
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
                                    <td colspan="2" style="text-align: center;">TOTAL</td>
                                    <?php
                                    foreach ($dataTabel_akt as $valAkt) {
                                        ?>
                                        <td style="text-align: center;"><?php echo $Terdaftar->getJmlPerJenkel($valAkt['AKT'], null, 'L'); ?></td>
                                        <td style="text-align: center;"><?php echo $Terdaftar->getJmlPerJenkel($valAkt['AKT'], null, 'P'); ?></td>
                                        <?php
                                    }
                                    ?>
                                    <td style="text-align: center;"><?php echo $terbilang->setCurrency($total); ?></td>
                                </tr>
                            </footer>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>