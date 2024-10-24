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
        Hi <?php echo $nama; ?>!<br/>
        Selamat anda telah terdaftar sebagai Instruktur Pelatihan Bahasa di UPT. Pusat Bahasa Universitas Andalas.<br/>
        Berikut dikirim ulang keterangan akun anda :<br/>
        <table style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px;">
            <tr>
                <td>Email</td>
                <td>:</td>
                <td><?php echo $email; ?></td>
            </tr>
            <tr>
                <td>Password</td>
                <td>:</td>
                <td><?php echo $pass; ?></td>
            </tr>
        </table>
        Silahkan gunakan <b><i>Email</i></b> sebagai <b><i>Username</i></b> dan <b><i>Password Baru</i></b> anda untuk login di <?php echo Html::a('http://simpb.lc.unand.ac.id/backoffice', 'http://simpb.lc.unand.ac.id/backoffice', ['target' => '_blank']); ?>.<br/>
        Demikian informasi ini disampaikan, Terima kasih.
    </div>
    <div style="border-top: solid 3px #cccccc;margin:10px;">
        <p style="font-size:10px;text-align: center;">
            Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke email ini.
        </p>
    </div>
</div>