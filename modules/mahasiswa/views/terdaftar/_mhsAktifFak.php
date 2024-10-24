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
use app\modules\mahasiswa\models\Fakultas;
use app\components\Terbilang;
use app\models\AppSemester;

$dataFakultas = Fakultas::findOne($model->fakId);
$semester = AppSemester::find()->where('smtIsAktif="1"')->one();
$inDate = new IndonesiaDate();
$terbilang = new Terbilang();
$Terdaftar = new Terdaftar();
?>
<div class="box" style="margin-top: -15px;margin-bottom: 10px;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-size: 16px;">Mahasiswa <?php echo $dataFakultas->fakNama; ?></h3>
        <div class="box-tools pull-right">
            <a href="<?php echo Url::to(['aktif-fakultas']); ?>" class="fa fa-reply btn btn-default btn-flat"> Kembali</a>
        </div>
    </div>
</div>
    <!--<div class="box-body" style="">-->
    <div class="row" style="">
            <div class="col-sm-10" style="padding-bottom: 10px;">
                <?php
                echo TabsX::widget([
                    'items' => [
                            [
                            'label' => '<i class="fa fa-pie-chart"></i> Jenjang',
                            'content' => $this->render('_mhsAktifFakPieJenjang', [
                                'dataPie' => $dataPie,
                                'fakId' =>$fakId
                            ]),
                            'active' => true
                        ],
                            [
                            'label' => '<i class="fa fa-pie-chart"></i> Program Studi',
                            'linkOptions' => ['data-url' => Url::to(['get-aktif-fak', 'act' => 'by-prodi', 'params' => urlencode(serialize(['fak'=>$model->fakId,'akt' => $arrAkt]))])]
                        ],
                            [
                            'label' => '<i class="fa fa-bar-chart"></i> Tahun Masuk',
                            'linkOptions' => ['data-url' => Url::to(['get-aktif-fak', 'act' => 'by-angkatan', 'params' => urlencode(serialize(['fak'=>$model->fakId,'akt' => $arrAkt]))])]
                        ],
                            [
                            'label' => '<i class="fa fa-bar-chart"></i> IPK Mahasiswa',
                            'linkOptions' => ['data-url' => Url::to(['get-aktif-fak', 'act' => 'by-ipk', 'params' => urlencode(serialize(['fak'=>$model->fakId,'akt' => $arrAkt]))])]
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
                <div class="small-box bg-green">
                    <span class="small-box-footer"><i class="fa fa-users"></i> Mahasiswa Aktif</span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlAktif); ?></h3>
                    </div>
                </div>
                <div class="small-box bg-yellow" style="margin-top: -15px;">
                    <span class="small-box-footer"><i class="fa fa-users"></i> Mahasiswa Cuti</span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlCuti); ?></h3>
                    </div>
                </div>
                <div class="small-box bg-red" style="margin-top: -15px;">
                    <span class="small-box-footer"><i class="fa fa-users"></i> Mahasiswa Non Aktif</span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlNonAktif); ?></h3>
                    </div>
                </div>
                <div class="small-box bg-purple" style="margin-top: -15px;">
                    <span class="small-box-footer"><i class="fa fa-users"></i> Total Mahasiswa</span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($totalMhs); ?></h3>
                    </div>
                </div>
                <div class="small-box bg-aqua" style="margin-top: -15px;">
                    <span class="small-box-footer"><i class="fa fa-user-plus"></i> Total Mahasiswa <b>Baru</b></span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlMabaTotal); ?></h3>
                        <p><?php echo '<b>T.A</b> ' . $semester->smtTahun; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="padding-bottom: 10px;">
            <div class="col-md-2">
                <div class="small-box bg-aqua" style="margin-top: -15px;margin-bottom: 0px;">
                    <span class="small-box-footer"><i class="fa fa-user-plus"></i> Mahasiswa <b>Baru</b></span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlMabaD3); ?></h3>
                        <p><?php echo '<b>D3<br/>T.A</b> ' . $semester->smtTahun; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box bg-aqua" style="margin-top: -15px;margin-bottom: 0px;">
                    <span class="small-box-footer"><i class="fa fa-user-plus"></i> Mahasiswa <b>Baru</b></span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlMabaS1); ?></h3>
                        <p><?php echo '<b>S1<br/>T.A</b> ' . $semester->smtTahun; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box bg-aqua" style="margin-top: -15px;margin-bottom: 0px;">
                    <span class="small-box-footer"><i class="fa fa-user-plus"></i> Mahasiswa <b>Baru</b></span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlMabaS2); ?></h3>
                        <p><?php echo '<b>S2<br/>T.A</b> ' . $semester->smtTahun; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box bg-aqua" style="margin-top: -15px;margin-bottom: 0px;">
                    <span class="small-box-footer"><i class="fa fa-user-plus"></i> Mahasiswa <b>Baru</b></span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlMabaS3); ?></h3>
                        <p><?php echo '<b>S3<br/>T.A</b> ' . $semester->smtTahun; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box bg-aqua" style="margin-top: -15px;margin-bottom: 0px;">
                    <span class="small-box-footer"><i class="fa fa-user-plus"></i> Mahasiswa <b>Baru</b></span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlMabaSp); ?></h3>
                        <p><?php echo '<b>Spesialis<br/>T.A</b> ' . $semester->smtTahun; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box bg-aqua" style="margin-top: -15px;margin-bottom: 0px;">
                    <span class="small-box-footer"><i class="fa fa-user-plus"></i> Mahasiswa <b>Baru</b></span>
                    <div class="inner" style="text-align: center;padding-top: 3px;padding-bottom: 3px;">
                        <h3><?php echo $terbilang->setCurrency($jmlMabaPro); ?></h3>
                        <p><?php echo '<b>Profesi<br/>T.A</b> ' . $semester->smtTahun; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo TabsX::widget([
                    'items' => [
//                            [
//                            'label' => '<i class="glyphicon glyphicon-tag"></i> Detail Berdasarkan Fakultas',
//                            'content' => '<i>Klik untuk menampilkan detail tabel</i>',
//                            'active' => false,
//                            'linkOptions' => ['data-url' => Url::to(['get-aktif-detail', 'act' => 'by-fakultas', 'params' => urlencode(serialize(['akt' => $arrAkt]))])]
//                        ],
                            [
                            'label' => '<i class="glyphicon glyphicon-tag"></i> Detail Berdasarkan Program Studi',
                            'content' => '<i>Klik untuk menampilkan detail tabel</i>',
                            'active' => false,
                            'linkOptions' => ['data-url' => Url::to(['get-aktif-fak-detail', 'act' => 'by-prodi', 'params' => urlencode(serialize(['fak'=>$model->fakId,'akt' => $arrAkt]))])]
                        ],
                    ],
                    'position' => TabsX::POS_ABOVE,
                    'bordered' => true,
                    'encodeLabels' => false,
                    'options' => ['style' => 'margin-top:0px;']
                ]);
                ?>
            </div>
        </div>
    <!--</div>-->
    <!--</div>-->
<!--</div>-->