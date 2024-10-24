<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use app\modules\member\models\MemberKategori;
use app\modules\member\models\RefPendidikan;

/* @var $this yii\web\View */
/* @var $model app\modules\referensi\models\RefProdi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-form">
    <?php
    //Member Kategori
    $dataMemberKat = MemberKategori::find()
            ->where('memberKatResource LIKE "%BACKOFFICE%"')
            ->all();
    //Pendidikan Terakhir
    $dataPendidikan = RefPendidikan::find()
            ->where('pddId<>"0"')
            ->orderBy('pddId DESC')
            ->all();

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    if ($model->memberIsAkunPortal == 1) {
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 2,
            'attributes' => [
                'memberNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Lengkap']],
                'memberJenkel' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                        'data' => ['L' => 'L', 'P' => 'P'],
                        'size' => Select2:: MEDIUM,
                        'options' => [
                            'placeholder' => '- Pilih Jenis Kelamin -',
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
            'columns' => 3,
            'attributes' => [
                'memberTmpLahir' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Tempat Lahir']],
                'memberTglLahir' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DatePicker::className(), 'options' => [
                        'convertFormat' => true,
                        'options' => ['placeholder' => 'Tanggal Lahir'],
                        'pluginOptions' => [
                            'format' => 'yyyy-MM-dd',
                            'todayHighlight' => true,
                            'autoclose' => true
                        ]
                    ],
                ],
                'memberPddId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                        'data' => ArrayHelper::map($dataPendidikan, 'pddId', 'pddNama'),
                        'size' => Select2:: MEDIUM,
                        'options' => [
                            'placeholder' => '- Pilih Pendidikan Terakhir -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                        ],
                    ],
                ],
                'memberEmail' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Email']],
                'memberTelp' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Telp']],
                'memberIsAktif' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                        'data' => ['1' => 'Aktif', '0' => 'Tidak Aktif'],
                        'size' => Select2:: MEDIUM,
                        'options' => [
                            'placeholder' => '- Pilih Status -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                        ],
                    ],
                ],
            ]
        ]);
    } else {
        //Akun SIMPB
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 2,
            'attributes' => [
                'memberNama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Lengkap']],
                'memberJenkel' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                        'data' => ['L' => 'L', 'P' => 'P'],
                        'size' => Select2:: MEDIUM,
                        'options' => [
                            'placeholder' => '- Pilih Jenis Kelamin -',
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
            'columns' => 3,
            'attributes' => [
                'memberTmpLahir' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Tempat Lahir']],
                'memberTglLahir' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DatePicker::className(), 'options' => [
                        'convertFormat' => true,
                        'options' => ['placeholder' => 'Tanggal Lahir'],
                        'pluginOptions' => [
                            'format' => 'yyyy-MM-dd',
                            'todayHighlight' => true,
                            'autoclose' => true
                        ]
                    ],
                ],
                'memberPddId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                        'data' => ArrayHelper::map($dataPendidikan, 'pddId', 'pddNama'),
                        'size' => Select2:: MEDIUM,
                        'options' => [
                            'placeholder' => '- Pilih Pendidikan Terakhir -',
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
            'columns' => 2,
            'attributes' => [
                'memberEmail' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Email']],
                'memberTelp' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Telp']],
                'memberMemberKatId' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                        'data' => ArrayHelper::map($dataMemberKat, 'memberKatId', 'memberKatNama'),
                        'size' => Select2:: MEDIUM,
                        'options' => [
                            'placeholder' => '- Pilih Kategori Member -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                        ],
                    ],
                ],
                'memberIsAktif' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                        'data' => ['1' => 'Aktif', '0' => 'Tidak Aktif'],
                        'size' => Select2:: MEDIUM,
                        'options' => [
                            'placeholder' => '- Pilih Status -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                        ],
                    ],
                ],
            ]
        ]);
    }

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
