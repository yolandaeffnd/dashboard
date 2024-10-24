<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\models\AppUser;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\referensi\models\RefInstrukturSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-instruktur',
    'enablePushState' => false,
    'enableReplaceState' => false
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'hover' => true,
    'pjax' => true,
    'toolbar' => [
        '{toggleData}',
    ],
    'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
            'attribute' => 'instNama',
            'value' => function ($data) {
                return $data->instNama;
            }
        ],
            [
            'attribute' => 'instJenkel',
            'width' => '50px;',
            'value' => function ($data) {
                return $data->instJenkel;
            }
        ],
            [
            'attribute' => 'instTelp',
            'value' => function ($data) {
                return $data->instTelp;
            }
        ],
            [
            'attribute' => 'instEmail',
            'value' => function ($data) {
                return $data->instEmail;
            }
        ],
            [
            'attribute' => 'instIsAktif',
            'format' => 'raw',
            'hAlign' => 'center',
            'value' => function ($data) {
                return ($data->instIsAktif == 1) ? '<b>Aktif</b>' : '<b><i>Tidak Aktif</i></b>';
            }
        ],
            [
            'label' => 'Akun',
            'value' => function ($data) {
                $akun = AppUser::find()->where('usernameApp=:username', [':username' => $data->instEmail]);
                if ($akun->count() == 0) {
                    return NULL;
                } else {
                    return $akun->one()->usernameApp;
                }
            }
        ],
            [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
    'panel' => [
        'type' => 'success',
        'heading' => 'Periode Pelatihan',
        'after' => false,
        'before' => false
    ],
]);
Pjax::end();
?>
