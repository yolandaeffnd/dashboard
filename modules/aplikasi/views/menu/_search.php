<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppMenuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idMenu') ?>

    <?= $form->field($model, 'parentId') ?>

    <?= $form->field($model, 'labelMenu') ?>

    <?= $form->field($model, 'urlModule') ?>

    <?= $form->field($model, 'controllerName') ?>

    <?php // echo $form->field($model, 'isAktif') ?>

    <?php // echo $form->field($model, 'isSubAction') ?>

    <?php // echo $form->field($model, 'noUrut') ?>

    <?php // echo $form->field($model, 'iconMenu') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
