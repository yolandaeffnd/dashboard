<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\aplikasi\models\AppGroup;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'System Info';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                phpinfo();
                ?>
            </div>
        </div>
    </div>
</div>
