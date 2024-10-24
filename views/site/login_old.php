<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;

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

<div class="login-box" style="margin-top: 100px;">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><span class="fa fa-key"></span> Login</b></h3>
            <div class="box-tools pull-right">
              <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
              <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <?php echo Html::img(AppAsset::register($this)->baseUrl . '/images/login-header.jpg',['style'=>'width:100%']); ?>
            <div class="login-box-body">
                
                <!--<p class="login-box-msg">Silahkan Login Terlebih Dahulu.</p>-->

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

                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-xs-8">
                        <?= $form->field($model, 'rememberMe',['options'=>['style'=>'margin-top:-8px;padding-bottom:0x;']])->checkbox() ?>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4" style="padding-left: 0px;padding-right: 0px;text-align: center;">
                        <?= Html::submitButton(' Sign In', ['class' => 'fa fa-key btn btn-primary btn-flat', 'name' => 'login-button']); ?>
                    </div>
                    <!-- /.col -->
                </div>


                <?php ActiveForm::end(); ?>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <!--        <div class="social-auth-links text-center">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in
                    using Facebook</a>
                <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign
                    in using Google+</a>
            </div>-->
    <!-- /.social-auth-links -->

    <!--<a href="#">I forgot my password</a><br>-->
    <!--<a href="register.html" class="text-center">Register a new membership</a>-->

</div>
<!-- /.login-box-body -->
