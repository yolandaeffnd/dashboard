<?php

namespace app\modules\informasi\models;

use Yii;

/**
 * This is the model class for table "lat_kelas".
 *
 * @property string $klsId
 * @property integer $klsPeriodeId
 * @property string $klsNama
 * @property integer $klsKapasitas
 * @property integer $klsMeetingMin
 * @property integer $klsMeetingMax
 * @property string $klsIsPublish
 * @property string $klsCreate
 * @property string $klsUpdate
 *
 * @property LatJadwal[] $latJadwals
 * @property LatPeriode $klsPeriode
 * @property LatKelasInstruktur[] $latKelasInstrukturs
 * @property RefInstruktur[] $insts
 * @property LatPeserta[] $latPesertas
 */
class LatKelas extends \yii\db\ActiveRecord {

    public $periodeNama;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'lat_kelas';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['klsId', 'klsPeriodeId', 'klsNama', 'klsCreate'], 'required'],
                [['klsPeriodeId', 'klsKapasitas', 'klsMeetingMin', 'klsMeetingMax'], 'integer'],
                [['klsIsPublish'], 'string'],
                [['klsCreate', 'klsUpdate'], 'safe'],
                [['klsId'], 'string', 'max' => 20],
                [['klsNama'], 'string', 'max' => 250],
                [['klsPeriodeId'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeriode::className(), 'targetAttribute' => ['klsPeriodeId' => 'periodeId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'klsId' => 'Kls ID',
            'klsPeriodeId' => 'Kls Periode ID',
            'klsNama' => 'Kls Nama',
            'klsKapasitas' => 'Kls Kapasitas',
            'klsMeetingMin' => 'Kls Meeting Min',
            'klsMeetingMax' => 'Kls Meeting Max',
            'klsIsPublish' => 'Kls Is Publish',
            'klsCreate' => 'Kls Create',
            'klsUpdate' => 'Kls Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatJadwals() {
        return $this->hasMany(LatJadwal::className(), ['jdwlKlsId' => 'klsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKlsPeriode() {
        return $this->hasOne(LatPeriode::className(), ['periodeId' => 'klsPeriodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatKelasInstrukturs() {
        return $this->hasMany(LatKelasInstruktur::className(), ['klsId' => 'klsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInsts() {
        return $this->hasMany(RefInstruktur::className(), ['instId' => 'instId'])->viaTable('lat_kelas_instruktur', ['klsId' => 'klsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPesertas() {
        return $this->hasMany(LatPeserta::className(), ['pesertaKlsId' => 'klsId']);
    }

}
