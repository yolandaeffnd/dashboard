<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\AppAsset;
use yii\helpers\Url;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$this->title = 'Ganti Foto Profil';
$this->params['breadcrumbs'][] = ['label' => 'Profil', 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-user-update">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="row" style="margin-right: 2px;border-radius: 4px;margin-bottom: 3px;">
                <div class="col-sm-6">
                    <?php echo $form->field($model, 'memberFoto')->fileInput()
                            ->hint('Silahkan upload foto anda dengan format .jpg/.jpeg/.png/.gif maksimal 200Kb.'); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['view']) . '";']); ?>
                <?php echo Html::submitButton(' Upload', ['class' => 'fa fa-upload btn btn-success']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>