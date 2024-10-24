<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeserta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lat-peserta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pesertaId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pesertaKlsId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pesertaMemberId')->textInput() ?>

    <?= $form->field($model, 'pesertaSkorTerakhirTest')->textInput() ?>

    <?= $form->field($model, 'pesertaCreate')->textInput() ?>

    <?= $form->field($model, 'pesertaUpdate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
