<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;
use app\models\AppSemester;

$inDate = new IndonesiaDate();


$this->title = "Lulusan Universitas Andalas";
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
        <div class="box-tools pull-right">
            <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
            <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
        </div>
    </div>
    <div class="box-body">
        <?php
        echo TabsX::widget([
            'items' => [
                    [
                    'label' => '<i class="fa fa-bar-chart"></i> Berdasarkan Tahun Lulus',
                    'content' => $this->render('_unandByTahunLulus', [
                        'dataCategories' => $dataCategories,
                        'dataSeries' => $dataSeries,
                        'dataTabel' => $dataTabel,
                    ]),
                    'active' => true
                ],
                    [
                    'label' => '<i class="fa fa-bar-chart"></i> Berdasarkan Angkatan',
                    'linkOptions' => ['data-url' => Url::to(['unand-by', 'act' => 'by-tahun-masuk', 'params' => urlencode(serialize(['akt' => '']))])]
                ],
            ],
            'position' => TabsX::POS_ABOVE,
            'bordered' => true,
            'encodeLabels' => false,
            'options' => ['style' => 'margin-top:-5px;']
        ]);
        ?>
        <?php
//        echo $this->render('_unandGrafikCol', [
//            'dataCategories' => $dataCategories,
//            'dataSeries' => $dataSeries,
//        ]);
//
//        echo $this->render('_unandTabel', [
//            'dataTabel' => $dataTabel,
//        ]);
        ?>
    </div>
</div>