<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "lat_peserta".
 *
 * @property string $pesertaId
 * @property string $pesertaKlsId
 * @property integer $pesertaMemberId
 * @property double $pesertaSkorTerakhirTest
 * @property string $pesertaCreate
 * @property string $pesertaUpdate
 *
 * @property LatKelas $pesertaKls
 * @property Member $pesertaMember
 */
class LatPeserta extends \yii\db\ActiveRecord {

    public $klsPeriodeId;
    public $klsNama;
    public $kehadiran;
    public $pesertaNama;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'lat_peserta';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['pesertaKlsId', 'pesertaMemberId', 'pesertaSkorTerakhirTest', 'pesertaCreate'], 'required'],
                [['pesertaMemberId'], 'integer'],
                [['pesertaSkorTerakhirTest'], 'number'],
                [['pesertaCreate', 'pesertaUpdate','pesertaNama','pesertaId'], 'safe'],
                [['pesertaId'], 'string', 'max' => 10],
                [['pesertaKlsId'], 'string', 'max' => 20],
                [['pesertaKlsId'], 'exist', 'skipOnError' => true, 'targetClass' => LatKelas::className(), 'targetAttribute' => ['pesertaKlsId' => 'klsId']],
                [['pesertaMemberId'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['pesertaMemberId' => 'memberId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pesertaId' => 'Nomor Peserta',
            'pesertaKlsId' => 'Peserta Kls ID',
            'pesertaMemberId' => 'Nama Peserta',
            'pesertaSkorTerakhirTest' => 'Skor Tes Terakhir',
            'pesertaCreate' => 'Peserta Create',
            'pesertaUpdate' => 'Peserta Update',
            'klsNama'=>'Kelas Pelatihan',
            'klsPeriodeId'=>'Periode Pelatihan',
            'pesertaNama'=>'Nama Peserta'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPesertaKls() {
        return $this->hasOne(LatKelas::className(), ['klsId' => 'pesertaKlsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPesertaMember() {
        return $this->hasOne(Member::className(), ['memberId' => 'pesertaMemberId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPesertaAbsen()
    {
        return $this->hasMany(LatPesertaAbsen::className(), ['absenPesertaId' => 'pesertaId']);
    }

}
