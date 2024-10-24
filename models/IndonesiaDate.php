<?php

namespace app\models;

use Yii;
use app\models\DAO;
use yii\db\Query;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndonesiaDate
 *
 * @author LPTIK
 */
class IndonesiaDate {

    public $arrMinggu = ['',
        'I',
        'II',
        'III',
        'IV'
    ];
    public $arrBulan = ['',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    public $arrBulanShort = ['',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agt',
        'Sep',
        'Okt',
        'Nov',
        'Des'
    ];
    private $arrEngBulan = ['',
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    public function getNow() {
        return date('Y-m-d H:i:s');
    }
    
    public function getTime() {
        return date('H:i:s');
    }

    public function getDate() {
        return date('Y-m-d');
    }

    public function getDay() {
        return date('d');
        ;
    }

    public function getMonth() {
        return date('m');
    }

    public function getYear() {
        return date('Y');
    }

    public function setEngDate($tanggal) {
        if (!empty($tanggal)) {
            $exp = explode('-', $tanggal);
            $tglExp = explode(' ', $exp[2]);
            $bln = $this->arrEngBulan[(int) $exp[1]];
            $tgl = (int) $tglExp[0];
            $date = $bln . ' ' . $tgl . ', ' . $exp[0];
            return $date;
        }
    }

    public function setMonth($bln) {
        if (!empty($bln)) {
            $bln = $this->arrBulan[$bln];
            return $bln;
        }
    }

    public function setShortMonth($bln) {
        if (!empty($bln)) {
            $bln = $this->arrBulanShort[$bln];
            return $bln;
        }
    }

    public function setWeek($week) {
        if (!empty($week)) {
            $week = $this->arrMinggu[$week];
            return $week;
        }
    }

    public function setDate($tanggal) {
        if (!empty($tanggal)) {
            $exp = explode('-', $tanggal);
            $tglExp = explode(' ', $exp[2]);
            $bln = $this->arrBulan[(int) $exp[1]];
            $date = $tglExp[0] . ' ' . $bln . ' ' . $exp[0];
            return $date;
        }
    }

    public function setDate2($tanggal) {
        if (!empty($tanggal)) {
            $tglExp = explode(' ', $tanggal);
            $date = $tglExp[0];
            return $date;
        }
    }

    public function setDateTime($tanggal) {
        if (!empty($tanggal)) {
            $exp = explode(' ', $tanggal);
            $exp1 = explode('-', $exp[0]);
            $tglExp = explode(' ', $exp1[2]);
            $bln = $this->arrBulan[(int) $exp1[1]];
            $date = $tglExp[0] . ' ' . $bln . ' ' . $exp1[0] . ' ' . $exp[1];
            return $date;
        }
    }

    public function setTime($tanggal) {
        if (!empty($tanggal)) {
            $exp = explode(' ', $tanggal);
            if (count($exp) > 1) {
                $exp1 = explode(':', $exp[1]);
                $time = $exp1[0] . ':' . $exp1[1];
            } else {
                $exp1 = explode(':', $tanggal);
                $time = $exp1[0] . ':' . $exp1[1];
            }
            return $time;
        }
    }

    public function setDateMonth($tanggal) {
        if (!empty($tanggal)) {
            $exp = explode('-', $tanggal);
            $tglExp = explode(' ', $exp[2]);
            $bln = $this->arrBulan[(int) $exp[1]];
            $date = $bln . ' ' . $exp[0];
            return $date;
        }
    }

    public function setDayTime($time) {
        if (!empty($time)) {
            $exp = explode(':', $time);
            $time = $exp[0] . ':' . $exp[1];
            return $time;
        }
    }

    public function setDateTimeSpeach($datetime) {
        $query = (new DAO())->QueryRow("SELECT DATEDIFF(:skrg,:val) AS ddiff,TIMEDIFF(:skrg,:val) AS tdiff", [
            ':skrg' => $this->getNow(),
            ':val' => $datetime
        ]);
        if ($query['ddiff'] == 0) {
            $exp = explode(':', $query['tdiff']);
            if ($exp[0] == '00') {
                if ($exp[1] == '00') {
                    $result = ((int) $exp[2]) . ' detik lalu';
                } else {
                    $result = ((int) $exp[1]) . ' menit lalu';
                }
            } else {
                if ($exp[0] > 6) {
                    $result = 'Hari ini';
                } else {
                    $result = ((int) $exp[0]) . ' jam lalu';
                }
            }
        } else {
            if ($query['ddiff'] == 1) {
                $result = 'Kemarin';
            } else {
                $exp = explode(' ', $datetime);
                $exp1 = explode('-', $exp[0]);
                $bln = $this->arrBulanShort[$exp1[1]];
                $result = $exp1[2] . ' ' . $bln . ' ' . $exp1[0];
            }
        }
        return $result;
    }

    public function setDateTimeSpeachRange($datetimeMulai, $datetimeSelesai) {
        $query = (new DAO())->QueryRow("SELECT DATEDIFF(:skrg,:valMulai) AS mulaiDdiff,TIMEDIFF(:skrg,:valMulai) AS mulaiTdiff, DATEDIFF(:skrg,:valSelesai) AS selesaiDdiff,TIMEDIFF(:skrg,:valSelesai) AS selesaiTdiff", [
            ':skrg' => $this->getNow(),
            ':valMulai' => $datetimeMulai,
            ':valSelesai' => $datetimeSelesai
        ]);
        if ($query['mulaiDdiff'] == 0) {
            $exp = explode(':', $query['mulaiTdiff']);
            if ($exp[0] . ':' == "-00:") {
                if ($exp[0] . ':' . $exp[1] == "-00:00") {
                    $result = ((int) $exp[2]) . ' detik lagi';
                } else {
                    $result = ((int) $exp[1]) . ' menit lagi';
                }
            } else {
                if ($exp[0] >= 0) {
                    $expM = explode(' ', $datetimeMulai);
                    $expS = explode(' ', $datetimeSelesai);
                    $expS1 = explode('-', $expS[0]);
                    $bln = $this->arrBulanShort[$expS1[1]];
                    if ($expM[0] == $expS[0]) {
                        $result = 'Agenda Sedang Berlangsung s/d Pukul ' . $expS[1];
                    } else {
                        $result = 'Agenda Sedang Berlangsung s/d Tanggal ' . $expS1[2] . ' ' . $bln . ' ' . $expS1[0] . ' Pukul ' . $expS[1];
                    }
                } else {
                    $result = (-(int) $exp[0]) . ' jam ' . ((int) $exp[1]) . ' menit lagi';
                }
            }
        } else {
            $expM = explode(' ', $datetimeMulai);
            $expM1 = explode('-', $expM[0]);
            $blnM = $this->arrBulanShort[$expM1[1]];
            $expS = explode(' ', $datetimeSelesai);
            $expS1 = explode('-', $expS[0]);
            $blnS = $this->arrBulanShort[$expS1[1]];
            if ($expM[0] == $expS[0]) {
                $result = $expM1[2] . ' ' . $blnM . ' ' . $expM1[0] . ' Pukul ' . $expM[1] . ' s/d Pukul ' . $expS[1];
            } else {
                $result = $expM1[2] . ' ' . $blnM . ' ' . $expM1[0] . ' Pukul ' . $expM[1] . ' s/d ' . $expS1[2] . ' ' . $blnS . ' ' . $expS1[0] . ' Pukul ' . $expS[1];
            }
        }
        return $result;
    }

    public function setDateRange($dateMulai, $dateSelesai) {
        $paramSatu = explode('-', $dateMulai);
        $paramDua = explode('-', $dateSelesai);
        if ($dateMulai == $dateSelesai) {
            $result = $paramSatu[2].' '.$this->arrBulan[(int)$paramSatu[1]].' '.$paramSatu[0];
        } else {
            if ($paramSatu[0] == $paramDua[0]) {
                if ($paramSatu[1] == $paramDua[1]) {
                    $result = $paramSatu[2] . ' - ' . $paramDua[2] . ' ' . $this->arrBulan[(int)$paramSatu[1]] . ' ' . $paramSatu[0];
                } else {
                    $result = $paramSatu[2] . ' ' . $this->arrBulan[(int)$paramSatu[1]] . ' s/d ' . $paramDua[2] . ' ' . $this->arrBulan[(int)$paramDua[1]] . ' ' . $paramSatu[0];
                }
            } else {
                $result = $paramSatu[2] . ' ' . $this->arrBulan[(int)$paramSatu[1]] . ' ' . $paramSatu[0] . ' s/d ' . $paramDua[2] . ' ' . $this->arrBulan[(int)$paramDua[1]] . ' ' . $paramDua[0];
            }
        }

        return $result;
    }

}
