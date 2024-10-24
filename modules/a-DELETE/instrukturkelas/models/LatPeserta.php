<?php

namespace app\modules\instrukturkelas\models;

use Yii;

/**
 * This is the model class for table "lat_peserta".
 *
 * @property string $pesertaId
 * @property string $pesertaKlsId
 * @property string $pesertaMemberId
 * @property double $pesertaSkorTerakhirTest
 * @property string $pesertaIsFree
 * @property string $pesertaCreate
 * @property string $pesertaUpdate
 *
 * @property InvoiceBank[] $invoiceBanks
 * @property LatKelas $pesertaKls
 * @property Member $pesertaMember
 * @property LatPesertaAbsen[] $latPesertaAbsens
 */
class LatPeserta extends \yii\db\ActiveRecord {

    public $klsPeriodeId;
    public $klsNama;
    public $kehadiran;
    public $pesertaNama;
    public $absenJdwlId;
    public $absenTgl;

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
            //[['pesertaId', 'pesertaKlsId', 'pesertaMemberId', 'pesertaSkorTerakhirTest', 'pesertaIsFree', 'pesertaCreate'], 'required'],
                [['absenJdwlId','absenTgl','pesertaId', 'pesertaKlsId', 'pesertaMemberId', 'pesertaSkorTerakhirTest', 'pesertaIsFree', 'pesertaCreate'], 'safe'],
                [['pesertaSkorTerakhirTest'], 'number'],
                [['pesertaIsFree'], 'string'],
                [['pesertaCreate', 'pesertaUpdate', 'klsNama', 'klsPeriodeId', 'pesertaNama'], 'safe'],
                [['pesertaId'], 'string', 'max' => 10],
                [['pesertaKlsId'], 'string', 'max' => 20],
                [['pesertaMemberId'], 'string', 'max' => 15],
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
            'pesertaMemberId' => 'Peserta Member ID',
            'pesertaSkorTerakhirTest' => 'Peserta Skor Terakhir Test',
            'pesertaIsFree' => 'Peserta Is Free',
            'pesertaCreate' => 'Peserta Create',
            'pesertaUpdate' => 'Peserta Update',
            'klsNama' => 'Kelas Pelatihan',
            'klsPeriodeId' => 'Periode Pelatihan',
            'pesertaNama' => 'Nama Peserta',
            'absenJdwlId'=>'Jadwal Pertemuan',
            'absenTgl'=>'Tanggal Pertemuan'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceBanks() {
        return $this->hasMany(InvoiceBank::className(), ['invoicePesertaId' => 'pesertaId']);
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
    public function getLatPesertaAbsens() {
        return $this->hasMany(LatPesertaAbsen::className(), ['absenPesertaId' => 'pesertaId']);
    }

}
