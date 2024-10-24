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
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\report\models\Stock */
/* @var $form yii\widgets\ActiveForm */

$this->title =  $judul;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-create">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
            <!-- <h3 class="box-title"><b>Yolanda</b></h3> -->
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <!-- <div class="callout callout-info">
                <p>
                    Halaman ini akan menampilkan data mahasiswa terdaftar 7 (tujuh) tahun terakhir tiap fakultas. 
                </p>
            </div> -->
            <div class="audit-form">
                <div id="reportContainer" class="dds__d-none" style="height:804px; overflow-y:hidden">
                    <iframe id="reportFrame"  title="test_dashboard_2" onload="powerBiLoaded()" frameborder="0" seamless="seamless" class="viewer pbi-frame" style=" width: 100%; height: 840px;" src=<?= $url_cart ?>>
                    </iframe>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
   

</div>