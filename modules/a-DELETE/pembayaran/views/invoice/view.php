<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pembayaran\models\InvoiceBank */

$this->title = $model->invoiceId;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-bank-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->invoiceId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->invoiceId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'invoiceId',
            'invoicePesertaId',
            'invoiceNama',
            'invoiceJnsBiayaId',
            'invoiceUraian',
            'invoiceJumlah',
            'invoiceBankId',
            'invoiceBuktiBayar',
            'invoiceTglBayar',
            'invoiceTglReversal',
            'invoiceFlag',
            'invoiceTglBerlaku',
            'invoiceCreate',
        ],
    ]) ?>

</div>
