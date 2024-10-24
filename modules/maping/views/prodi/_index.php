<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\maping\models\ProdiMap;

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
            [
            'attribute' => 'prodiKode',
            'width' => '50px;',
            'value' => function ($data) {
                return $data->prodiKode;
            }
        ],
            [
            'attribute' => 'prodiNama',
            'width' => '250px;',
            'value' => function ($data) {
                return $data->prodiNama;
            }
        ],
            [
            'attribute' => 'prodiNama',
            'label' => 'Prodi Sireg',
            'format' => 'raw',
            'width' => '250px;',
            'value' => function($data) {
                $map = ProdiMap::find()
                        ->select(['namaProgramStudi AS prodiNama'])
                        ->join('JOIN', 'sireg_prodi', 'sireg_prodi.idProgramStudi=ref_prodi_map.idProgramStudi')
                        ->where('ref_prodi_map.prodiKode=:id', [':id' => $data->prodiKode])
                        ->each();
                $result = '';
                foreach ($map as $val) {
                    if ($result == '') {
                        $result = '# ' . $val['prodiNama'];
                    } else {
                        $result = $result . '<br/># ' . $val['prodiNama'];
                    }
                }
                return $result;
            }
        ],
            [
            'attribute' => 'prodiJenjang',
            'width' => '50px;',
            'value' => function ($data) {
                return $data->prodiJenjang;
            }
        ],
            [
            'attribute' => 'prodiFakId',
            'width' => '200px;',
            'value' => function ($data) {
                return $data->prodiFak->fakNama;
            }
        ],
            [
            'attribute' => 'prodiStatus',
            'width' => '50px;',
            'value' => function ($data) {
                return $data->prodiStatus;
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['update', 'id' => $model->prodiKode]), ['title' => Yii::t('yii', 'Ubah'), 'data-pjax' => 'false']);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['onclick' => 'deleteItem("' . Url::to(['delete', 'id' => $model->prodiKode]) . '")', 'title' => Yii::t('yii', 'Hapus'), 'data-pjax' => 'false']);
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