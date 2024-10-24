<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\pengolahan\models;

use Yii;
use yii\base\Model;

/**
 * Description of Stock
 *
 * @author IDEAPAD
 */
class Dimensi extends Model {

    public $fakId;
    public $thnAkt;
    public $isProses;

    public function rules() {
        return [
                [['fakId', 'thnAkt','isProses'], 'safe'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'fakId'=>'Fakultas',
            'thnAkt'=>'Tahun Angkatan'
        ];
    }

}
