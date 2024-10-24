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
use app\modules\akademik\models\Fakultas;
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\report\models\Stock */
/* @var $form yii\widgets\ActiveForm */

$this->title =  $judul;
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
        .bg-body{
            background-image: url('eofficeimgs/bg-body.jpg');
            background-size: 100%;
            background-position: 0 0;
            background-repeat: repeat;
            background-repeat: no-repeat;
            background-position: center;
            -webkit-background-size: 100% 100%;
            -moz-background-size: 100% 100%;
            -o-background-size: 100% 100%;
            background-size: 100% 100%;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 10px;
            width: 98%;
            height: max-content;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1)
        }
        .content {
            min-height: 200px;
            padding: 3px;
            margin-right: auto;
            margin-left: -4%;
            /* padding-left: 3px;
            padding-right: 3px; */
            width: 110%;	
        }



    </style>

<div class="audit-create">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
            <!-- <h3 class="box-title"><b>Yolanda</b></h3> -->
            <div class="box-tools pull-right" style="margin-top:3px;">
                <a href="<?= Url::home(); ?>" class="btn btn-primary btn-xs" ><i class="fa fa-home"></i> Home</a>
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <div class="audit-form">
                <div id="reportContainer" class="dds__d-none" style="height:804px; overflow-y:hidden">
                    <iframe id="reportFrame"  title="test_dashboard_2" onload="powerBiLoaded()" frameborder="0" seamless="seamless" class="viewer pbi-frame" style=" width: 100%; height: 840px;" src=<?= $url_cart ?>>
                    </iframe>
                </div>
            </div>


       