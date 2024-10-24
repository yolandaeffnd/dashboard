<?php

namespace app\modules\pelatihan\models;

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
 * @property LatKelasPeriodeRule[] $latKelasPeriodeRules
 * @property LatPeserta[] $latPesertas
 */
class LatKelas extends \yii\db\ActiveRecord
{
    public $klsInstId;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lat_kelas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['klsPeriodeId', 'klsNama', 'klsCreate'], 'required'],
            [['klsPeriodeId', 'klsKapasitas', 'klsMeetingMin', 'klsMeetingMax'], 'integer'],
            [['klsIsPublish'], 'string'],
            [['klsCreate', 'klsUpdate','klsId','klsInstId'], 'safe'],
            [['klsId'], 'string', 'max' => 20],
            [['klsNama'], 'string', 'max' => 250],
            [['klsPeriodeId'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeriode::className(), 'targetAttribute' => ['klsPeriodeId' => 'periodeId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'klsId' => 'Kls ID',
            'klsPeriodeId' => 'Periode Pelatihan',
            'klsNama' => 'Nama Kelas',
            'klsKapasitas' => 'Kapasitas',
            'klsMeetingMin' => 'Pertemuan Min.',
            'klsMeetingMax' => 'Pertemuan Maks.',
            'klsIsPublish' => 'Publish?',
            'klsCreate' => 'Kls Create',
            'klsUpdate' => 'Kls Update',
            'klsInstId'=>'Instruktur'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatJadwals()
    {
        return $this->hasMany(LatJadwal::className(), ['jdwlKlsId' => 'klsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKlsPeriode()
    {
        return $this->hasOne(LatPeriode::className(), ['periodeId' => 'klsPeriodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatKelasPeriodeRules()
    {
        return $this->hasMany(LatKelasPeriodeRule::className(), ['ruleKlsId' => 'klsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPesertas()
    {
        return $this->hasMany(LatPeserta::className(), ['pesertaKlsId' => 'klsId']);
    }
}
