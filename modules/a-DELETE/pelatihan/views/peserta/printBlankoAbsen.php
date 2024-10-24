<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\modules\pelatihan\models\RefInstruktur;
use app\modules\pelatihan\models\SetTtd;

$inDate = new IndonesiaDate();

/* @var $this yii\web\View */
/* @var $model app\modules\peserta\models\Member */
$this->title = 'Catak Blanko Absen';
?>
<style>
    /*BEGIN RESRET CSS*/
    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed, 
    figure, figcaption, footer, header, hgroup, 
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }
    /* HTML5 display-role reset for older browsers */
    article, aside, details, figcaption, figure, 
    footer, header, hgroup, menu, nav, section {
        display: block;
    }
    body {
        line-height: 1;
    }
    ol, ul {
        list-style: none;
    }
    blockquote, q {
        quotes: none;
    }
    blockquote:before, blockquote:after,
    q:before, q:after {
        content: '';
        content: none;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    /*END RESET CSS*/
    .page-print{
        margin: auto;
        padding: 0px;
        font-size: 14px;
        /*height: 100%;*/
        width: 100%;
        clear: both;
        /*border: 1px solid;*/
    }
    .page-print table{
        margin: 0px;
        padding: 0px;
        text-align: left;
        font-size: 11px;
        border-collapse: collapse;
        width: 100%;
        font-family: 'Arial';
    }
    th{
        font-weight: bold;
    }
    thead{
        background-color: #cccccc;
    }
    .page-print .tabel-border th,.tabel-border td{
        border: solid 1px;
        border-collapse: collapse;
        border-color: black;
    }
    .page-print .tabel-no-border th,.tabel-no-border td{
        border: none;

    }
    .page-print table th,td{
        padding-top: 1px;
        padding-bottom: 1px;
        padding-left: 2px;
        padding-right: 2px;
    }
    .page-print .info{
        background-color: #cccccc;
        border-collapse: collapse;
        border: solid 1px #cccccc;
    }
    .page-print .detail th,.detail td {
        border: solid 1px #cccccc;
        border-collapse: collapse;
    }
    .page-print h3{
        text-decoration: underline;
        text-align: center;
        font-family: 'Arial';
        font-weight: bold;
        font-size: 18px;
        letter-spacing: 1px;
    }
    .page-print .page-print-header{
        border-bottom: 2px solid;
        margin-top: 0px;
        padding-top: 0px;
    }
    .page-print .page-print-header .logo{
        margin-top: 0px;
        padding-left: 10px;
        padding-right: 10px;
        /*margin-left: 5%;*/
        /*text-align: center;*/
        float: left;
    }
    .page-print .page-print-header .kop{
        margin-top: 0px;
        padding-top: 5px;
        /*border: 1px solid;*/
        margin-left: 70px;
    }
    .page-print .page-print-header .kop h2{
        margin: 0px;
        padding: 0px;
        letter-spacing: 7px;
        font-size: 30px;
        font-family: 'Times';
        font-weight: normal;
        text-align: left;
    }
    .page-print .page-print-header .kop h4{
        margin: 0px;
        padding: 0px;
        font-size: 24px;
        font-family: 'Arial';
        letter-spacing: 1px;
        font-weight: bold;
        text-align: left;
        margin-top: -5px;
    }
    .page-print .page-print-header .kop p{
        margin: 0px;
        padding: 0px;
        text-align: left;
        font-size: 12px;
        font-family: 'arial';
        letter-spacing: 1px;
    }
    .page-print .ttd{
        text-align: right;
        margin-top: 50px;
        border-top: 1px solid;
    }
    .page-print .ttd .ttd-tabel th, .ttd.ttd-tabel td{
        text-align: center;
        /*font-size: 13px;*/
        border-collapse: collapse;
        width: 100%;
    }
    .clear{
        clear: both;
    }
    #page-break{
        page-break-before: always;
    }
