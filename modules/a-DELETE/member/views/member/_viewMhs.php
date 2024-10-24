<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\IndonesiaDate;
use app\modules\member\models\RefFakultas;
use app\modules\member\models\RefProdi;
use app\modules\member\models\Member;
use app\modules\member\models\RefPendidikan;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeriode */

echo DetailView::widget([
    'model' => $model,
    'condensed' => true,
    'hover' => true,
    'mode' => DetailView::MODE_VIEW,
    'panel' => [
        'heading' => 'Detail Member',
        'type' => DetailView::TYPE_SUCCESS,
    ],
    'updateOptions' => [
        'hidden' => true,
    ],
    'deleteOptions' => [
        'hidden' => true,
    ],
    'attributes' => [
            [
            'attribute' => 'memberFoto',
            'label' => '',
            'format' => 'raw',
            'value' => empty($model->memberFoto) ?
                    Html::img(AppAsset::register($this)->baseUrl . '/images/nobody.png', ['style' => 'width:150px;height:190px;border-radius:5px;']) :
                    Html::img(Url::to(['/site/getfoto', 'filename' => $model->memberFoto]), ['style' => 'width:150px;height:190px;border-radius:5px;'])
        ],
        'memberNama',
            [
            'attribute' => 'memberJenkel',
            'label' => 'Jenis Kelamin',
            'value' => ($model->memberJenkel == 'L') ? 'Laki-Laki' : 'Perempuan'
        ],
            [
            'attribute' => 'memberTglLahir',
            'label' => 'Tempat/Tanggal Lahir',
            'value' => $model->memberTmpLahir . '/ ' . $inDate->setDate($model->memberTglLahir)
        ],
            [
            'attribute' => 'memberPddId',
            'label' => 'Pendidikan Terakhir',
            'value' => RefPendidikan::findOne($model->memberPddId)->pddNama
        ],
        'memberMhsAngkatan',
        'memberMhsNim',
            [
            'attribute' => 'memberMhsProdiId',
            'value' => empty($model->memberMhsProdiId) ? '-' : RefProdi::findOne($model->memberMhsProdiId)->prodiNama
        ],
            [
            'attribute' => 'memberMhsFakId',
            'value' => empty($model->memberMhsFakId) ? '-' : RefFakultas::findOne($model->memberMhsFakId)->fakNama
        ],
        'memberEmail',
        'memberTelp',
            [
            'attribute' => 'memberIsAktif',
            'value' => ($model->memberIsAktif == 1) ? 'Aktif' : 'Tidak Aktif'
        ],
            [
            'attribute' => 'memberCreate',
            'label' => 'Terdaftar Sejak',
            'value' => $inDate->setDateTime($model->memberCreate)
        ],
    ]
]);

echo Html::a(' Kembali', Url::to(['index']), ['class' => 'fa fa-reply btn btn-default btn-flat', 'style' => 'margin-top:-25px;margin-right:5px;']);
echo Html::a(' Ubah', Url::to(['update', 'id' => $model->memberId]), ['class' => 'fa fa-edit btn btn-primary btn-flat', 'style' => 'margin-top:-25px;margin-right:5px;']);
