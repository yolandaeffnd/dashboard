<?php

use app\models\IndonesiaDate;
use app\components\AppVersion;
use yii\helpers\Url;

$version = new AppVersion();
$inDate = new IndonesiaDate();


/* @var $this yii\web\View */

$this->title = 'Home';
?>
<div class="site-index">

    <div class="row">
        <div class="col-lg-12">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner" style="text-align: center;">
                    <p style="font-size: 30px;"><i>Selamat Datang, <?php echo Yii::$app->user->identity->userNama; ?>!</i></p>
                    <p class="lead" style="font-size: 20px;font-style: italic;margin: 0px;margin-top:-5px;"><?php echo Yii::$app->name . ' - ' . $version->companyName(); ?></p>
                    <!--<p>New Orders</p>-->
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <span class="small-box-footer"><b><i class="fa fa-arrow-circle-right"></i> <?php echo 'Hari ini tanggal  ' . $inDate->setDate($inDate->getNOw()) . ' pukul ' . $inDate->setTime($inDate->getNow()); ?></b></span>
            </div>
        </div><!-- ./col -->
    </div>
    <div class="body-content">
        <div class="row">
            <section id="dashboard-kiri-satu" class="col-lg-7 connectedSortable" style="min-height: 0px;">
                <!-- Agenda -->
            </section>
            <section id="dashboard-kanan-dua" class="col-lg-5 connectedSortable" style="min-height: 0px;">
                <!-- Notifikasi Inbox Naskah -->
            </section>
            <section id="dashboard-kanan-satu" class="col-lg-5 connectedSortable" style="min-height: 0px;">
                <!-- Member Online -->
            </section>

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