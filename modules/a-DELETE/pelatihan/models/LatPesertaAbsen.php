<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "lat_peserta_absen".
 *
 * @property string $absenPesertaId
 * @property integer $absenJdwlId
 * @property string $absenTgl
 * @property string $absenIsHadir
 * @property string $absenCreate
 *
 * @property LatJadwal $absenJdwl
 * @property LatPeserta $absenPeserta
 */
class LatPesertaAbsen extends \yii\db\ActiveRecord {
    
    public $absenFile;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'lat_peserta_absen';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['absenJdwlId', 'absenTgl', 'absenIsHadir', 'absenCreate'], 'required'],
                [['absenJdwlId'], 'integer'],
                [['absenTgl', 'absenCreate', 'absenPesertaId'], 'safe'],
                [['absenFile'],'file','extensions' => 'xls,xlsx','maxSize'=>1024000],
                [['absenIsHadir'], 'string'],
                [['absenPesertaId'], 'string', 'max' => 10],
                [['absenJdwlId'], 'exist', 'skipOnError' => true, 'targetClass' => LatJadwal::className(), 'targetAttribute' => ['absenJdwlId' => 'jdwlId']],
                [['absenPesertaId'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeserta::className(), 'targetAttribute' => ['absenPesertaId' => 'pesertaId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'absenPesertaId' => 'Nomor Peserta',
            'absenJdwlId' => 'Hari Pertemuan',
            'absenTgl' => 'Tanggal Pertemuan',
            'absenIsHadir' => 'Keterangan',
            'absenCreate' => 'Absen Create',
            'absenFile'=>'Import Absensi'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbsenJdwl() {
        return $this->hasOne(LatJadwal::className(), ['jdwlId' => 'absenJdwlId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbsenPeserta() {
        return $this->hasOne(LatPeserta::className(), ['pesertaId' => 'absenPesertaId']);
    }

}
