<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
    app\assets\AppAsset::register($this);
} else {
    app\assets\AppAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <!-- <title><?php echo Yii::$app->name . ' - '; ?><?= Html::encode($this->title) ?></title> -->
        <title>Data Unand</title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-green-light layout-top-nav">
        <?php $this->beginBody() ?>
        <div class="wrapper">

            <?php
          echo $this->render(
                    'header-fo.php', ['directoryAsset' => $directoryAsset]
            )
            ?>
            <?php
            echo $this->render(
                    'content-fo.php', ['content' => $content, 'directoryAsset' => $directoryAsset]
            )
            ?>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
