<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\mahasiswa\models;

use Yii;
use yii\base\Model;
use app\models\DAO;

/**
 * Description of Stock
 *
 * @author IDEAPAD
 */
class Terdaftar extends Model {

    public $fakId;
    public $thnAkt;
    public $prodiId;

    public function rules() {
        return [
                [['fakId', 'thnAkt', 'prodiId'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'fakId' => 'Fakultas',
            'thnAkt' => 'Tahun Angkatan',
            'prodiId' => 'Program Studi'
        ];
    }

    public function getJmlPerJenkel($act, $akt, $kode = '', $jenkel) {
        $conn = new DAO();
        $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan<=YEAR(NOW())-7 ORDER BY angkatan DESC LIMIT 1";
        $rsAkt = $conn->QueryRow($qAkt, []);
        if ($act == 'by-prodi') {
            if ($kode == null) {
                if ($rsAkt['angkatan'] == $akt) {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan<=:akt AND a1.mhsJenkel=:jenkel
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan<=:akt AND a2.mhsJenkel=:jenkel
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan<=:akt AND a3.mhsJenkel=:jenkel
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a2.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan<=:akt AND mhs.mhsJenkel=:jenkel";
                } else {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan=:akt AND a1.mhsJenkel=:jenkel
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan=:akt AND a2.mhsJenkel=:jenkel
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan=:akt AND a3.mhsJenkel=:jenkel
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan=:akt AND mhs.mhsJenkel=:jenkel";
                }
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':jenkel' => $jenkel
                ]);
            } else {
                if ($rsAkt['angkatan'] == $akt) {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan<=:akt AND a1.mhsJenkel=:jenkel AND c1.prodiKode=:prodi
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan<=:akt AND a2.mhsJenkel=:jenkel AND c2.prodiKode=:prodi
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan<=:akt AND a3.mhsJenkel=:jenkel AND c3.prodiKode=:prodi
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan<=:akt AND mhs.mhsJenkel=:jenkel AND mhs.prodiKode=:prodi";
                } else {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan=:akt AND a1.mhsJenkel=:jenkel AND c1.prodiKode=:prodi
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan=:akt AND a2.mhsJenkel=:jenkel AND c2.prodiKode=:prodi
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan=:akt AND a3.mhsJenkel=:jenkel AND c3.prodiKode=:prodi
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan=:akt AND mhs.mhsJenkel=:jenkel AND mhs.prodiKode=:prodi";
                }
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':prodi' => $kode,
                    ':jenkel' => $jenkel
                ]);
            }
        } else if ($act == 'by-fakultas') {
            /*
             * By Fakultas
             */
            if ($kode == null) {
                /*
                 * Jika Kode == null
                 */
                if ($rsAkt['angkatan'] == $akt) {
                    /*
                     * Jika parameter akt sama dengan angkatan dibawah 7 tahun
                     */
                    $query = "SELECT 
			COUNT(*) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan<=:akt AND mhs.mhsJenkel=:jenkel
                        ORDER BY mhs.fakId ASC";
                } else {
                    /*
                     * Jika parameter akt termasuk angkatan 7 tahun terakhir
                     */
                    $query = "SELECT 
			COUNT(*) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan=:akt AND mhs.mhsJenkel=:jenkel
                        ORDER BY mhs.fakId ASC";
                }
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':jenkel' => $jenkel
                ]);
            } else {
                /*
                 * Jika Kode != null
                 */
                if ($rsAkt['angkatan'] == $akt) {
                    /*
                     * Jika parameter akt sama dengan angkatan dibawah 7 tahun
                     */
                    $query = "SELECT 
			COUNT(*) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan<=:akt AND mhs.mhsJenkel=:jenkel AND mhs.fakId=:fak
                        ORDER BY mhs.fakId ASC";
                } else {
                    /*
                     * Jika parameter akt termasuk angkatan 7 tahun terakhir
                     */
                    $query = "SELECT 
			COUNT(*) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            'A' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            'C' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            'N' AS ket
                            #COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan=:akt AND mhs.mhsJenkel=:jenkel AND mhs.fakId=:fak
                        ORDER BY mhs.fakId ASC";
                }
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':fak' => $kode,
                    ':jenkel' => $jenkel
                ]);
            }
        }
        return $result['jml'];
    }
    
    public function getJmlPerJenkelFak($act, $akt, $fak,$prodi = '', $jenkel) {
        $conn = new DAO();
        $qAkt = "SELECT angkatan FROM ref_angkatan WHERE angkatan<=YEAR(NOW())-7 ORDER BY angkatan DESC LIMIT 1";
        $rsAkt = $conn->QueryRow($qAkt, []);
        if ($act == 'by-prodi') {
            if ($prodi == null) {
                if ($rsAkt['angkatan'] == $akt) {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan<=:akt AND a1.mhsJenkel=:jenkel AND e1.fakId=:fak
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan<=:akt AND a2.mhsJenkel=:jenkel AND e2.fakId=:fak
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan<=:akt AND a3.mhsJenkel=:jenkel AND e3.fakId=:fak
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a2.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan<=:akt AND mhs.mhsJenkel=:jenkel AND mhs.fakId=:fak";
                } else {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan=:akt AND a1.mhsJenkel=:jenkel AND e1.fakId=:fak
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan=:akt AND a2.mhsJenkel=:jenkel AND e2.fakId=:fak
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan=:akt AND a3.mhsJenkel=:jenkel AND e3.fakId=:fak
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan=:akt AND mhs.mhsJenkel=:jenkel AND mhs.fakId=:fak";
                }
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':fak'=>$fak,
                    ':jenkel' => $jenkel
                ]);
            } else {
                if ($rsAkt['angkatan'] == $akt) {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan<=:akt AND a1.mhsJenkel=:jenkel AND c1.prodiKode=:prodi AND e1.fakId=:fak
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan<=:akt AND a2.mhsJenkel=:jenkel AND c2.prodiKode=:prodi AND e2.fakId=:fak
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan<=:akt AND a3.mhsJenkel=:jenkel AND c3.prodiKode=:prodi AND e3.fakId=:fak
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan<=:akt AND mhs.mhsJenkel=:jenkel AND mhs.prodiKode=:prodi AND mhs.fakId=:fak";
                } else {
                    $query = "SELECT 
                        SUM(mhs.jml) AS jml
                        FROM (
                            SELECT 
                            a1.mhsNiu,
                            a1.`mhsAngkatan`,
                            e1.`fakId`,
                            e1.`fakNama`,
                            a1.`mhsJenkel`,
                            c1.`prodiKode`,
                            c1.`prodiJenjang`,
                            c1.`prodiNama`,
                            'A' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a1
                            JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                            JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                            JOIN `ref_fakultas` e1 ON e1.`fakId`=c1.`prodiFakId`
                            JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                            WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                            AND a1.mhsAngkatan=:akt AND a1.mhsJenkel=:jenkel AND c1.prodiKode=:prodi AND e1.fakId=:fak
                            GROUP BY c1.`prodiJenjang`,a1.`mhsAngkatan`,e1.fakId,a1.`mhsJenkel`,c1.`prodiKode`#,a1.mhsNiu
                            UNION
                            SELECT 
                            a2.mhsNiu,
                            a2.`mhsAngkatan`,
                            e2.`fakId`,
                            e2.`fakNama`,
                            a2.`mhsJenkel`,
                            c2.`prodiKode`,
                            c2.`prodiJenjang`,
                            c2.`prodiNama`,
                            'C' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a2
                            JOIN `fact_mahasiswa_cuti` b2 ON b2.`mhsctNiu`=a2.`mhsNiu`
                            JOIN `ref_prodi_nasional` c2 ON c2.`prodiKode`=a2.`mhsProdiDikti`
                            JOIN `ref_fakultas` e2 ON e2.`fakId`=c2.`prodiFakId`
                            JOIN `app_semester` d2 ON d2.smtId=b2.`mhsctSmtId`
                            WHERE a2.`mhsJenkel`<>'' AND a2.`mhsStatus` IN('C') AND d2.smtIsAktif='1'
                            AND a2.mhsAngkatan=:akt AND a2.mhsJenkel=:jenkel AND c2.prodiKode=:prodi AND e2.fakId=:fak
                            GROUP BY c2.`prodiJenjang`,a2.`mhsAngkatan`,e2.fakId,a2.`mhsJenkel`,c2.`prodiKode`#,a2.mhsNiu
                            UNION
                            SELECT 
                            a3.mhsNiu,
                            a3.`mhsAngkatan`,
                            e3.`fakId`,
                            e3.fakNama,
                            a3.`mhsJenkel`,
                            c3.`prodiKode`,
                            c3.`prodiJenjang`,
                            c3.`prodiNama`,
                            'N' AS ket,
                            COUNT(*)AS jml
                            FROM dim_mahasiswa a3
                            JOIN `ref_prodi_nasional` c3 ON c3.`prodiKode`=a3.`mhsProdiDikti`
                            JOIN `ref_fakultas` e3 ON e3.`fakId`=c3.`prodiFakId`
                            WHERE a3.`mhsJenkel`<>'' AND a3.`mhsStatus` IN('N')
                            AND a3.mhsAngkatan=:akt AND a3.mhsJenkel=:jenkel AND c3.prodiKode=:prodi AND e3.fakId=:fak
                            GROUP BY c3.`prodiJenjang`,a3.`mhsAngkatan`,e3.fakId,a3.`mhsJenkel`,c3.`prodiKode`#,a3.mhsNiu
                        )AS mhs
                        WHERE mhs.mhsAngkatan IN(SELECT angkatan FROM ref_angkatan) 
                        AND mhs.mhsAngkatan=:akt AND mhs.mhsJenkel=:jenkel AND mhs.prodiKode=:prodi AND mhs.fakId=:fak";
                }
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':fak'=>$fak,
                    ':prodi' => $prodi,
                    ':jenkel' => $jenkel
                ]);
            }
        } 
        return $result['jml'];
    }

    public function getJmlMhs($act, $akt = '', $kode = '', $jenkel = '') {
        $conn = new DAO();
        $result = [
            'JML' => 0
        ];
        if ($act == 'mhs-aktif-per-prodi') {
            if ($kode == null) {
                $query = "SELECT 
                    COUNT(*)AS JML
                    FROM dim_mahasiswa a1
                    JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                    JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                    JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                    WHERE a1.`mhsJenkel`<>'' AND a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                    AND a1.`mhsAngkatan`=:akt AND a1.`mhsJenkel`=:jenkel
                    GROUP BY c1.`prodiKode`
                    ORDER BY c1.`prodiJenjang` ASC";
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':jenkel' => $jenkel
                ]);
            } else {
                $query = "SELECT 
                    COUNT(*)AS JML
                    FROM dim_mahasiswa a1
                    JOIN `fact_mahasiswa_registrasi` b1 ON b1.`mhsregNiu`=a1.`mhsNiu`
                    JOIN `ref_prodi_nasional` c1 ON c1.`prodiKode`=a1.`mhsProdiDikti`
                    JOIN `app_semester` d1 ON d1.smtId=b1.`mhsregSmtId`
                    WHERE a1.`mhsStatus` IN('A') AND d1.smtIsAktif='1'
                    AND a1.`mhsAngkatan`=:akt
                    AND c1.`prodiKode`=:prodi and a1.`mhsJenkel`=:jenkel
                    GROUP BY c1.`prodiKode`
                    ORDER BY c1.`prodiJenjang` ASC";
                $result = $conn->QueryRow($query, [
                    ':akt' => $akt,
                    ':prodi' => $kode,
                    ':jenkel' => $jenkel
                ]);
            }
        }
        return $result['JML'];
    }

}
