<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\informasi\models\Member;
use app\modules\informasi\models\LatKelas;

/* @var $this yii\web\View */
/* @var $model app\modules\informasi\models\Broadcast */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="broadcast-form">
    <?php
    //Data Kelas
    $kelas = LatKelas::find()
            ->select(['*', 'CONCAT(jnslatNama," - ",periodeNama) AS periodeNama'])
            ->join('JOIN', 'lat_periode', 'lat_periode.periodeId=lat_kelas.klsPeriodeId')
            ->join('JOIN', 'ref_jenis_pelatihan', 'ref_jenis_pelatihan.jnslatId=lat_periode.periodeJnslatId')
            ->where('lat_periode.periodeIsAktif="1"')
            ->orderBy('klsNama ASC');
    //Data Member
    $member = Member::find()
            ->select(['*', 'CONCAT(memberEmail," : ",memberNama)AS memberNama']);
    if($model->bcKategori!=''){
        $member->join('JOIN', 'lat_peserta', 'lat_peserta.pesertaMemberId=member.memberId')
                ->where('lat_peserta.pesertaKlsId=:kls', [':kls'=>$model->bcKategori]);
    }
    $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_VERTICAL
    ]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'bcKategori' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($kelas->all(), 'klsId', 'klsNama', 'periodeNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => 'Kirim Broadcast per Member',
                        'onchange' => '$(this).submit();'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ],
            ],
            
            'bcJudul' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Judul']],
        ]
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'bcIsi' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => CKEditor::className(), 'options' => ['preset' => 'basic']],
        ]
    ]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'bcTo' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($member->all(), 'memberEmail', 'memberNama'),
                    'size' => Select2:: MEDIUM,
                    'options' => [
                        'placeholder' => 'Tujuan',
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['index']) . '";']) . ' ' .
                Html::submitButton(' Kirim', ['id' => 'btn-kirim','name' => 'btn-kirim','onclick'=>'$("#btn-kirim").val(1);', 'type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
