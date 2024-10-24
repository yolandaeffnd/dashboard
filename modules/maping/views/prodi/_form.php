<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\maping\models\SiregProdi;
use app\modules\maping\models\ProgramStudi;
use app\modules\maping\models\Fakultas;

/* @var $this yii\web\View */
/* @var $model app\modules\maping\models\ProdiNasional */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prodi-nasional-form">
    <?php
    $dataFakultas = Fakultas::find()->all();
    
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'prodiKode' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Kode DIKTI']],
            'prodiNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Prodi Dikti']],
        ]
    ]);
     
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'prodiJenjang' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Jalur']],
            'prodiStatus' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ['Aktif' => 'Aktif', 'Tidak Aktif' => 'Tidak Aktif'],
                    'size' => Select2:: MEDIUM,
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => false,
                    ],
                ],
            ]
        ]
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'prodiFakId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataFakultas, 'fakId', 'fakNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Fakultas -',
                        'onchange'=>'$(this).submit()'
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => false,
                    ],
                ],
            ],
            'prodiMap' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataSiregProdi, 'idProgramStudi', 'namaProgramStudi','namaJenjang'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => '- Pilih Program Studi -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ],
                ],
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default btn-flat', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['id'=>'btn-simpan','name'=>'btn-simpan','onclick'=>'$("#btn-simpan").val(1);','type' => 'button', 'class' => 'fa fa-save btn btn-primary btn-flat']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
