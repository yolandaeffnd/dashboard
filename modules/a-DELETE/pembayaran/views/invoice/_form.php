<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pembayaran\models\InvoiceBank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-bank-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoicePesertaId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoiceNama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoiceJnsBiayaId')->textInput() ?>

    <?= $form->field($model, 'invoiceUraian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoiceJumlah')->textInput() ?>

    <?= $form->field($model, 'invoiceBankId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoiceBuktiBayar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoiceTglBayar')->textInput() ?>

    <?= $form->field($model, 'invoiceTglReversal')->textInput() ?>

    <?= $form->field($model, 'invoiceFlag')->dropDownList([ '0', '1', '2', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'invoiceTglBerlaku')->textInput() ?>

    <?= $form->field($model, 'invoiceCreate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
