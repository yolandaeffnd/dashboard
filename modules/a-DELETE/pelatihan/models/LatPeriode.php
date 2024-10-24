<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "lat_periode".
 *
 * @property integer $periodeId
 * @property integer $periodeJnslatId
 * @property string $periodeNama
 * @property string $periodeRegAwal
 * @property string $periodeRegAkhir
 * @property string $periodeLakMulai
 * @property string $periodeLakSelesai
 * @property string $periodeMaxSkor
 * @property string $periodeIsAktif
 * @property string $periodeCreate
 * @property string $periodeUpdate
 *
 * @property LatKelas[] $latKelas
 * @property LatKelasPeriodeRule[] $latKelasPeriodeRules
 * @property RefJenisPelatihan $periodeJnslat
 */
class LatPeriode extends \yii\db\ActiveRecord {

    public $ruleAngkatan;
    public $ruleMemberKat;
    public $rulePeriode;
    public $ruleTarif;
    public $jnslatNama;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'lat_periode';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['periodeJnslatId', 'periodeNama', 'periodeRegAwal', 'periodeRegAkhir', 'periodeLakMulai', 'periodeLakSelesai', 'periodeCreate','periodeMaxSkor'], 'required'],
                [['periodeJnslatId', 'periodeNama', 'periodeRegAwal', 'periodeRegAkhir', 'periodeLakMulai', 'periodeLakSelesai', 'periodeCreate', 'periodeMaxSkor'], 'safe'],
                [['periodeJnslatId', 'periodeMaxSkor'], 'integer'],
                [['periodeRegAwal', 'periodeRegAkhir', 'periodeLakMulai', 'periodeLakSelesai', 'periodeCreate', 'periodeUpdate', 'ruleAngkatan', 'ruleMemberKat', 'rulePeriode', 'ruleTarif'], 'safe'],
                [['periodeIsAktif'], 'string'],
                [['periodeNama'], 'string', 'max' => 250],
                [['periodeJnslatId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisPelatihan::className(), 'targetAttribute' => ['periodeJnslatId' => 'jnslatId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'periodeId' => 'Periode ID',
            'periodeJnslatId' => 'Jenis Pelatihan',
            'periodeNama' => 'Periode',
            'periodeRegAwal' => 'Awal Registrasi Online',
            'periodeRegAkhir' => 'Akhir Registrasi Online',
            'periodeLakMulai' => 'Awal Periode',
            'periodeLakSelesai' => 'Akhir Periode',
            'periodeIsAktif' => 'Status',
            'periodeMaxSkor' => 'Skor Maksimal Yang Diizinkan',
            'periodeCreate' => 'Periode Create',
            'periodeUpdate' => 'Periode Update',
            'ruleAngkatan' => 'Untuk Angkatan',
            'ruleMemberKat' => 'Untuk Kategori Member',
            'rulePeriode' => 'Periode Tidak Diizinkan',
            'ruleTarif' => 'Biaya Pelatihan'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatKelas() {
        return $this->hasMany(LatKelas::className(), ['klsPeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatKelasPeriodeRules() {
        return $this->hasMany(LatKelasPeriodeRule::className(), ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeJnslat() {
        return $this->hasOne(RefJenisPelatihan::className(), ['jnslatId' => 'periodeJnslatId']);
    }

}
