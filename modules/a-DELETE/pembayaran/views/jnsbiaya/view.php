<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefJenisBiaya */

$this->title = $model->jnsBiayaId;
$this->params['breadcrumbs'][] = ['label' => 'Ref Jenis Biayas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-jenis-biaya-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->jnsBiayaId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->jnsBiayaId], [
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
            'jnsBiayaId',
            'jnsBiayaNama',
            'jnsBiayaCreate',
            'jnsBiayaUpdate',
        ],
    ]) ?>

</div>
