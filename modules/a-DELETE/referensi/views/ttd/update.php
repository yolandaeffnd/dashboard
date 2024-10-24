<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatJadwal */

$this->title = 'Ubah Penandatangan';
$this->params['breadcrumbs'][] = ['label' => 'Set Tandatangan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-jadwal-update">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><b><?= Html::encode($this->title) ?></b></h3>
            <div class="box-tools pull-right">
                <!--<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>-->
                <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
            </div>
        </div>
        <div class="box-body">
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
