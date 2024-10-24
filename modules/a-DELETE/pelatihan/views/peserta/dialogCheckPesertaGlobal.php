<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\modules\pelatihan\models\RefProdi;
use app\modules\pelatihan\models\RefFakultas;
use app\modules\pelatihan\models\LatJadwal;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */
?>
<div class="lat-peserta-check">
    <div style="float: left;width: 18%;">
        <?php
        echo Html::img(Url::to(['/site/getfoto', 'filename' => $modelPeserta->pesertaMember->memberFoto]), ['style' => 'width:150px;height:190px;border-radius:5px;']);
        ?>
    </div>
    <div style="float: left;width: 82%;">
        <?php
        if ($modelPeserta->pesertaMember->memberMemberKatId == 1) {
            $attributes = [
                    [
                    'columns' => [
                            [
                            'attribute' => 'pesertaId',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaId
                        ],
                            [
                            'label' => 'Kategori',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberMemberKat->memberKatNama
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Nama Peserta',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'format' => 'raw',
                            'value' => '<b>' . $modelPeserta->pesertaMember->memberNama . '</b>'
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Tempat/ Tgl Lahir',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberTmpLahir . '/ ' . $inDate->setDate($modelPeserta->pesertaMember->memberTglLahir)
                        ],
                            [
                            'label' => 'Jenis Kelamin',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:30%;'],
                            'format' => 'raw',
                            'value' => ($modelPeserta->pesertaMember->memberJenkel == 'L') ? 'Laki-Laki' : 'Perempuan'
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Telp',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberTelp
                        ],
                            [
                            'label' => 'Email',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:30%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberEmail
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'NIM',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberMhsNim
                        ],
                            [
                            'label' => 'Angkatan',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:30%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberMhsAngkatan
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Program Studi',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => RefProdi::findOne($modelPeserta->pesertaMember->memberMhsProdiId)->prodiNama
                        ],
                            [
                            'label' => 'Fakultas',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:30%;'],
                            'format' => 'raw',
                            'value' => RefFakultas::findOne($modelPeserta->pesertaMember->memberMhsFakId)->fakNama
                        ],
                    ]
                ],
            ];
        } else {
            $attributes = [
                    [
                    'columns' => [
                            [
                            'attribute' => 'pesertaId',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaId
                        ],
                            [
                            'label' => 'Kategori',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberMemberKat->memberKatNama
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Nama Peserta',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'format' => 'raw',
                            'value' => '<b>' . $modelPeserta->pesertaMember->memberNama . '</b>'
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Tempat/ Tgl Lahir',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberTmpLahir . '/ ' . $inDate->setDate($modelPeserta->pesertaMember->memberTglLahir)
                        ],
                            [
                            'label' => 'Jenis Kelamin',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:30%;'],
                            'format' => 'raw',
                            'value' => ($modelPeserta->pesertaMember->memberJenkel == 'L') ? 'Laki-Laki' : 'Perempuan'
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Telp',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:35%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberTelp
                        ],
                            [
                            'label' => 'Email',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:15%;'],
                            'valueColOptions' => ['style' => 'text-align:left;vertical-align:top;width:30%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaMember->memberEmail
                        ],
                    ]
                ],
            ];
        }

        echo DetailView::widget([
            'model' => $modelPeserta,
            'condensed' => true,
            'hover' => true,
            'mode' => DetailView::MODE_VIEW,
            'panel' => [
//                    'hidden'=>true,
//                    'heading' => false,//'Detail Kelas Pelatihan',
//                    'type' => DetailView::TYPE_SUCCESS,
            ],
            'updateOptions' => [
                'hidden' => true,
            ],
            'deleteOptions' => [
                'hidden' => true,
            ],
            'attributes' => $attributes
        ]);
        ?>
    </div>
    <div style="clear: both;"></div>
    <div style="float: left;width: 100%;margin-top: -10px;">
        <?php
        echo DetailView::widget([
            'model' => $modelPeserta,
            'condensed' => true,
            'hover' => true,
            'mode' => DetailView::MODE_VIEW,
            'panel' => [
            ],
            'updateOptions' => [
                'hidden' => true,
            ],
            'deleteOptions' => [
                'hidden' => true,
            ],
            'attributes' => [
                    [
                    'columns' => [
                            [
                            'label' => 'Jenis Pelatihan',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'format' => 'raw',
                            'value' => '<b>' . $modelPeserta->pesertaKls->klsPeriode->periodeJnslat->jnslatNama . '</b>'
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Periode',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaKls->klsPeriode->periodeNama
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Kelas',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'format' => 'raw',
                            'value' => $modelPeserta->pesertaKls->klsNama
                        ],
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'label' => 'Ruang',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'format' => 'raw',
                            'value' => LatJadwal::findOne($jdwl)->jdwlRuang->ruangNama
                        ],
                            [
                            'label' => 'Pukul',
                            'labelColOptions' => ['style' => 'text-align:right;vertical-align:top;width:20%;'],
                            'format' => 'raw',
                            'value' => $inDate->setTime(LatJadwal::findOne($jdwl)->jdwlJamMulai).' s/d '.$inDate->setTime(LatJadwal::findOne($jdwl)->jdwlJamSelesai)
                        ],
                    ]
                ],
            ]
        ]);
        ?>
    </div>
    <?php
    //echo Html::button('OK', ['id'=>'btn-ok','class' => 'fa fa-check btn btn-primary btn-flat', 'style' => 'width:100%;margin-top:-25px;margin-bottom:-15px;margin-right:5px;']);
    echo Html::a(' OK', '#', ['id' => 'btn-ok', 'class' => 'fa fa-check btn btn-primary btn-flat', 'style' => 'width:100%;margin-top:-25px;margin-bottom:-15px;margin-right:5px;']);
    ?>
</div>
<?php
$params = urlencode(serialize(['peserta' => $peserta, 'jdwl' => $jdwl, 'tgl' => $tgl, 'ishadir' => $ishadir]));
$urlOk = Url::to(['simpanpeserta', 'act' => 'personal-set-hadir', 'params' => $params]);
$jsPopup = <<<JS
    $('#btn-ok').focus();
    $('#btn-ok').on('keypress',function(e){
        if(e.which == 13){
            $.ajax({
                type: 'GET',
                url: '{$urlOk}',
                success: function (data) {
                    if(data==410){
                        $('#modal').modal('hide');
                        alert('Terima Kasih!');
                    }else if(data==401){
                        $('#modal').modal('hide');
                        alert('Gagal Menyimpan Absensi!');
                    }else if(data==400){
                        $('#modal').modal('hide');
                        alert('Absensi Sudah Tercatat...');
                    }
                    $('#no-peserta').focus();
                    return false;
                },
            });
            return false;
        }
    });
    $('#btn-ok').on('click',function(e){
        $.ajax({
            type: 'GET',
            url: '{$urlOk}',
            success: function (data) {
                if(data==410){
                    $('#modal').modal('hide');
                    alert('Terima Kasih!');
                }else if(data==401){
                    $('#modal').modal('hide');
                    alert('Gagal Menyimpan Absensi!');
                }else if(data==400){
                    $('#modal').modal('hide');
                    alert('Absensi Sudah Tercatat...');
                }
                $('#no-peserta').focus();
                return false;
            },
        });
        return false;
    });
JS;
$this->registerJs($jsPopup);
?>