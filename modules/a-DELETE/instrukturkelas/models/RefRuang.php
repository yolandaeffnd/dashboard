<?php

namespace app\modules\instrukturkelas\models;

use Yii;

/**
 * This is the model class for table "ref_ruang".
 *
 * @property integer $ruangId
 * @property string $ruangKode
 * @property string $ruangNama
 * @property integer $runagKapasitas
 * @property string $ruangCreate
 * @property string $ruangUpdate
 *
 * @property LatJadwal[] $latJadwals
 */
class RefRuang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_ruang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ruangKode', 'ruangNama', 'runagKapasitas', 'ruangCreate'], 'required'],
            [['runagKapasitas'], 'integer'],
            [['ruangCreate', 'ruangUpdate'], 'safe'],
            [['ruangKode'], 'string', 'max' => 20],
            [['ruangNama'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ruangId' => 'Ruang ID',
            'ruangKode' => 'Ruang Kode',
            'ruangNama' => 'Ruang Nama',
            'runagKapasitas' => 'Runag Kapasitas',
            'ruangCreate' => 'Ruang Create',
            'ruangUpdate' => 'Ruang Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatJadwals()
    {
        return $this->hasMany(LatJadwal::className(), ['jdwlRuangId' => 'ruangId']);
    }
}
