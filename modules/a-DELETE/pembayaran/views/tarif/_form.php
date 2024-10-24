<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use yii\widgets\MaskedInput;
use app\modules\pembayaran\models\RefJenisPelatihan;
use app\modules\pembayaran\models\RefJenisBiaya;
use app\modules\pembayaran\models\RefBank;

/* @var $this yii\web\View */
/* @var $model app\modules\pembayaran\models\RefTarif */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-tarif-form">
    <?php
    //Bank
    $dataBank = RefBank::find()->all();
    //Jenis Pelatihan
    $dataJenisPelatihan = RefJenisPelatihan::find();
    //Jenis Biaya
    $dataJenisBiaya = RefJenisBiaya::find();
    if ($model->isNewRecord) {
        
        $dataJenisBiaya->where('jnsBiayaId NOT IN(SELECT tarifJnsBiayaId FROM ref_tarif WHERE tarifJnslatId=:jnslat)', [
            ':jnslat' => $model->tarifJnslatId
        ]);
    } else {
        $dataJenisPelatihan->where('jnslatId=:id',[':id'=>$model->tarifJnslatId]);
        $dataJenisBiaya->where('jnsBiayaId NOT IN(SELECT tarifJnsBiayaId FROM ref_tarif WHERE tarifJnslatId=:jnslat) OR jnsBiayaId=:jnsbiaya', [
            ':jnslat' => $model->tarifJnslatId,
            ':jnsbiaya' => $model->tarifJnsBiayaId
        ]);
    }

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'tarifJnslatId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataJenisPelatihan->all(), 'jnslatId', 'jnslatNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Jenis Pelatihan -',
                        'onchange' => '$(this).submit();'
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => false,
                    ],
                ],
            ],
            'tarifJnsBiayaId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataJenisBiaya->all(), 'jnsBiayaId', 'jnsBiayaNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Jenis Biaya -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => false,
                    ],
                ],
            ],
            'tarifBankId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataBank, 'bankId', 'bankNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Bank Pembayaran -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => false,
                    ],
                ],
            ],
            'tarifJumlah' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => MaskedInput::className(), 'options' => [
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true,
                    ],
                ]],
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
                Html::submitButton(' Simpan', ['id' => 'btn-simpan', 'name' => 'btn-simpan', 'onclick' => '$("#btn-simpan").val(1);', 'type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);
    ActiveForm::end();
    ?>
</div>
