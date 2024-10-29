<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\modules\aplikasi\models\AppKategori;

?>

<div class="app-kategori-form">

    <?php

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [

            'nama_kategori' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Kategori']],
            'jenis_kategori' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => [
                    'keuangan' => 'Keuangan',
                    'kepegawaian' => 'Kepegawaian',
                    'kemahasiswaan' => 'Kemahasiswaan'
                ],
                'options' => ['prompt' => 'Pilih Jenis Kategori']
            ],
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/kategori']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
