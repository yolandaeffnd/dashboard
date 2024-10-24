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
use app\modules\pengolahan\models\Fakultas;
use app\modules\pengolahan\models\Angkatan;
use app\models\IndonesiaDate;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\report\models\Stock */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Dimensi Semester';
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
                $dataTahun = Angkatan::find()->all();

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
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false,
                                    'multiple' => false,
                                ],
                            ],
                        ],
                        'thnAkt' => ['label' => 'Tahun Angkatan', 'type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                                'data' => ArrayHelper::map($dataTahun, 'angkatan', 'angkatan'),
                                'size' => Select2:: MEDIUM,
                                'options' => [
                                    'placeholder' => '- Pilih Tahun Angkatan -',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false,
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
                            Html::submitButton(' Cek', ['id' => 'btn-cek', 'name' => 'btn-cek', 'onclick' => '$("#btn-cek").val(1);', 'type' => 'button', 'class' => 'fa fa-search btn btn-primary btn-flat']) .
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
    if (!empty($data)) {
        ?>
        <div class="box" style="margin-top: -15px;">
            <div class="box-header with-border">
                <h3 class="box-title">Result</h3>
                <div class="box-tools pull-right">
                </div>
            </div>
            <div class="box-body">
                <?php
                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
                ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Data Mahasiswa</span>
                                <span class="info-box-number"><?php echo $data['jmlMhs'] . ' Orang'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Mhs Cuti</span>
                                <span class="info-box-number"><?php echo $data['jmlMhsCuti'] . ' Orang'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Mhs Keluar</span>
                                <span class="info-box-number"><?php echo $data['jmlMhsKeluar'] . ' Orang'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Mhs Registrasi</span>
                                <span class="info-box-number"><?php echo $data['jmlMhsReg'] . ' Orang'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                
                $akt = '';
                for ($i = 0; $i < count($model->thnAkt); $i++) {
                    if ($akt == '') {
                        $akt = $model->thnAkt[$i];
                    } else {
                        $akt = $akt . ',' . $model->thnAkt[$i];
                    }
                }
                echo $form->field($model, 'isProses')->hiddenInput(['readonly' => true, 'value' => 'proses'])->label(false);
                echo $form->field($model, 'fakId')->hiddenInput(['readonly' => true])->label(false);
                echo $form->field($model, 'thnAkt')->hiddenInput(['readonly' => true, 'value' => $akt])->label(false);
                echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' => [
                        'actions' => [
                            'type' => Form::INPUT_RAW,
                            'value' => '<div style="text-align: center; margin-top: 0px">' .
                            Html::submitButton(' Proses', ['id' => 'btn-proses', 'name' => 'btn-proses', 'onclick' => '$("#btn-proses").val(1);', 'type' => 'button', 'class' => 'fa fa-save btn btn-primary btn-flat']) .
                            '</div>'
                        ],
                    ]
                ]);
                ActiveForm::end();
                ?>
            </div>
        </div>
        <?php
    }
    //ActiveForm::end();
    ?>
</div>