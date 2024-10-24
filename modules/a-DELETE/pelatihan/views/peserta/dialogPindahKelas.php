<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\modules\pelatihan\models\RefProdi;
use app\modules\pelatihan\models\RefFakultas;
use app\modules\pelatihan\models\LatKelas;
use app\assets\AppAsset;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\pelatihan\models\LatKelas */
?>
<div class="lat-peserta-check">
    <div style="width: 100%;">
        <div style="float: left;width: 18%;">
            <?php
            if (!empty($modelPeserta->pesertaMember->memberFoto)) {
                echo Html::img(Url::to(['/site/getfoto', 'filename' => $modelPeserta->pesertaMember->memberFoto]), ['style' => 'width:150px;height:190px;border-radius:5px;']);
            } else {
                echo Html::img(AppAsset::register($this)->baseUrl . '/images/nobody.png', ['style' => 'width:150px;height:190px;border-radius:5px;']);
            }
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
    </div>
    <div style="clear: both;"></div>
    <?php
    //Data Kelas
    $dataKelas = LatKelas::find()
            ->select([
                'klsId',
                'CONCAT(klsNama," (",GROUP_CONCAT(CONCAT(ref_hari.`hariInd`," : ",SUBSTR(lat_jadwal.`jdwlJamMulai`,1,5)," - ",SUBSTR(lat_jadwal.`jdwlJamSelesai`,1,5))),") Jml Peserta : ",(SELECT COUNT(*) FROM lat_peserta WHERE pesertaKlsId=klsId)) AS klsNama'
            ])
            ->join('JOIN', 'lat_jadwal', 'lat_jadwal.jdwlKlsId=lat_kelas.klsId')
            ->join('JOIN', 'ref_hari', 'ref_hari.hariKode=lat_jadwal.jdwlHariKode')
            ->where('klsPeriodeId=:periode',[
                ':periode'=>$periodeId
            ])->groupBy(['klsId'])
            ->all();
            
    $form = ActiveForm::begin([
                'id' => 'frm-cari-pindah-kelas-pelatihan',
                'type' => ActiveForm::TYPE_VERTICAL
    ]);

    echo Form::widget([
        'model' => $modelPeserta,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'pesertaKlsId' => ['label' => 'Peserta diatas akan dipindahkan ke kelas berikut ini',
                'type' => Form::INPUT_WIDGET, 'widgetClass' => Select2::className(), 'options' => [
                    'data' => ArrayHelper::map($dataKelas, 'klsId', 'klsNama'),
                    'size' => Select2:: MEDIUM,
                    'pluginOptions' => [
                        'allowClear' => false,
                        'multiple' => false,
                    ],
                ]
            ],
        ]
    ]);

    echo Form::widget([
        'model' => $modelPeserta,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' => '<div style="text-align: left; margin-top: 0px">'
                . Html::submitButton(' Pindahkan', ['type' => 'button', 'class' => 'fa fa-check btn btn-primary btn-flat', 'style' => 'width:100%;margin-top:-15px;'])
                . '</div>'
            ],
        ]
    ]);

    ActiveForm::end();
    ?>
    <?php
    //echo Html::button('OK', ['id'=>'btn-ok','class' => 'fa fa-check btn btn-primary btn-flat', 'style' => 'width:100%;margin-top:-25px;margin-bottom:-15px;margin-right:5px;']);
//    echo Html::a(' OK', '#', ['id' => 'btn-ok', 'class' => 'fa fa-check btn btn-primary btn-flat', 'style' => 'width:100%;margin-top:-25px;margin-bottom:-15px;margin-right:5px;']);
    ?>
    <!--<a href="" onkeypress="" onkeydown=""></a>-->
</div>
<?php
$urlGetData = Url::to(['setpindahkelas','id'=>$modelPeserta->pesertaId]);
$urlView = Url::to(['view','id'=>$klsasal]);
$jsPopupPindah = <<<JS
    $('#frm-cari-pindah-kelas-pelatihan').on('submit',function(e){
        $.ajax({
            type: 'GET',
            contentType:false,
            processData:false,
            url: '{$urlGetData}',
            data: $('#frm-cari-pindah-kelas-pelatihan').serialize(),
            success: function (data) {
                if(data==410){
                    alert('Pindah kelas berhasil...');
                    document.location='{$urlView}';
                }
                return false;
            },
        });
        return false;
    });
JS;
$this->registerJs($jsPopupPindah);
?>