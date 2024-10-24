<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\maping\models\JalurMap;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\maping\models\JalurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'hover' => true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
        'namaJalur',
            [
            'attribute' => 'namaJalur',
            'label' => 'Jalur Pada Sireg',
            'format' => 'raw',
            'value' => function($data) {
                $map = JalurMap::find()
                        ->select(['namaJalur AS jalurSireg'])
                        ->join('JOIN', 'sireg_jalur', 'sireg_jalur.idJalur=ref_jalur_map.mapIdJalur')
                        ->where('ref_jalur_map.idJalur=:id', [':id' => $data->idJalur])
                        ->each();
                $result = '';
                foreach ($map as $val) {
                    if ($result == '') {
                        $result = '# ' . $val['jalurSireg'];
                    } else {
                        $result = $result . '<br/># ' . $val['jalurSireg'];
                    }
                }
                return $result;
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['update', 'id' => $model->idJalur]), ['title' => Yii::t('yii', 'Ubah'), 'data-pjax' => 'false']);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['onclick' => 'deleteItem("' . Url::to(['delete', 'id' => $model->idJalur]) . '")', 'title' => Yii::t('yii', 'Hapus'), 'data-pjax' => 'false']);
                }
            ]
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => $this->title,
    ],
]);
?>
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