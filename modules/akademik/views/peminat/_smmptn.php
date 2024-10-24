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

$inDate = new IndonesiaDate();
$terbilang = new Terbilang();
$Terdaftar = new Terdaftar();
?>
<div class="box" style="margin-top: -15px;margin-bottom: 10px;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-size: 16px;">Tahun <?php echo $model->thnAkt; ?></h3>
        <div class="box-tools pull-right">
            <a href="<?php echo Url::to(['smmptn']); ?>" class="fa fa-reply btn btn-default btn-flat"> Kembali</a>
        </div>
    </div>
</div>
<div class="row" style="padding-right: 10px;">
    <div class="col-sm-12" style="padding-bottom: 10px;">
        <?php
        echo $this->render('_smmptnGrafikCol', [
            'model' => $model,
            'dataCategories' => $dataCategories,
            'dataSeries' => $dataSeries,
        ]);
        
        echo $this->render('_smmptnTabel', [
            'model' => $model,
            'dataTabel' => $dataTabel,
        ]);
        ?>
    </div>
</div>