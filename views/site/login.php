<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<style>
        .left-aligned-box {
            width: 300px; /* Sesuaikan dengan kebutuhan Anda */
            padding: 10px;
            border: 1px solid #000;
            text-align: left;
            background-color: white;
            position: absolute; /* Menetapkan posisi absolut */
            top: 10px; /* Menempatkan di atas */
            left: 10px; /* Menempatkan di kiri */
        }
    </style>
 <!-- <div class="left-aligned-box">
 <div class="box-body">
            <h3 class="profile-username text-center" style="font-weight: bold;">Chart Universitas Andalas</h3>
            <div class="login-box-body" style="margin-top: 20px;">
            <ul>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['tag' => false], // Disable default wrapping tag
                'layout' => "{items}\n{pager}",
                'itemView' => function ($model, $key, $index, $widget) {
                    // Buat URL untuk setiap item
                    $url = Url::to(['view', 'id' => $model->idChart]);
                    // $url = Url::to($model->url_chart);
                    // Render setiap item dalam tag <li> yang berisi tag <a> dengan URL yang dihasilkan
                    return Html::tag('li', Html::a(Html::encode($model->nama_chart), $url, ['target' => '_blank']));
                },
            ]); ?>
            </ul>

               
            </div>
        </div>
    </div> -->



<div class="login-box" style="margin-top: 15%;">
    <div class="box box-success">
        <div class="box-body">
            <div class="profile-user-img img-responsive img-circle" style="text-align: center;">
                <?php echo Html::img(AppAsset::register($this)->baseUrl . '/images/logo-header.png', ['style' => 'width:50%;text-align:center;']); ?> 
            </div>
            <h3 class="profile-username text-center" style="font-weight: bold;">Dashboard Universitas Andalas</h3>
            <div class="login-box-body" style="margin-top: 20px;">

                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true, 'options' => ['style' => 'margin-top:-15px;']]); ?>

                <?php
                echo $form
                        ->field($model, 'username', $fieldOptions1)
                        ->label(false)
                        ->textInput(['placeholder' => $model->getAttributeLabel('username')])
                ?>

                <?php
                echo $form
                        ->field($model, 'password', $fieldOptions2)
                        ->label(false)
                        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
                ?>

                <div class="row" style="margin-bottom: -10px;">
                    <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;text-align: center;">
                        <?= Html::submitButton(' Masuk', ['class' => 'fa fa-key btn btn-primary btn-flat', 'name' => 'login-button']); ?>
                    </div>
                </div>


                <?php ActiveForm::end(); ?>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
<!-- /.login-box-body -->
