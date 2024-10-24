<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\aplikasi\models\AppMenu;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppMenu */

$this->title = 'Detail Menu';
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-menu-view">


    <?php
    $a = AppMenu::find()->select('labelMenu')->where(['idMenu' => $model->parentId])->one();
    echo DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => 'Detail Menu',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'onclick' => 'document.location="' . Url::to(['/aplikasi/menu/update', 'id' => $model->idMenu]) . '";',
        ],
        'deleteOptions' => [
            'ajaxSettings' => [
                'url' => Url::to(['/aplikasi/menu/delete', 'id' => $model->idMenu]),
                'success' => 'function(){document.location="' . Url::to(['/aplikasi/menu/index']) . '";}'
            ]
        ],
        'attributes' => [
            'idMenu',
            [
                'attribute' => 'parentId',
                'value' => empty($a->labelMenu) ? '-' : $a->labelMenu
            ],
            'labelMenu',
            'urlModule:url',
            'controllerName',
            [
                'attribute' => 'isAktif',
                'value' => ($model->isAktif == 1) ? 'Ya' : 'Tidak Aktif'
            ],
            [
                'attribute' => 'isSubAction',
                'value' => ($model->isSubAction == 1) ? 'Ya' : 'Tidak'
            ],
            'noUrut',
        ]
    ]);
    ?>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'hover' => true,
        'toolbar' => [
            [
                'content' =>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'type' => 'button',
                    'title' => 'Tambah Aksi',
                    'class' => 'btn btn-success',
                    'onclick' => 'js:document.location="' . Url::to(['/aplikasi/action/create', 'menu' => $model->idMenu]) . '";'
                ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'actionFn',
            'actionDesk',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/aplikasi/action/update', 'id' => $model->idAction, 'menu' => $model->idMenu]));
                    },
                            'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['/aplikasi/action/delete', 'id' => $model->idAction, 'menu' => $model->idMenu]), ['title' => Yii::t('yii', 'Delete'), 'data-confirm' => 'Are you sure to delete this item?', 'data-method' => 'post', 'data-pjax' => '0']);
                    }
                        ]
                    ],
                ],
                'panel' => [
                    'type' => 'success',
                    'heading' => 'Action Menu ' . $model->labelMenu,
                ],
            ]);
            echo Html::resetButton(' Selesai', ['class' => 'fa fa-check-circle-o btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/menu']) . '";']);
            ?>

</div>
