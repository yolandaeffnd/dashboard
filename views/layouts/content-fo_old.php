<?php

use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
?>

<div class="content-wrapper bg-body">
    <div class="container">
        <section class="content">
            <?= Alert::widget() ?>
            <?= $content ?>
        </section>
    </div>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> <?php echo Yii::$app->versionApp->version();?>
    </div>
    <strong>&copy; <a href="<?php echo Yii::$app->versionApp->developBy(); ?>" target="_blank"><?php echo Yii::$app->versionApp->year().'-'.date('Y'); ?></a> <a href="<?php echo Yii::$app->versionApp->companyLink();?>" target="_blank"><?php echo Yii::$app->versionApp->companyName();?></a>.</strong> All rights reserved.
</footer>
