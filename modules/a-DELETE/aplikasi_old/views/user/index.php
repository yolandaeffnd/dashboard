<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\aplikasi\models\AppGroup;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengguna';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-user-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'hover' => true,
        'toolbar' => [
                [
                'content' =>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'type' => 'button',
                    'title' => 'Tambah Pengguna',
                    'class' => 'btn btn-success',
                    'onclick' => 'js:document.location="' . Url::to(['create']) . '";'
                ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
            'nama',
            'telp',
            'usernameApp',
            'namaGroup',
                [
                'attribute' => 'isAktif',
                'value' => function ($data) {
                    return ($data->isAktif == 1) ? "Aktif" : "Tidak Aktif";
                }
            ],
                [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function($url, $model) {
                        $group = AppGroup::findOne($model->idGroup);
                        if ($group->isMemberGroup == '2') {
                            return NULL;
                        } else {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['view', 'id' => $model->idUser]), ['title' => Yii::t('yii', 'View'), 'data-pjax' => 'false']);
                        }
                    },
                    'update' => function ($url, $model) {
                        $group = AppGroup::findOne($model->idGroup);
                        if ($group->isMemberGroup == '2') {
                            return NULL;
                        } else {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['update', 'id' => $model->idUser]), ['title' => Yii::t('yii', 'Update'), 'data-pjax' => 'false']);
                        }
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['onclick' => 'deleteItem("' . Url::to(['delete', 'id' => $model->idUser]) . '")', 'title' => Yii::t('yii', 'Delete'), 'data-pjax' => 'false']);
                    }
                ]
            ],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Pengguna',
        ],
    ]);
    ?>

</div>
<script>
    function deleteItem(url) {
        krajeeDialog.confirm("Are you sure to delete this item?", function (result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    success: function (data) {

                    }
                });
                this.remove();
            } else {
                this.remove();
            }
        });
    }
</script>