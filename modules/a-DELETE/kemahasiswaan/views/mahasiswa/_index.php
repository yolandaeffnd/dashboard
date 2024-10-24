<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefFakultasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Pjax::begin([
//    'id' => 'pjax-periode',
//    'enablePushState' => false,
//    'enableReplaceState' => false
//]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'hover' => true,
    'pjax' => true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            [
            'class' => 'kartik\grid\SerialColumn',
            'width' => '30',
        ],
            [
            'label' => 'NIM',
            'width' => '80',
            'value' => function ($data) {
                return $data['niu'];
            }
        ],
            [
            'label' => 'No.Test',
            'width' => '80',
            'value' => function ($data) {
                return $data['notest'];
            }
        ],
            [
            'label' => 'Nama',
            'width' => '150',
            'value' => function ($data) {
                return $data['nama'];
            }
        ],
            [
            'label' => 'Akt',
            'width' => '50',
            'value' => function ($data) {
                return $data['angkatan'];
            }
        ],
            [
            'label' => 'Jalur',
            'width' => '80',
            'value' => function ($data) {
                return $data['namaJalur'];
            }
        ],
            [
            'label' => 'Program Studi',
            'width' => '200',
            'value' => function ($data) {
                return $data['namaProgramStudi'];
            }
        ],
            [
            'label' => 'Jenjang',
            'width' => '100',
            'value' => function ($data) {
                return $data['namaJenjang'];
            }
        ],
            [
            'label' => 'Fakultas',
            'width' => '150',
            'value' => function ($data) {
                return $data['namaFakultas'];
            }
        ],
            [
            'label' => 'L/P',
            'width' => '30',
            'value' => function ($data) {
                return $data['idSeks'];
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tempat Lahir',
            'width' => '100',
            'value' => function ($data) {
                return $data['tempatLahir'];
            }
        ],
                [
            'label' => 'Alamat',
            'width' => '100',
            'value' => function ($data) {
                return $data['alamat'];
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],[
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                [
            'label' => 'Tgl Lahir',
            'width' => '100',
            'value' => function ($data) {
                $inDate = new IndonesiaDate();
                return $inDate->setDate($data['tglLahir']);
            }
        ],
                
//            [
//            'class' => 'kartik\grid\ActionColumn',
//            'width' => '100px;',
//            'hAlign' => 'center',
//            'template' => '{update} {delete}',
//            'buttons' => [
//                'update' => function($url, $model) {
//                    return Html::a('', Url::to(['update', 'id' => $model->periodeId]), ['class' => 'btn-sm btn-primary btn-flat fa fa-pencil', 'title' => Yii::t('yii', 'Detail'), 'style' => 'float:left;margin-right:5px;']);
//                },
//                'delete' => function ($url, $model) {
//                    return Html::a('', '#', ['class' => 'btn-sm btn-danger btn-flat fa fa-trash', 'onclick' => 'deleteItem("' . Url::to(['delete', 'id' => $model->periodeId]) . '")', 'title' => Yii::t('yii', 'Hapus'), 'data-pjax' => 'false', 'style' => 'float:left;']);
//                },
//            ]
//        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => $this->title,
        'after' => false,
        'before' => false
    ],
]);
//Pjax::end();
?>
<script>
    function deleteItem(url) {
        krajeeDialog.confirm("Are you sure to delete this item?", function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: url,
                    success: function (data) {
                        document.location = '<?php echo Yii::$app->request->absoluteUrl; ?>';
                    }
                });
                this.remove();
            } else {
                this.remove();
            }
        });
    }
</script>