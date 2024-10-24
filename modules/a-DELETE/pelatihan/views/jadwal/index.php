<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pelatihan\models\LatJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jadwal Pelatihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lat-jadwal-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    echo $this->render('_index', [
        'dataProvider' => $dataProvider,
    ]);
    ?>
<!--    <div id="data-jadwal-pelatihan-">
         Menampilkan Data 
        <div class="label-inverse" style="text-align: center;margin-bottom: 20px;padding: 50px;font-size: 18px;">Loading...</div>
    </div>-->
</div>
<?php
//Url
//$urlGetData = Url::to(['getindex']);
//$js = <<< JS
//    //Load Data
//    $('#data-jadwal-pelatihan').load('{$urlGetData}');
//    $('#frm-cari-jadwal-pelatihan').on('submit',function(e){
//        $.ajax({
//            type: 'GET',
//            contentType:false,
//            processData:false,
//            url: '{$urlGetData}',
//            data: $('#frm-cari-jadwal-pelatihan').serialize(),
//            success: function (data) {
//                $('#data-jadwal-pelatihan').html(data);
//                return false;
//            },
//        });
//        return false;
//    });
//JS;
//$this->registerJs($js);
?>