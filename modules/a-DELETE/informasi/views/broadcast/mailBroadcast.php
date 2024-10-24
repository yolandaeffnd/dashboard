<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div style="border: solid 1px #cccccc;border-radius: 2px;width:800px;">
    <div style="border-bottom: solid 5px blue;">
        <h2 style='margin-bottom:0px;text-align: center;'>Sistem Informasi Manajemen Pelatihan Bahasa (SIMPB)</h2>
        <h1 style='margin-top:0px;text-align: center;'>UPT. Pusat Bahasa - Universitas Andalas</h1>
    </div>
    <div style="padding: 10px;font-size:14px;text-align: justify;">
        <h3><?php echo $judul; ?></h3>
        <?php echo Html::decode($isi); ?>
    </div>
    <div style="border-top: solid 3px #cccccc;margin:10px;">
        <p style="font-size:10px;text-align: center;">
            Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke email ini.
        </p>
    </div>
</div>