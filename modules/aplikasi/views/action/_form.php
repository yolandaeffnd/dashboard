<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppAction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-action-form">
    
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'labelMenu' => ['type' => Form::INPUT_TEXT, 'options' => ['readonly' => true,'style'=>'background-color:white']],
        ]
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'actionFn' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Action Fn (function)']],
            'actionDesk' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Action Deskripsi']],
        ]
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' => '<div style="text-align: left; margin-top: 0px">' .
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/menu/view','id'=>$model->idMenu]) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);
    
    ActiveForm::end();
    ?>

</div>
