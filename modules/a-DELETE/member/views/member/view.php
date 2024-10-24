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

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatPeriode */

$this->title = 'Detail Member';
$this->params['breadcrumbs'][] = ['label' => 'Member', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-periode-view">
    <?php
    if ($model->memberMemberKatId == '1') {
        //Kategori Mahasiswa
        echo $this->render('_viewMhs', [
            'model' => $model,
        ]);
    } else if ($model->memberMemberKatId =='2') {
        //Kategori Dosen
        echo $this->render('_viewDosen', [
            'model' => $model,
        ]);
    }else {
        //Kategori Umum dan Karyawan
        echo $this->render('_view', [
            'model' => $model,
        ]);
    }

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
                'value' => function ($data) {
                    return $data->pesertaId;
                }
            ],
                [
                'attribute' => 'pesertaKlsId',
                'label' => 'Jenis Pelatihan',
                'format' => 'raw',
                'value' => function ($data) {
                    $inDate = new IndonesiaDate();
                    return $data->pesertaKls->klsPeriode->periodeJnslat->jnslatNama . '<br/>' . $data->pesertaKls->klsPeriode->periodeNama . '<br/>(' . $inDate->setDate($data->pesertaKls->klsPeriode->periodeLakMulai) . ' s/d ' . $inDate->setDate($data->pesertaKls->klsPeriode->periodeLakSelesai).')';
                }
            ],
                [
                'attribute' => 'pesertaKlsId',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->pesertaKls->klsNama;
                }
            ],
                [
                'attribute' => 'pesertaIsFree',
                'format' => 'raw',
                'value' => function ($data) {
                    return ($data->pesertaIsFree == 1) ? 'Ya' : 'Tidak';
                }
            ],
                [
                'attribute' => 'pesertaCreate',
                'format' => 'raw',
                'value' => function ($data) {
                    $inDate = new IndonesiaDate();
                    return $inDate->setDateTime($data->pesertaCreate);
                }
            ],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Riwayat Pelatihan',
            'after' => false,
            'before' => false
        ],
    ]);
    ?>
</div>
<?php
$urlReset = Url::to(['resetpassword', 'id' => $model->memberId]);
$js = <<<JS
    $('#btn-reset-password').on('click',function(e){
        krajeeDialog.confirm("Apakah anda akan mereset password akun ini dan mengirimkan ke emailnya?", function (result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    contentType:false,
                    processData:false,
                    url: '{$urlReset}',
                    success: function (data) {
                        if(data==410){
                            alert('Reset password berhasil & Password sudah dikirim ke email!');
                        }else if(data==401){
                            alert('Reset password berhasil, tetapi email tidak terkirim!');
                        }else if(data==400){
                            alert('Reset password gagal!');
                        }
                        krajeeDialog.remove();
                        return false;
                    },
                });
            } else {
                this.remove();
            }
        });
    });
JS;
$this->registerJs($js);
?>