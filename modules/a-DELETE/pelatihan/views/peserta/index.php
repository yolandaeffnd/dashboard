<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pelatihan\models\LatKelasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peserta Kelas Pelatihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-peserta-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_index', ['dataProvider' => $dataProvider]); ?>
<!--    <div id="data-peserta-pelatihan">
         Menampilkan Data 
        <div class="label-inverse" style="text-align: center;margin-bottom: 20px;padding: 50px;font-size: 18px;">Loading...</div>
    </div>-->
</div>
<?php
//Url
//$urlGetData = Url::to(['getindex']);
//$js = <<< JS
//    //Load Data
//    $('#data-peserta-pelatihan').load('{$urlGetData}');
//    $('#frm-cari-peserta-pelatihan').on('submit',function(e){
//        $.ajax({
//            type: 'GET',
//            contentType:false,
//            processData:false,
//            url: '{$urlGetData}',
//            data: $('#frm-cari-peserta-pelatihan').serialize(),
//            success: function (data) {
//                $('#data-peserta-pelatihan').html(data);
//                return false;
//            },
//        });
//        return false;
//    });
//JS;
//$this->registerJs($js);
?>