<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "app_action".
 *
 * @property integer $idAction
 * @property integer $idMenu
 * @property integer $idKategori
 * @property string $actionFn
 * @property string $actionDesk
 */
class AppChart extends \yii\db\ActiveRecord {

    // public $labelMenu;
    public $nama_kategori;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'app_chart';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['idKategori', 'nama_chart','url_chart','posisiChart'], 'required'],
            [['idKategori'], 'integer'],
            [['unitId'], 'default', 'value' => null],
            [['posisiChart'], 'integer'],
            [['nama_chart'], 'string', 'max' => 255],
            [['url_chart'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idChart' => 'Id Chart',
            'idKategori' => 'Kategori',
            'nama_chart' => 'Nama Chart',
            'url_chart' => 'Url Chart',
            'unitId' => 'Unit',
            'posisiChart' => 'Posisi Chart',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getIdMenu0() {
    //     return $this->hasOne(AppMenu::className(), ['idMenu' => 'idMenu']);
    // }

    public function getIdKategori0() {
        return $this->hasOne(AppKategori::className(), ['idKategori' => 'idKategori']);
    }

    public function getIdUnit0() {
        return $this->hasOne(RefFakultas::className(), ['fakId' => 'unitId']);
    }


    public function getChartById($params)
    {
        $result = RefFakultas::findOne($params);
        if ($result !== null) {
            return $result->fakNama;
        } else {
            // Handle the case where no record is found
            return null; // Or any other value or action you need
        }
    }

}