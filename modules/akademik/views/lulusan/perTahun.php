<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use app\models\AppUserData;
use app\modules\bhp\models\BhpPeriode;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use app\modules\pengolahan\models\Fakultas;
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\report\models\Stock */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Lulusan Per Tahun';
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
            <div class="callout callout-info">
                <p>
                    Halaman ini akan menampilkan data peminat Penerimaan Mahasiswa Baru Diploma III (PMB-D3) tiap program studi. 
                </p>
            </div>
            <div class="audit-form">
                <?php
                if (empty($dataCategories) && empty($dataSeries)) {
                    $userData = AppUserData::findOne(['idUser' => \Yii::$app->user->identity->userId]);

                    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
                    echo Form::widget([
                        'model' => $model,
                        'form' => $form,
                        'columns' => 2,
                        'attributes' => [
                            'thnAkt' => ['label' => 'Tahun Lulus', 'type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                                    'data' => ArrayHelper::map($refThnLulus, 'thnLulus', 'thnLulus'),
                                    'size' => Select2:: MEDIUM,
                                    'options' => [
                                        'placeholder' => '- Pilih Tahun Lulus -',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false,
                                        'multiple' => false,
                                    ],
                                ],
                            ],
//                            'prodiId' => ['label' => 'Program Studi', 'type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
//                                    'data' => ArrayHelper::map($refProdi, 'prodiKode', 'prodiNama'),
//                                    'size' => Select2:: MEDIUM,
//                                    'options' => [
//                                        'placeholder' => '- Semua Program Studi  -',
//                                    ],
//                                    'pluginOptions' => [
//                                        'allowClear' => true,
//                                        'multiple' => false,
//                                    ],
//                                ],
//                            ],
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
                                Html::submitButton(' Tampilkan', ['id' => 'btn-cek', 'name' => 'btn-cek', 'onclick' => '$("#btn-cek").val(1);', 'type' => 'button', 'class' => 'fa fa-search btn btn-primary btn-flat']) .
                                '</div>'
                            ],
                        ]
                    ]);
                    ActiveForm::end();
                } else {
                    echo $this->render('_pmbDiploma', [
                        'model' => $model,
                        'dataCategories' => $dataCategories,
                        'dataSeries' => $dataSeries,
                        'dataTabel'=>$dataTabel
                    ]);
                }
                ?>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>