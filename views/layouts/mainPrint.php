<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?php echo Yii::$app->name.' - '; ?><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body style="margin: 5px;">
        <script>
        window.print();
        </script>
        <?php //$this->beginBody() ?>
        <!--<div class="wrapper">-->
            <?= $content ?>
        <!--</div>-->
        <?php //$this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
