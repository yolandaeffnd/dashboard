<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pembayaran\models\InvoiceBank */

$this->title = 'Update Invoice Bank: ' . $model->invoiceId;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->invoiceId, 'url' => ['view', 'id' => $model->invoiceId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoice-bank-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
