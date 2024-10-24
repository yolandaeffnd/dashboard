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
    'id' => 'pjax-kelas-pelatihan',
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
            'attribute' => 'klsNama',
            'value' => function ($data) {
                return $data->klsNama;
            }
        ],
            [
            'attribute' => 'klsPeriodeId',
            'group'=>true,
            'format' => 'raw',
            'value' => function ($data) {
                return '<b>' . $data->klsPeriode->periodeJnslat->jnslatNama . '</b><br/>'
                        . $data->klsPeriode->periodeNama;
            }
        ],
            [
            'label' => 'Jadwal',
            'format' => 'raw',
            'width' => '180px;',
            'value' => function ($data) {
                $jdwl = LatJadwal::find()
                                ->select(['*', 'hariInd AS jdwlHariKode'])
                                ->join('JOIN', 'ref_hari', 'ref_hari.hariKode=lat_jadwal.jdwlHariKode')
                                ->where('jdwlKlsId=:kls', [':kls' => $data->klsId])->each();
                $jadwal = '';
                foreach ($jdwl as $val) {
                    if ($jadwal == '') {
                        $jadwal = $val['jdwlHariKode'] . ' # ' . substr($val['jdwlJamMulai'], 0, 5) . ' - ' . substr($val['jdwlJamSelesai'], 0, 5);
                    } else {
                        $jadwal = $jadwal . '<br/>' . $val['jdwlHariKode'] . ' # ' . substr($val['jdwlJamMulai'], 0, 5) . ' - ' . substr($val['jdwlJamSelesai'], 0, 5);
                    }
                }
                return $jadwal;
            }
        ],
            [
            'attribute' => 'klsKapasitas',
            'hAlign' => 'center',
            'width' => '50px;',
            'value' => function ($data) {
                return $data->klsKapasitas;
            }
        ],
            [
            'label' => 'Jml Pes.',
            'hAlign' => 'center',
            'width' => '50px;',
            'value' => function ($data) {
                return LatPeserta::find()->where('pesertaKlsId=:id', [':id'=>$data->klsId])->count();
            }
        ],
            [
            'attribute' => 'klsMeetingMin',
            'label' => 'Pert.Min',
            'hAlign' => 'center',
            'width' => '50px;',
            'value' => function ($data) {
                return $data->klsMeetingMin;
            }
        ],
            [
            'attribute' => 'klsMeetingMax',
            'label' => 'Pert.Max',
            'hAlign' => 'center',
            'width' => '50px;',
            'value' => function ($data) {
                return $data->klsMeetingMax;
            }
        ],
            [
            'attribute' => 'klsIsPublish',
            'hAlign' => 'center',
            'width' => '50px;',
            'value' => function ($data) {
                return ($data->klsIsPublish == 1) ? 'Ya' : 'Tidak';
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Kelas Pelatihan',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>