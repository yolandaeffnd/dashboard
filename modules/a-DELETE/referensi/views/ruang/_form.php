<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefRuang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-ruang-form">
    <?php

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'attributes' => [
            'ruangKode' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Kode Ruang']],
            'ruangNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Ruang']],
            'runagKapasitas' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Kapasitas']],
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
