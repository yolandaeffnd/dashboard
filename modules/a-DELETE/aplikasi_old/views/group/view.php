<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $model app\modules\aplikasi\models\AppGroup */

$this->title = 'Detail Group';
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Group', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-group-view">

    <?php
    echo DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => 'Detail Group',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'onclick' => 'document.location="' . Url::to(['/aplikasi/group/update', 'id' => $model->idGroup]) . '";',
        ],
        'deleteOptions' => [
            'ajaxSettings' => [
                'url' => Url::to(['/aplikasi/group/delete', 'id' => $model->idGroup]),
                'success' => 'function(){document.location="' . Url::to(['/aplikasi/group/index']) . '";}'
            ]
        ],
        'attributes' => [
            'idGroup',
            'namaGroup',
            'ketGroup',
            [
                'attribute'=>'isMemberGroup',
                'value'=>(($model->isMemberGroup=='1')?'Ya, Front End':(($model->isMemberGroup=='2')?'Ya, Back End':'Tidak'))
            ]
        ]
    ]);
    ?>

    <?php
    $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_VERTICAL,
                'action' => Url::to(['akses', 'id' => $model->idGroup])
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' => '<div style="text-align: left; margin-top: 0px;margin-bottom:5px;">' .
                Html::resetButton(' Selesai', ['class' => 'fa fa-check-circle-o btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/group']) . '";']) . ' ' .
                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                '</div>'
            ],
        ]
    ]);

    //Akses Menu
    echo GridView::widget([
        'id' => 'gridViewMenu',
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'hover' => true,
        'toolbar' => ['{toggleData}'],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            ['attribute' => 'kode',
                'format' => 'raw',
                'group' => true,
                'value' => function($data) {
                    return $data->kode;
                }
            ],
            ['attribute' => 'labelMenu',
                'format' => 'raw',
                'group' => true,
                'value' => function($data) {
                    return $data->labelMenu;
                }
            ],
            'actionDesk',
            'actionFn',
//            'idGroup',
//            'idAction',
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'name' => 'pilihan',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                $modelMenu = new app\modules\aplikasi\models\AppMenu;
                    $arr = $modelMenu->getMenuAkses($model->idGroup);
                    if (in_array($model->idAction, $arr)) {
                        return ['checked' => true, 'value' => $model->idAction];
                    } else {
                        return ['checked' => false, 'value' => $model->idAction];
                    }
                }
                    ],
                ],
                'panel' => [
                    'type' => 'success',
                    'heading' => 'Akses Menu',
//                    'footer' => false
                ],
            ]);



            //Akses Group
            echo GridView::widget([
                'id' => 'gridViewGroup',
                'dataProvider' => $dataProviderGroup,
                'responsive' => true,
                'hover' => true,
                'toolbar' => [],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    'namaGroup',
                    [
                        'class' => 'kartik\grid\CheckboxColumn',
                        'name' => 'pilihanGroup',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            $arr = explode(',', $model->arrGroupView);
                            if (in_array($model->idGroup, $arr)) {
                                return ['checked' => true, 'value' => $model->idGroup];
                            } else {
                                return ['checked' => false, 'value' => $model->idGroup];
                            }
                        }
                            ],
                        ],
                        'panel' => [
                            'type' => 'success',
                            'heading' => 'Akses Group',
                            'footer' => false
                        ],
                    ]);

                    echo Form::widget([
                        'model' => $model,
                        'form' => $form,
                        'columns' => 1,
                        'attributes' => [
                            'actions' => [
                                'type' => Form::INPUT_RAW,
                                'value' => '<div style="text-align: left; margin-top: 0px">' .
                                Html::resetButton(' Selesai', ['class' => 'fa fa-check-circle-o btn btn-default', 'onclick' => 'js:document.location="' . Url::to(['/aplikasi/group']) . '";']) . ' ' .
                                Html::submitButton(' Simpan', ['type' => 'button', 'class' => 'fa fa-save btn btn-primary']) .
                                '</div>'
                            ],
                        ]
                    ]);

                    ActiveForm::end();
                    ?>
</div>
