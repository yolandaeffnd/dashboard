<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\modules\instrukturkelas\models\LatPesertaAbsen;
use app\modules\instrukturkelas\models\LatPeriode;
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
            'header' => 'Hadirkah Hari Ini?',
            'hAlign' => 'right',
            'template' => '{hadir} {tidak} {reset}',
            'buttons' => [
                'hadir' => function ($url, $model) {
                    $absen = LatPesertaAbsen::find()->where('absenPesertaId=:id AND absenTgl=:tgl AND absenJdwlId=:jdwl', [
                                ':id' => $model->pesertaId,
                                ':tgl' => Yii::$app->request->get('tgl'),
                                ':jdwl' => $model->absenJdwlId
                            ])->count();
                    if ($absen == 0) {
                        return Html::a('<span class="glyphicon glyphicon-check"></span>', '#', ['class' => 'btn-sm btn-primary', 'onclick' => 'setKehadiran("' . Url::to(['simpanpeserta', 'act' => 'set-hadir', 'params' => urlencode(serialize(['id' => $model->pesertaId]))]) . '")', 'title' => Yii::t('yii', 'Ya'), 'data-pjax' => 'false', 'style' => 'margin-right:3px;']);
                    } else {
                        $absenTidak = LatPesertaAbsen::find()->where('absenPesertaId=:id AND absenTgl=:tgl AND absenJdwlId=:jdwl AND absenIsHadir="1"', [
                                    ':id' => $model->pesertaId,
                                    ':tgl' => Yii::$app->request->get('tgl'),
                                    ':jdwl' => $model->absenJdwlId
                                ])->count();
                        if ($absenTidak != 0) {
                            return Html::beginTag('span', ['class' => 'label label-info']) . 'Hadir' . Html::endTag('span');
                        }
                    }
                },
                'tidak' => function ($url, $model) {
                    $absen = LatPesertaAbsen::find()->where('absenPesertaId=:id AND absenTgl=:tgl AND absenJdwlId=:jdwl', [
                                ':id' => $model->pesertaId,
                                ':tgl' => Yii::$app->request->get('tgl'),
                                ':jdwl' => $model->absenJdwlId
                            ])->count();
                    if ($absen == 0) {
                        return Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', '#', ['class' => 'btn-sm btn-warning', 'onclick' => 'setKehadiran("' . Url::to(['simpanpeserta', 'act' => 'set-tidak-hadir', 'params' => urlencode(serialize(['id' => $model->pesertaId]))]) . '")', 'title' => Yii::t('yii', 'Tidak'), 'data-pjax' => 'false']);
                    } else {
                        $absenTidak = LatPesertaAbsen::find()->where('absenPesertaId=:id AND absenTgl=:tgl AND absenJdwlId=:jdwl AND absenIsHadir="0"', [
                                    ':id' => $model->pesertaId,
                                    ':tgl' => Yii::$app->request->get('tgl'),
                                    ':jdwl' => $model->absenJdwlId
                                ])->count();
                        if ($absenTidak != 0) {
                            return Html::beginTag('span', ['class' => 'label label-warning']) . 'Tidak Hadir' . Html::endTag('span');
                        }
                    }
                },
                'reset' => function ($url, $model) {
                    $absen = LatPesertaAbsen::find()->where('absenPesertaId=:id AND absenTgl=:tgl AND absenJdwlId=:jdwl', [
                                ':id' => $model->pesertaId,
                                ':tgl' => Yii::$app->request->get('tgl'),
                                ':jdwl' => $model->absenJdwlId
                            ])->count();
                    if ($absen != 0) {
                        return Html::a('<span class="glyphicon glyphicon-refresh"></span>', '#', ['class' => 'btn-sm btn-success', 'onclick' => 'setKehadiran("' . Url::to(['simpanpeserta', 'act' => 'reset-absen', 'params' => urlencode(serialize(['id' => $model->pesertaId]))]) . '")', 'title' => Yii::t('yii', 'Reset'), 'data-pjax' => 'false', 'style' => 'margin-left:3px;']);
                    }
                }
            ]
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Daftar Peserta Kelas',
        'after' => false,
//        'before' => false
    ],
]);
Pjax::end();
?>
<script>
    function setKehadiran(url) {
        $.ajax({
            type: 'GET',
            url: url,
            data: {tgl: '<?php echo $absenTgl; ?>', jdwl: '<?php echo $absenJdwl; ?>'},
            success: function (data) {
                if (data == 410) {
                    document.location = '';
                }
            }
        });
    }
</script>