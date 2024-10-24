<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use miloschuman\highcharts\Highcharts;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
?>
<div class="box box-default collapsed-box" style="margin-top:-20px;">
    <div class="box-header with-border">
        <h3 class="box-title">Detail</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        The body of the box
    </div>
    <!-- /.box-body -->
</div>