<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\akademik\models;

use Yii;
use yii\base\Model;
use app\models\DAO;

/**
 * Description of Stock
 *
 * @author IDEAPAD
 */
class Peminat extends Model {

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
            'thnAkt' => 'Tahun Masuk',
            'prodiId' => 'Program Studi'
        ];
    }

}
