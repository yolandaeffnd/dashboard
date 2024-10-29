<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "app_action".
 *
 * @property integer $idAction
 * @property integer $idMenu
 * @property string $actionFn
 * @property string $actionDesk
 */
class AppKategori extends \yii\db\ActiveRecord {

    public $labelMenu;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'app_kategori';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nama_kategori', 'jenis_kategori'], 'required'],
            [['nama_kategori'], 'string', 'max' => 50],
            [['jenis_kategori'], 'in', 'range' => ['keuangan', 'kepegawaian', 'kemahasiswaan']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idKategori' => 'Id Kategori',
            'nama_kategori' => 'Nama Kategori',
            'jenis_kategori' => 'Jenis Kategori',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */

}