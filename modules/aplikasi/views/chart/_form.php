<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\modules\aplikasi\models\AppChart;
use app\modules\aplikasi\models\AppMenu;
use app\models\RefFakultas;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppMenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-chart-form">

    <?php
    // if ($model->isNewRecord) {
    //     $dataMenu = AppMenu::find()->select(['idMenu', 'CONCAT(if(parentId=0,idMenu,CONCAT(parentId,".",idMenu))," - ",labelMenu) AS labelMenu'])->orderBy('labelMenu')->all();
    // } else {
    //     $dataMenu = AppMenu::find()->select(['idMenu', 'CONCAT(if(parentId=0,idMenu,CONCAT(parentId,".",idMenu))," - ",labelMenu) AS labelMenu'])->where('idMenu<>:id', [':id' => $model->idMenu])->orderBy('labelMenu')->all();
    // }

    
    $dataMenu = AppMenu::find()->select(['idMenu', 'CONCAT(if(parentId=0,idMenu,CONCAT(parentId,".",idMenu))," - ",labelMenu) AS labelMenu'])->orderBy('labelMenu')->all();
    

    $dataUnit = RefFakultas::find()->select(['fakId', 'fakNama'])->orderBy('fakNama')->all();
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [

            'idMenu' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ArrayHelper::map($dataMenu, 'idMenu', 'labelMenu'), 'options' => ['prompt' => '- Pilih Menu -']],
            'nama_chart' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Chart']],
            'url_chart' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Url Chart']],
            'unitId' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ArrayHelper::map($dataUnit, 'fakId', 'fakNama'), 'options' => ['prompt' => '- Pilih Fakultas -']],
            'posisiChart' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ['1' => 'Di Dalam', '0' => 'Di Luar']],
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/chart']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
