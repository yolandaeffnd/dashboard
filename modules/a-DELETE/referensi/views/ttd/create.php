<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\SetTtd */

$this->title = 'Create Set Ttd';
$this->params['breadcrumbs'][] = ['label' => 'Set Ttds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="set-ttd-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
