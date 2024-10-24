<?php

namespace app\components;

use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SandiUrl
 *
 */
class SandiUrl {

    public function enkripParam($parameter) {
        $search = array('/', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $change = array('&', '1Z', '2Y', '3X', '4W', '5V', '6U', '7T', '8S', '9R', '0Q', '1P', '2O', '3N', '4M', '5L', '6K', '7J', '8I', '9H', '0G', '1F', '2E', '3D', '4C', '5B', '6A');
        $hasil = str_replace($search, $change, $parameter);
        $sisip = '9oUjk8y7f4590j65';
        $sisip1 = 'd3y5xc632bn12gd72gs';
        $lenght = strlen($hasil);
        $hasil1 = substr_replace($hasil, $sisip, $lenght);
        $result = substr_replace($hasil1, $sisip1, 5, 0);
        return $result;
    }

    public function dekripParam($parameter) {
        $sisip = '9oUjk8y7f4590j65';
        $sisip1 = 'd3y5xc632bn12gd72gs';
        $sisip1Lenght = strlen($sisip1);
        $hasil1 = substr_replace($parameter, '', 5, $sisip1Lenght);
        $parLenght = strlen($hasil1);
        $sisipLenght = strlen($sisip);
        $hasil = substr($hasil1, 0, $parLenght - $sisipLenght);
        $change = array('/', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $search = array('&', '1Z', '2Y', '3X', '4W', '5V', '6U', '7T', '8S', '9R', '0Q', '1P', '2O', '3N', '4M', '5L', '6K', '7J', '8I', '9H', '0G', '1F', '2E', '3D', '4C', '5B', '6A');
        $result = str_replace($search, $change, $hasil);
        return $result;
    }

}
