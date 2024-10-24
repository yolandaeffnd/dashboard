<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pembayaran\models\InvoiceBankSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-bank-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'invoiceId') ?>

    <?= $form->field($model, 'invoicePesertaId') ?>

    <?= $form->field($model, 'invoiceNama') ?>

    <?= $form->field($model, 'invoiceJnsBiayaId') ?>

    <?= $form->field($model, 'invoiceUraian') ?>

    <?php // echo $form->field($model, 'invoiceJumlah') ?>

    <?php // echo $form->field($model, 'invoiceBankId') ?>

    <?php // echo $form->field($model, 'invoiceBuktiBayar') ?>

    <?php // echo $form->field($model, 'invoiceTglBayar') ?>

    <?php // echo $form->field($model, 'invoiceTglReversal') ?>

    <?php // echo $form->field($model, 'invoiceFlag') ?>

    <?php // echo $form->field($model, 'invoiceTglBerlaku') ?>

    <?php // echo $form->field($model, 'invoiceCreate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
