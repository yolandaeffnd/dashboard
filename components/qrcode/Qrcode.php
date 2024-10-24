<?php

namespace app\components\qrcode;

use Yii;
use yii\base\Component;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QrCode
 *
 * @author IDEAPAD
 */
class Qrcode extends Component {

    public function init() {
        include dirname(__FILE__) . '/lib/qrlib.php';
        parent::init();
    }

    public function generate($data = '') {
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 5;
        $file = 'QRCode' . md5($data . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
        $filename = Yii::getAlias('@webroot/temp/qrcode/' . $file);
        if (!file_exists(Yii::getAlias('@webroot/temp'))) {
            mkdir(Yii::getAlias('@webroot/temp/'));
        }
        if (!file_exists(Yii::getAlias('@webroot/temp/qrcode'))) {
                mkdir(Yii::getAlias('@webroot/temp/qrcode/'));
            }
        $QR = new \QRcode();
        $QR->png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        return $file;
    }

}