</style>
<?php
if ($rsKelas['klsMeetingMax'] <= 15) {
    $meet = 15;
    $hal = 1;
} else {
    $meet = 15;
    $hal = round($rsKelas['klsMeetingMax'] / 15);
}
for ($h = 1; $h <= $hal; $h++) {
    ?>
    <div class="page-print">
        <div class="page-print-header">
            <div class="logo" style="width: 50px;">
                <img style="width: 50px;" src="<?php echo Url::to(['/site/image', 'filename' => 'logo-header.png']); ?>"/>
            </div>
            <div class="kop">
                <h4 style="font-size:20px;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN TINGGI</h4>
                <h2 style="font-weight: bold;font-size:25px;">UPT.PUSAT BAHASA - UNIVERSITAS ANDALAS</h2>
                <!--<h2 style="font-weight: bold;">UNIVERSITAS ANDALAS</h2>-->
                <!--<h4 style="font-style: italic;margin-top: 0px;">Sistem Informasi Manajemen Pelayanan Rumah Tangga (SimpelRT)</h4>-->
                <p>Gedung Pusat Bahasa, Kampus Limau Manis Padang</p>
            </div>
            <div class="clear"></div>
        </div>
        <h3 style="font-size: 16px;margin-top: 10px;">DAFTAR HADIR PELATIHAN</h3>
        <!--<h4 style="text-align:center;margin-top:-19px;">Nomor : <?php //echo $transaksi->trxMemoNomor;                                          ?></h4>-->
        <!--<h4 style="text-align:center;margin-top:-15px;">KODE BOOKING : <?php // echo $transaksi->trxKodeBooking;                                                 ?></h4>-->
        <br/>
        <div style="margin-bottom: 10px;">
            <table class="tabel-no-border">
                <tr style="height: 15px;">
                    <th style="width: 15%;">Jenis Pelatihan/ Periode</th>
                    <th style="width: 1%;">:</th>
                    <th style="width: 84%;text-align: left;padding-left: 0px;">
                        <?php
                        echo $rsKelas['jnslatNama'] . ' / ' . $rsKelas['periodeNama'];
                        ?>
                    </th>
                </tr>
                <tr style="height: 15px;">
                    <th>Kelas</th>
                    <th>:</th>
                    <th>
                        <?php
                        echo $rsKelas['klsNama'];
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>Instruktur</th>
                    <th>:</th>
                    <th>
                        <ul style="list-style: lower-alpha;margin-top: 0px;margin-left: 13px;">
                            <?php
                            $instruktur = RefInstruktur::find()
                                    ->join('JOIN', 'lat_kelas_instruktur', 'lat_kelas_instruktur.instId=ref_instruktur.instId')
                                    ->where('lat_kelas_instruktur.klsId=:kls', [':kls' => $kls])
                                    ->each();
                            foreach ($instruktur as $val) {
                                ?>
                                <li><?php echo $val['instNama']; ?></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </th>
                </tr>
            </table>
        </div>
        <?php
        $no = 1;
        for ($pg = 1; $pg <= $jmlPage; $pg++) {
            $rsPeserta = $conn->QueryAll($qPeserta, [
                ':kls' => $kls,
                ':page' => ($pg - 1) * 15,
                ':ofset' => $ofset
            ]);
            ?>
            <div id="page-absen">
                <table class="tabel-border">
                    <thead>
                        <tr style="height: 20px;">
                            <th rowspan="2" style="text-align: center;vertical-align: middle;width: 20px;">No</th>
                            <th rowspan="2" style="text-align: center;vertical-align: middle;width: 80px;">Nomor Peserta</th>
                            <th rowspan="2" style="text-align: center;vertical-align: middle;width: 250px;">Nama Peserta</th>
                            <th rowspan="2" style="text-align: center;vertical-align: middle;width: 100px;">No.Telp</th>
                            <th colspan="<?php echo $meet; ?>" style="text-align: center;vertical-align: middle;">Pertemuan</th>
                        </tr>
                        <tr style="height: 20px;">
                            <?php
                            for ($i = 1; $i <= $meet; $i++) {
                                $per = ($meet * $h) - ($meet - $i);
                                ?>
                                <th style="text-align: center;width: 80px;vertical-align: middle;"><?php echo $per; ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rsPeserta as $valPeserta) {
                            ?>
                            <tr>
                                <td style="height: 25px;vertical-align: middle;text-align: center;width: 20px;"><?php echo $no; ?></td>
                                <td style="vertical-align: middle;width: 80px;"><?php echo $valPeserta['pesertaId']; ?></td>
                                <td style="vertical-align: middle;width: 250px;"><?php echo strtoupper($valPeserta['memberNama']); ?></td>
                                <td style="vertical-align: middle;width: 100px;"><?php echo $valPeserta['memberTelp']; ?></td>
                                <?php
                                for ($i = 1; $i <= $meet; $i++) {
                                    ?>
                                    <td style="text-align: center;width: 80px;"></td>
                                <?php } ?>
                            </tr>
                            <?php
                            $no++;
                        }
                        if ($pg == $jmlPage) {
                            ?>
                            <tr style="background-color: #cccccc;">
                                <th style="height: 25px;text-align: right;vertical-align: middle;width: 450px;" colspan="4">Jml Hadir</th>
                                <?php
                                for ($i = 1; $i <= $meet; $i++) {
                                    ?>
                                    <td style="text-align: center;width: 80px;"></td>
                                <?php } ?>
                            </tr>
                            <tr style="background-color: #cccccc;">
                                <th style="height: 25px;text-align: right;vertical-align: middle;width: 450px;" colspan="4">Jml Tidak Hadir</th>
                                <?php
                                for ($i = 1; $i <= $meet; $i++) {
                                    ?>
                                    <td style="text-align: center;width: 80px;"></td>
                                <?php } ?>
                            </tr>
                            <tr style="background-color: #cccccc;">
                                <th style="height: 25px;text-align: right;vertical-align: middle;width: 450px;" colspan="4">Paraf Instruktur</th>
                                <?php
                                for ($i = 1; $i <= $meet; $i++) {
                                    ?>
                                    <td style="text-align: center;width: 80px;"></td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
            <?php
            if ($pg == $jmlPage) {
                $ttd = SetTtd::find()->where('ttdKode="TTD-BLANKO-ABSEN" AND ttdPosisi="kanan"')->one();
                ?>
                <br/>
                <div style="width: 30%;float: left;text-align: center;">

                </div>
                <div style="width: 40%;float: left;text-align: center;">

                </div>
                <div style="width: 30%;float: right;text-align: center;">
                    <table class="tabel-no-border">
                        <tr>
                            <th>Mengetahui,</th>
                        </tr>
                        <tr>
                            <th><?php echo $ttd->ttdJabatan; ?></th>
                        </tr>
                        <tr>
                            <td style="height: 70px;"></td>
                        </tr>
                        <tr>
                            <th><?php echo $ttd->ttdNama; ?></th>
                        </tr>
                        <tr>
                            <th><?php echo ($ttd->ttdNip != '-') ? 'Nip.' . $ttd->ttdNip : ''; ?></th>
                        </tr>
                    </table>
                </div>
                <div class="clear"></div>
                <?php
            }
            ?>
            <div style="text-align: right;margin-top: 8px;">
                <?php echo $h . '.' . $pg . '/' . $hal; ?>
            </div>
            <?php
            if ($pg != $jmlPage) {
                ?>
                <div id="page-break"></div>
                <?php
            }
        }
        ?>
        <div class="clear"></div>
    </div>
    <?php
    if ($h != $hal) {
        ?>
        <div id="page-break"></div>
        <?php
    }
}
?>