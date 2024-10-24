<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatJadwal */

$this->title = $model->jdwlId;
$this->params['breadcrumbs'][] = ['label' => 'Lat Jadwals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-jadwal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->jdwlId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->jdwlId], [
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
            'jdwlId',
            'jdwlKlsId',
            'jdwlRuangId',
            'jdwlHariKode',
            'jdwlJamMulai',
            'jdwlJamSelesai',
            'jdwlCreate',
            'jdwlUpdate',
        ],
    ]) ?>

</div>
