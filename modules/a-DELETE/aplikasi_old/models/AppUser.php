<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "app_user".
 *
 * @property integer $idUser
 * @property string $nama
 * @property string $telp
 * @property string $usernameApp
 * @property string $passwordApp
 * @property integer $idGroup
 * @property string $isAktif
 * @property string $tglEntri
 */
class AppUser extends \yii\db\ActiveRecord {

    public $namaGroup;
    public $userData;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'app_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nama','telp', 'usernameApp', 'passwordApp', 'idGroup'], 'required'],
            [['idGroup'], 'integer'],
            [['isAktif'], 'string'],
            [['tglEntri'], 'safe'],
            [['nama'], 'string', 'max' => 30],
            [['telp'], 'string', 'max' => 100],
            [['usernameApp', 'passwordApp'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idUser' => 'Id User',
            'nama' => 'Nama Pengguna',
            'telp'=>'Telepon/HP',
            'usernameApp' => 'Username',
            'passwordApp' => 'Password',
            'idGroup' => 'Group Pengguna',
            'isAktif' => 'Status',
            'tglEntri' => 'Tgl Entri',
            'namaGroup'=>'Group Pengguna'
        ];
    }

}
