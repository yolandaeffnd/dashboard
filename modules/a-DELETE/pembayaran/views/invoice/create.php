<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pembayaran\models\InvoiceBank */

$this->title = 'Create Invoice Bank';
$this->params['breadcrumbs'][] = ['label' => 'Invoice Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-bank-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
