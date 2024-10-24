<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\modules\pelatihan\models\LatJadwal;
use app\modules\pelatihan\models\LatPeserta;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pelatihan\models\LatKelasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-absensi-pelatihan',
    'enablePushState' => false,
    'enableReplaceState' => false
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'hover' => true,
    'pjax'=>true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
            'attribute' => 'pesertaId',
            'width' => '100px',
            'value' => function ($data) {
                return $data->pesertaId;
            }
        ],
            [
            'label' => 'NIM',
            'width' => '80px',
            'value' => function ($data) {
                return $data->pesertaMember->memberMhsNim;
            }
        ],
            [
            'attribute' => 'pesertaMemberId',
            'format' => 'raw',
            'value' => function ($data) {
                return '<b>' . $data->pesertaMember->memberNama . '</b>';
            }
        ],
            [
            'label' => 'Kategori Peserta',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->pesertaMember->memberMemberKat->memberKatNama;
            }
        ],
            [
            'attribute' => 'pesertaSkorTerakhirTest',
            'width' => '100px',
            'hAlign' => 'center',
            'value' => function ($data) {
                return $data->pesertaSkorTerakhirTest;
            }
        ],
            [
            'label' => 'Kehadiran',
            'width' => '100px',
            'hAlign' => 'center',
            'format' => 'raw',
            'value' => function ($data) {
                return ($data->kehadiran == 1) ? '<i>Hadir</i>' : '<b>Tidak Hadir</b>';
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['onclick' => 'deleteItem("' . Url::to(['batalpeserta', 'pesertaid' => $model->pesertaId]) . '")', 'title' => Yii::t('yii', 'Batalkan Absensi'), 'data-pjax' => 'false']);
                }
            ]
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Kehadiran Peserta Pelatihan',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
//==================================
$param = [
    'kls' => $klsid,
    'jdwl' => $jdwlid,
    'tgl' => $tgl
];
$urlBelumAbsen = Url::to(['countabsensi', 'act' => 'belum-absen', 'params' => serialize($param)]);
$urlSudahAbsen = Url::to(['countabsensi', 'act' => 'sudah-absen', 'params' => serialize($param)]);
$urlJmlHadir = Url::to(['countabsensi', 'act' => 'jml-hadir', 'params' => serialize($param)]);
$urlJmlTidakHadir = Url::to(['countabsensi', 'act' => 'jml-tidak-hadir', 'params' => serialize($param)]);
$urlGetData = Url::to(['getkehadiran', 'klsid' => $klsid, 'jdwlid' => $jdwlid, 'tgl' => $tgl]);
?>
<script>
    function deleteItem(url) {
        krajeeDialog.confirm("Are you sure to delete this item?", function (result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {kls: '<?php echo $klsid; ?>', jdwl: '<?php echo $jdwlid; ?>', tgl: '<?php echo $tgl; ?>'},
                    success: function (data) {
                        if (data == 410) {
                            $('#data-absen-pelatihan').load('<?php echo $urlGetData; ?>');
                            $('#label-belum-absen').load('<?php echo $urlBelumAbsen; ?>');
                            $('#label-sudah-absen').load('<?php echo $urlSudahAbsen; ?>');
                            $('#label-jml-hadir').load('<?php echo $urlJmlHadir; ?>');
                            $('#label-jml-tidak-hadir').load('<?php echo $urlJmlTidakHadir; ?>');
                            return false;
                        }
                    }
                });
                this.remove();
            } else {
                this.remove();
            }
        });
    }
</script>