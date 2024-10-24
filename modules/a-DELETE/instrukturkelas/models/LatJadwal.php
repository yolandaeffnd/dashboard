<?php

namespace app\modules\instrukturkelas\models;

use Yii;

/**
 * This is the model class for table "lat_jadwal".
 *
 * @property integer $jdwlId
 * @property string $jdwlKlsId
 * @property integer $jdwlRuangId
 * @property string $jdwlHariKode
 * @property string $jdwlJamMulai
 * @property string $jdwlJamSelesai
 * @property string $jdwlCreate
 * @property string $jdwlUpdate
 *
 * @property RefHari $jdwlHariKode0
 * @property LatKelas $jdwlKls
 * @property RefRuang $jdwlRuang
 */
class LatJadwal extends \yii\db\ActiveRecord {

    public $hariInd;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'lat_jadwal';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['jdwlKlsId', 'jdwlRuangId', 'jdwlHariKode', 'jdwlJamMulai', 'jdwlJamSelesai', 'jdwlCreate'], 'required'],
                [['jdwlRuangId'], 'integer'],
                [['jdwlJamMulai', 'jdwlJamSelesai', 'jdwlCreate', 'jdwlUpdate', 'jdwlHariKode'], 'safe'],
                [['jdwlKlsId'], 'string', 'max' => 20],
                [['jdwlHariKode'], 'exist', 'skipOnError' => true, 'targetClass' => RefHari::className(), 'targetAttribute' => ['jdwlHariKode' => 'hariKode']],
                [['jdwlKlsId'], 'exist', 'skipOnError' => true, 'targetClass' => LatKelas::className(), 'targetAttribute' => ['jdwlKlsId' => 'klsId']],
                [['jdwlRuangId'], 'exist', 'skipOnError' => true, 'targetClass' => RefRuang::className(), 'targetAttribute' => ['jdwlRuangId' => 'ruangId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'jdwlId' => 'Jdwl ID',
            'jdwlKlsId' => 'Kelas Pelatihan',
            'jdwlRuangId' => 'Ruang',
            'jdwlHariKode' => 'Hari',
            'jdwlJamMulai' => 'Jam Mulai',
            'jdwlJamSelesai' => 'Jam Selesai',
            'jdwlCreate' => 'Jdwl Create',
            'jdwlUpdate' => 'Jdwl Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdwlHariKode0() {
        return $this->hasOne(RefHari::className(), ['hariKode' => 'jdwlHariKode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdwlKls() {
        return $this->hasOne(LatKelas::className(), ['klsId' => 'jdwlKlsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdwlRuang() {
        return $this->hasOne(RefRuang::className(), ['ruangId' => 'jdwlRuangId']);
    }

}
