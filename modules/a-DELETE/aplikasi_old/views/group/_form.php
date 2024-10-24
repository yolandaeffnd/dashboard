<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-group-form">

    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'namaGroup' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Group']],
            'isMemberGroup' => ['type' => Form::INPUT_DROPDOWN_LIST,'items'=>['0'=>'Tidak','1'=>'Ya, Front End','2'=>'Ya, Back End']],
        ]
    ]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'ketGroup' => ['type' => Form::INPUT_TEXTAREA, 'options' => ['placeholder' => 'Keterangan']],
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/group']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
