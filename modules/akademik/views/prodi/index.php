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
use app\models\AppSemester;

$semester = AppSemester::find()->where('smtIsAktif="1"')->one();
$inDate = new IndonesiaDate();
$terbilang = new Terbilang();

/* @var $this yii\web\View */
$this->title = 'Program Studi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-periode-view" style="">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title" style=""><?= Html::encode($this->title); ?></h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body" style="">
            <div class="row">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        <p>
                            Halaman ini menampilkan data Program Studi dan Akreditasinya. 
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo TabsX::widget([
                        'items' => [
                                [
                                'label' => '<i class="fa fa-bar-chart"></i> Berdasarkan Fakultas',
                                'content' => $this->render('_prodiPerFakultas', [
                                    'dataCategories' => $dataCategories,
                                    'dataSeries' => $dataSeries,
                                    'dataTabel' => $dataTabel
                                ]),
                                ' active' => true
                            ],
                                [
                                'label' => '<i class = "fa fa-bar-chart"></i> Berdasarkan Akreditasi',
                                'linkOptions' => ['data-url' => Url::to(['index-by', 'act' => 'by-akreditasi', 'params' => urlencode(serialize(['akt' => '']))])]
                            ],
                                [
                                'label' => '<i class = "fa fa-table"></i> Berdasarkan Masa Berlaku Akreditasi',
                                'linkOptions' => ['data-url' => Url::to(['index-by', 'act' => 'by-masa-berlaku', 'params' => urlencode(serialize(['akt' => '']))])]
                            ],
                        ],
                        'position' => TabsX::POS_ABOVE,
                        'bordered' => true,
                        'encodeLabels' => false,
                        'options' => ['style' => 'margin-top:-10px;']
                    ]);
                    ?>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>