<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = 'Ubah Profil';
$this->params['breadcrumbs'][] = ['label' => 'Profil', 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-user-update">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <?php
            if ($ket == 'member') {
                echo $this->render('_formMember', [
                    'model' => $model,
                ]);
            } else {
                echo $this->render('_form', [
                    'model' => $model,
                ]);
            }
            ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
