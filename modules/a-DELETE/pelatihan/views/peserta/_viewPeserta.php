<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\modules\pelatihan\models\LatPesertaAbsen;
use app\modules\pelatihan\models\LatPeriode;
use app\models\IndonesiaDate;

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */

Pjax::begin([
    'id' => 'pjax-peserta-kelas-pelatihan',
    'enablePushState' => false,
    'enableReplaceState' => false
]);

echo GridView::widget([
    'dataProvider' => $dataProviderPeserta,
    'responsive' => true,
    'hover' => true,
    'pjax' => true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
            'attribute' => 'pesertaId',
            'width' => '100px',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data->pesertaIsFree == '0' && $data->pesertaIsPaid=='0') {
                    $result = $data->pesertaId . ' | ' . Html::a('', '#', ['class' => 'fa fa-money']);
                } else {
                    $result = $data->pesertaId;
                }
                return $result;
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
            'label' => 'Hadir',
            'width' => '10px',
            'hAlign' => 'center',
            'value' => function ($data) {
                return LatPesertaAbsen::find()->where('absenPesertaId=:id AND absenIsHadir="1"', [':id' => $data->pesertaId])->count();
            }
        ],
            [
            'label' => 'Tidak Hadir',
            'width' => '10px',
            'hAlign' => 'center',
            'value' => function ($data) {
                return LatPesertaAbsen::find()->where('absenPesertaId=:id AND absenIsHadir="0"', [':id' => $data->pesertaId])->count();
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{pindah} {delete}',
            'buttons' => [
                'pindah' => function ($url, $model) {
                    $inDate = new IndonesiaDate();
                    $periode = LatPeriode::find()
                                    ->where('periodeId=:periode AND periodeLakMulai>:sekarang', [
                                        ':periode' => $model->pesertaKls->klsPeriodeId,
                                        ':sekarang' => $inDate->getDate()
                                    ])->one();
                    if (!empty($periode)) {
                        return Html::a('<span class="glyphicon glyphicon-retweet"></span>', '#', ['onclick' => 'dialogPindah("' . Url::to(['pindahkelas', 'id' => $model->pesertaId, 'klsasal' => $model->pesertaKlsId]) . '")', 'title' => Yii::t('yii', 'Pindah Kelas'), 'data-pjax' => 'false', 'style' => 'margin-right:5px;']);
                    }
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['onclick' => 'deleteItem("' . Url::to(['delete', 'id' => $model->pesertaId]) . '")', 'title' => Yii::t('yii', 'Hapus Peserta'), 'data-pjax' => 'false']);
                }
            ]
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Daftar Peserta Kelas',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();

//Popup
Modal::begin([
    'header' => 'Pop Up',
    'id' => 'modal',
    'size' => 'modal-lg',
]);
echo '<div id="modalContent"></div>';
Modal::end();

$urlView = Yii::$app->request->absoluteUrl;
?>
<script>
    function dialogPindah(url) {
        $('#modal').modal('show')
                .find('#modalContent')
                .load(url);
    }
    function deleteItem(url) {
        krajeeDialog.confirm("Are you sure to delete this item?", function (result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        if (data == 410) {
                            document.location = '<?php echo $urlView; ?>';
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