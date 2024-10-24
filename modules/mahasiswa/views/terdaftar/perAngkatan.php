<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use app\models\AppUserData;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use app\modules\mahasiswa\models\ProdiNasional;
use app\modules\mahasiswa\models\Fakultas;
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\report\models\Stock */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Mahasiswa Per Angkatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-create">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <div class="audit-form">
                <?php
                $userData = AppUserData::findOne(['idUser' => \Yii::$app->user->identity->userId]);
                $dataFakultas = Fakultas::find()
                        ->all();
                $dataProdi= ProdiNasional::find()
                        ->select(['prodiKode','CONCAT(prodiJenjang," - ",prodiNama)AS prodiNama'])
                        ->where('prodiFakId=:id', [
                            ':id'=>$model->fakId
                        ])->orderBy('prodiJenjang ASC')
                        ->all();

                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
                echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [
                        'fakId' => ['label' => 'Fakultas', 'type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
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
                        'prodiId' => ['label' => 'Program Studi', 'type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                                'data' => ArrayHelper::map($dataProdi, 'prodiKode', 'prodiNama'),
                                'size' => Select2:: MEDIUM,
                                'options' => [
                                    'placeholder' => '- Pilih Program Studi -',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => false,
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
                            Html::submitButton(' Tampilkan', ['id' => 'btn-search', 'name' => 'btn-search', 'onclick' => '$("#btn-search").val(1);', 'type' => 'button', 'class' => 'fa fa-search btn btn-primary btn-flat']) .
                            '</div>'
                        ],
                    ]
                ]);
                ActiveForm::end();
                ?>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    if (!empty($dataKategori) && !empty($dataColumn)) {
        echo $this->render('_grafikColPerAngkatan', [
            'dataKategori' => $dataKategori,
            'dataColumn' => $dataColumn
        ]);

        echo $this->render('_detailPerAngkatan', [
        ]);
    }
    ?>
</div>