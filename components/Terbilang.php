<?php

namespace app\components;

use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Terbilang
 * 1.000.000.000.000.000
 * @author Ade Priyanto
 */
class Terbilang {

    public $th = ['', 'Ribu', 'Juta', 'Milyar', 'Triliun'];
    public $dg = ['Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
    public $tn = ['Sepuluh', 'Sebelas', 'Dua Belas', 'Tiga Belas', 'Empat Belas', 'Lima Belas', 'Enam Belas', 'Tujuh Belas', 'Delapan Belas', 'Sembilan Belas'];
    public $tw = ['Dua Puluh', 'Tiga Puluh', 'Empat Puluh', 'Lima Puluh', 'Enam Puluh', 'Tujuh Puluh', 'Delapan Puluh', 'Sembilan Puluh'];

    public function Rupiah($s) {
        $result = '';
        $s = (string) $s;
        $s = str_replace('/[\, ]/g', '', $s);
        if (!floatval($s)) {
            $result = 'Not a number.';
        }
        $x = strlen($s);
        if ($x <= 14) {
            $n = str_split($s);
            $str = '';
            $sk = 0;
            for ($i = 0; $i < $x; $i++) {
                if (($x - $i) % 3 == 2) {
                    if ($n[$i] == '1') {
                        $str = $str . $this->tn[$n[$i + 1]] . ' ';
                        $i++;
                        $sk = 1;
                    } else if ($n[$i] != 0) {
                        $str = $str . $this->tw[$n[$i] - 2] . ' ';
                        $sk = 1;
                    }
                } else if ($n[$i] != 0) {
                    $str = $str . $this->dg[$n[$i]] . ' ';
                    if (($x - $i) % 3 == 0)
                        $str = $str . 'Ratus ';
                    $sk = 1;
                }
                if (($x - $i) % 3 == 1) {
                    if ($sk)
                        $str = $str . $this->th[($x - $i - 1) / 3] . ' ';
                    $sk = 0;
                }
            }
            if ($x != strlen($s)) {
                $y = strlen($s);
                $str = $str . 'point ';
                for ($i = $x + 1; $i < $y; $i++)
                    $str = $str . $this->dg[$n[$i]] . ' ';
            }
            $str = str_replace('/[\, ]/g', ' ', $str);
            $str = str_replace("Satu Ratus", "Seratus", $str);
            $str = str_replace("Satu Ribu", "Seribu", $str);
            $str = str_replace("Satu Puluh", "Sepuluh", $str) . ' Rupiah,-';
            $result = $str;
        } else {
            $result = 'Too big.';
        }

        return $result;
    }
    
    public function Angka($s) {
        $result = '';
        $s = (string) $s;
        $s = str_replace('/[\, ]/g', '', $s);
        if (!floatval($s)) {
            $result = 'Not a number.';
        }
        $x = strlen($s);
        if ($x <= 14) {
            $n = str_split($s);
            $str = '';
            $sk = 0;
            for ($i = 0; $i < $x; $i++) {
                if (($x - $i) % 3 == 2) {
                    if ($n[$i] == '1') {
                        $str = $str . $this->tn[$n[$i + 1]] . ' ';
                        $i++;
                        $sk = 1;
                    } else if ($n[$i] != 0) {
                        $str = $str . $this->tw[$n[$i] - 2] . ' ';
                        $sk = 1;
                    }
                } else if ($n[$i] != 0) {
                    $str = $str . $this->dg[$n[$i]] . ' ';
                    if (($x - $i) % 3 == 0)
                        $str = $str . 'Ratus ';
                    $sk = 1;
                }
                if (($x - $i) % 3 == 1) {
                    if ($sk)
                        $str = $str . $this->th[($x - $i - 1) / 3] . ' ';
                    $sk = 0;
                }
            }
            if ($x != strlen($s)) {
                $y = strlen($s);
                $str = $str . 'point ';
                for ($i = $x + 1; $i < $y; $i++)
                    $str = $str . $this->dg[$n[$i]] . ' ';
            }
            $str = str_replace('/[\, ]/g', ' ', $str);
            $str = str_replace("Satu Ratus", "Seratus", $str);
            $str = str_replace("Satu Ribu", "Seribu", $str);
            $str = str_replace("Satu Puluh", "Sepuluh", $str);
            $result = $str;
        } else {
            $result = 'Too big.';
        }

        return $result;
    }
    
    public function setCurrency($value){
        $x = Yii::$app->formatter->asCurrency($value,'Rp.');
        $y = explode('.', $x);
        $xy = isset($y[1])?$y[1]:0;
        return $xy;
    }
    
    public function setNumber($value){
        $x = Yii::$app->formatter->asInteger(empty($value)?0:$value);
        $y = str_replace(',', '.', $x);
        $xy = isset($y)?$y:0;
        return $xy;
    }

}
