<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    
    <?php
    //label menu dropdown
    $chartMenu = [
        'label' => 'Chart',
        'linkOptions' => ['class' => 'fa fa-key'],
    ];
    
    // $subitems = Yii::$app->db->createCommand('SELECT label, idChart FROM app_chart WHERE posisiChart = $ps_chart  AND WHERE posisiChart = :parent_id')
    // ->bindValue(':parent_id', 1) // Assuming the parent_id for Login is 1
    // ->queryAll();
    
    $subitems = Yii::$app->db->createCommand('SELECT nama_chart, idChart FROM app_chart WHERE posisiChart = :posisiChart')
    ->bindValue(':posisiChart', 1)
    ->queryAll();

    $chartMenu['items'] = array_map(function($item) {
        return [
            'label' => $item['nama_chart'],
            // 'url' => [$item['idChart']],
            'url' => ['/site/view', 'id' => $item['idChart']],
            'linkOptions' => ['class' => 'fa fa-bar-chart']
        ];
    }, $subitems);

    NavBar::begin([
        'brandLabel' => '<span class="art-object567354297-fo" data-left="0%"></span><h1 class="art-headline-fo" data-left="10%">'.Yii::$app->versionApp->companyName().'</h1><h2 class="art-slogan-fo" data-left="10%">data.unand.ac.id</h2>',//.Html::img(AppAsset::register($this)->baseUrl . '/images/logo-unand.png', ['style' => 'width:34px;margin-top:-10px;float:left;margin-right:5px;border:2px solid;border-radius:3px;background-color:white;']) . 'e-Office Universitas Andalas',
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => [
            'style' => 'font-size:20px;'
        ],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => [
            'class' => 'navbar-nav navbar-right',
            'style' => 'margin:0px;margin-right:15px;'
            ],
        'items' => [
            // ['label' => ' Home', 'url' => ['/site/index'],'linkOptions' =>['class'=>'fa fa-home']],
            // [
            //     'label' => 'Chart',
            //     'items' => [
            //         ['label' => 'Subitem 1', 'url' => ['/site/subitem1']],
            //         ['label' => 'Subitem 2', 'url' => ['/site/subitem2']],
            //     ],
            //     'linkOptions' => ['class' => 'fa fa-bar-chart']
            // ],
            $chartMenu,
            ['label' => ' Login', 'url' => ['/site/login'],'linkOptions' =>['class'=>'fa fa-key']],
           
        ],
    ]);
    NavBar::end();
    ?>
    <!--</nav>-->
</header>
