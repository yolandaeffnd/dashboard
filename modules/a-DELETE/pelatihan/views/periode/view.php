<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\components\Terbilang;
use app\modules\pelatihan\models\LatPeriodeRuleAngkatan;
use app\modules\pelatihan\models\LatPeriodeRuleMemberKategori;
use app\modules\pelatihan\models\MemberKategori;
use app\modules\pelatihan\models\LatPeriodeRulePeriode;
use app\modules\pelatihan\models\LatPeriode;
use app\modules\pelatihan\models\LatPeriodeRuleTarif;
use app\modules\pelatihan\models\RefTarif;

$inDate = new IndonesiaDate();
$terbilang = new Terbilang();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeriode */

$this->title = 'Detail Periode Pelatihan';
$this->params['breadcrumbs'][] = ['label' => 'Periode Pelatihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-periode-view">
    <?php
    //Rule Angkatan
    $allowAngkatan = '';
    $ruleAngkatan = LatPeriodeRuleAngkatan::find()
                    ->where('rulePeriodeId=:periode', [
                        ':periode' => $model->periodeId
                    ])->each();
    foreach ($ruleAngkatan as $val) {
        if ($allowAngkatan == '') {
            $allowAngkatan = $val['ruleAllowAngkatan'];
        } else {
            $allowAngkatan = $allowAngkatan . '; ' . $val['ruleAllowAngkatan'];
        }
    }
    //Rule Member Kategori
    $allowMemberKat = '';
    $ruleMemberKat = LatPeriodeRuleMemberKategori::find()
                    ->where('rulePeriodeId=:periode', [
                        ':periode' => $model->periodeId
                    ])->each();
    foreach ($ruleMemberKat as $val) {
        if ($allowMemberKat == '') {
            $allowMemberKat = MemberKategori::findOne($val['ruleAllowMemberKatId'])->memberKatNama . ';';
        } else {
            $allowMemberKat = $allowMemberKat . '<br/> ' . MemberKategori::findOne($val['ruleAllowMemberKatId'])->memberKatNama . ';';
        }
    }
    //Rule Periode Not Allow
    $notAllowPeriode = '';
    $rulePeriode = LatPeriodeRulePeriode::find()
                    ->where('rulePeriodeId=:periode', [
                        ':periode' => $model->periodeId
                    ])->each();
    foreach ($rulePeriode as $val) {
        if ($notAllowPeriode == '') {
            $notAllowPeriode = LatPeriode::findOne($val['ruleNotAllowPeriode'])->periodeNama . ';';
        } else {
            $notAllowPeriode = $notAllowPeriode . '<br/> ' . LatPeriode::findOne($val['ruleNotAllowPeriode'])->periodeNama . ';';
        }
    }
    //Rule Tarif
    $biayaPelatihan = '';
    $ruleTarif = LatPeriodeRuleTarif::find()
                    ->where('rulePeriodeId=:periode', [
                        ':periode' => $model->periodeId
                    ])->each();
    foreach ($ruleTarif as $val) {
        $tar = RefTarif::findOne($val['ruleTarifId']);
        if ($biayaPelatihan == '') {
            $biayaPelatihan = $tar->tarifJnsBiaya->jnsBiayaNama . ' : Rp.' . $terbilang->setCurrency($tar->tarifJumlah) . ';';
        } else {
            $biayaPelatihan = $biayaPelatihan . '<br/> ' . $tar->tarifJnsBiaya->jnsBiayaNama . ' : Rp.' . $terbilang->setCurrency($tar->tarifJumlah) . ';';
        }
    }

    echo DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => 'Detail Periode Pelatihan',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'updateOptions' => [
            'hidden' => true,
            'onclick' => 'document.location="' . Url::to(['update', 'id' => $model->periodeId]) . '";',
        ],
        'deleteOptions' => [
            'hidden' => true,
            'ajaxSettings' => [
                'url' => Url::to(['delete', 'id' => $model->periodeId]),
                'success' => 'function(){document.location="' . Url::to(['index']) . '";}'
            ]
        ],
        'attributes' => [
                [
                'attribute' => 'periodeNama',
                'value' => $model->periodeNama
            ],
                [
                'attribute' => 'periodeJnslatId',
                'value' => $model->periodeJnslat->jnslatNama
            ],
                [
                'attribute' => 'periodeRegAwal',
                'label' => 'Pendaftaran Online',
                'value' => $inDate->setDateTime($model->periodeRegAwal) . ' s/d ' . $inDate->setDateTime($model->periodeRegAkhir)
            ],
                [
                'attribute' => 'periodeLakMulai',
                'label' => 'Pelaksanaan',
                'value' => $inDate->setDate($model->periodeLakMulai) . ' s/d ' . $inDate->setDate($model->periodeLakSelesai)
            ],
            'periodeMaxSkor',
                [
                'label' => 'Biaya Pelatihan',
                'format' => 'raw',
                'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;'],
                'value' => empty($biayaPelatihan) ? '-' : $biayaPelatihan
            ],
                [
                'label' => 'Untuk Kategori Member',
                'format' => 'raw',
                'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;'],
                'value' => empty($allowMemberKat) ? '-' : $allowMemberKat
            ],
                [
                'label' => 'Untuk Angkatan',
                'format' => 'raw',
                'value' => empty($allowAngkatan) ? 'Tidak Ada Batasan' : $allowAngkatan
            ],
                [
                'label' => 'Periode Tidak Diizinkan',
                'labelColOptions' => ['style' => 'vertical-align:top;text-align:right;'],
                'format' => 'raw',
                'value' => empty($notAllowPeriode) ? 'Tidak Ada Batasan' : $notAllowPeriode
            ],
        ]
    ]);
    echo Html::a(' Kembali', Url::to(['index']), ['class' => 'fa fa-reply btn btn-default', 'style' => 'margin-top:-20px;margin-right:5px;']);
    echo Html::a(' Ubah', Url::to(['update', 'id' => $model->periodeId]), ['class' => 'fa fa-edit btn btn-primary', 'style' => 'margin-top:-20px;']);
    ?>
</div>
