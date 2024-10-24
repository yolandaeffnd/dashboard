<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\modules\aplikasi\models\AppMenu;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppMenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-menu-form">

    <?php
    if ($model->isNewRecord) {
        $dataMenu = AppMenu::find()->select(['idMenu', 'CONCAT(if(parentId=0,idMenu,CONCAT(parentId,".",idMenu))," - ",labelMenu) AS labelMenu'])->orderBy('labelMenu')->all();
    } else {
        $dataMenu = AppMenu::find()->select(['idMenu', 'CONCAT(if(parentId=0,idMenu,CONCAT(parentId,".",idMenu))," - ",labelMenu) AS labelMenu'])->where('idMenu<>:id', [':id' => $model->idMenu])->orderBy('labelMenu')->all();
    }
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'labelMenu' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Label Menu']],
            'parentId' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ArrayHelper::map($dataMenu, 'idMenu', 'labelMenu'), 'options' => ['prompt' => '- Menu Utama -']],
            'urlModule' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Url Module']],
            'controllerName' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Controller']],
        ]
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'attributes' => [
            'isAktif' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ['1' => 'Aktif', '0' => 'Tidak Aktif']],
            'isSubAction' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ['0' => 'Tidak', '1' => 'Ya']],
            'isHeader' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ['0' => 'Tidak', '1' => 'Ya']],
        ]
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'noUrut' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'No Urut']],
            'iconMenu' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Class Icon Admin LTE (Ex : fa fa-home)']],
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
                Html::resetButton(' Batal', ['class' => 'fa fa-ban btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/menu']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
</div>
