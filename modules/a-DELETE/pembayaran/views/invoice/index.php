<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pembayaran\models\InvoiceBankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoice Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-bank-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Invoice Bank', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'invoiceId',
            'invoicePesertaId',
            'invoiceNama',
            'invoiceJnsBiayaId',
            'invoiceUraian',
            // 'invoiceJumlah',
            // 'invoiceBankId',
            // 'invoiceBuktiBayar',
            // 'invoiceTglBayar',
            // 'invoiceTglReversal',
            // 'invoiceFlag',
            // 'invoiceTglBerlaku',
            // 'invoiceCreate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
