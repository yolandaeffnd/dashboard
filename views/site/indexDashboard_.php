<?php

use app\models\IndonesiaDate;
use app\components\AppVersion;
use yii\helpers\Url;

$version = new AppVersion();
$inDate = new IndonesiaDate();


/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<div class="site-index">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Statistik Jumlah Mahasiswa Baru SNMPTN</h3>
        </div>
        <div class="box-body">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 style="font-size: 26px;margin-bottom: 0px;">L : 712 Org</h3>
                        <h3 style="font-size: 26px;margin-bottom: 0px;">P : 787 Org</h3>
                        <p style="margin-top: 5px;margin-bottom: 0px;">Jumlah Diterima</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 style="font-size: 26px;margin-bottom: 0px;">L : 700 Org</h3>
                        <h3 style="font-size: 26px;margin-bottom: 0px;">P : 780 Org</h3>
                        <p style="margin-top: 5px;margin-bottom: 0px;">Jumlah Mendaftar Ulang</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 style="font-size: 26px;margin-bottom: 0px;">L : 12 Org</h3>
                        <h3 style="font-size: 26px;margin-bottom: 0px;">P : 7 Org</h3>
                        <p style="margin-top: 5px;margin-bottom: 0px;">Jumlah Tidak Mendaftar Ulang</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>1500 Org</h3>

                        <p>Total Diterima</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Pembayaran</h3>
        </div>
        <div class="box-body">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>150</h3>

                        <p>New Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>53<sup style="font-size: 20px">%</sup></h3>

                        <p>Bounce Rate</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>44</h3>

                        <p>User Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>65</h3>

                        <p>Unique Visitors</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php
//if (!empty($member)) {
//    //Load Agenda
//    $urlKiriSatu = Url::to(['dashboard/getagenda']);
//    //Load Member Online
//    $urlKananSatu = Url::to(['dashboard/getmemberonline']);
//    //Load Notifikasi Inbox
//    $urlKananDua = Url::to(['dashboard/getnotifikasiinbox']);
//    $js = <<< JS
//    //Load Agenda
//    $('#dashboard-kiri-satu').load('{$urlKiriSatu}');
//    //Load Member Online
//    $('#dashboard-kanan-satu').load('{$urlKananSatu}');
//    //Load Notifikasi inbox
//    $('#dashboard-kanan-dua').load('{$urlKananDua}');
//    setInterval(function(){
//        //Load Agenda
//        $('#dashboard-kiri-satu').load('{$urlKiriSatu}');
//        //Load Member Online
//        $('#dashboard-kanan-satu').load('{$urlKananSatu}');
//        //Load Notifikasi inbox
//        $('#dashboard-kanan-dua').load('{$urlKananDua}');
//    },9000);
//JS;
//    $this->registerJs($js);
//}
    ?>