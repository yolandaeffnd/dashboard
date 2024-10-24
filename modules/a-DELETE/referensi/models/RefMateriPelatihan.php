<?php

namespace app\modules\referensi\models;

use Yii;

/**
 * This is the model class for table "ref_materi_pelatihan".
 *
 * @property integer $mapelId
 * @property integer $mapelJnslatId
 * @property string $mapelNama
 * @property string $mapelDeskripsi
 * @property string $mapelIsAktif
 * @property string $mapelCreate
 * @property string $mapelUpdate
 *
 * @property LatMateriKelas[] $latMateriKelas
 * @property RefJenisPelatihan $mapelJnslat
 */
class RefMateriPelatihan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_materi_pelatihan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mapelJnslatId', 'mapelNama', 'mapelDeskripsi', 'mapelCreate'], 'required'],
            [['mapelJnslatId'], 'integer'],
            [['mapelDeskripsi', 'mapelIsAktif'], 'string'],
            [['mapelCreate', 'mapelUpdate'], 'safe'],
            [['mapelNama'], 'string', 'max' => 250],
            [['mapelJnslatId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisPelatihan::className(), 'targetAttribute' => ['mapelJnslatId' => 'jnslatId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mapelId' => 'Mapel ID',
            'mapelJnslatId' => 'Jenis Pelatihan',
            'mapelNama' => 'Materi Pelatihan',
            'mapelDeskripsi' => 'Deskripsi Materi Pelatihan',
            'mapelIsAktif' => 'Status',
            'mapelCreate' => 'Mapel Create',
            'mapelUpdate' => 'Mapel Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatMateriKelas()
    {
        return $this->hasMany(LatMateriKelas::className(), ['mkMapelId' => 'mapelId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapelJnslat()
    {
        return $this->hasOne(RefJenisPelatihan::className(), ['jnslatId' => 'mapelJnslatId']);
    }
}
