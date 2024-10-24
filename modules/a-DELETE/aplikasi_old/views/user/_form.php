<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use app\modules\aplikasi\models\AppGroup;
use app\modules\aplikasi\models\AppGroupView;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-user-form">
    <?php
    //Pengecualian Group
    $availableGroup = [];
    $GroupView = AppGroupView::find()
            ->join('JOIN', 'app_group', 'app_group.idGroup=app_group_view.idGroupView')
            ->where(['app_group_view.idGroup' => Yii::$app->user->identity->userGroupId])
            ->andWhere('isMemberGroup="0"')->each();
    foreach ($GroupView as $val) {
        $availableGroup[] = $val->idGroupView;
    }

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'nama' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Nama Pengguna']],
            'telp' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Telepon/HP']],
            'usernameApp' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Username']],
            'passwordApp' => ['type' => Form::INPUT_PASSWORD, 'options' => ['placeholder' => 'Password']],
            'idGroup' => ['type' => Form::INPUT_DROPDOWN_LIST, 
                'items' => ArrayHelper::map(AppGroup::find()->where(['IN', 'idGroup', $availableGroup])->all(), 'idGroup', 'namaGroup'), 
                'options' => ['id'=>'id-group','prompt' => '- Pilih Group Pengguna -'],
                'hint' => '<span id="ket-group"></span>',
                ],
            'isAktif' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ['1' => 'Ya', '0' => 'Tidak']],
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
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>

</div>
<?php
$urlGetKetGroup=  Url::to(['getdatagroup']);
$js = <<<JS
        $('#id-group').on('change',function(){
            $.ajax({
                url:"{$urlGetKetGroup}",
                type:"GET",
                data:{id:$(this).val()},
                success:function(data){
                    $("#ket-group").html(data);
                }
            });
        });
JS;
$this->registerJs($js);
?>