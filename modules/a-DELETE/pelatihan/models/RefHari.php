<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "ref_hari".
 *
 * @property string $hariKode
 * @property string $hariInd
 * @property integer $hariUrut
 *
 * @property LatJadwal[] $latJadwals
 */
class RefHari extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_hari';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hariKode', 'hariInd', 'hariUrut'], 'required'],
            [['hariUrut'], 'integer'],
            [['hariKode', 'hariInd'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hariKode' => 'Hari Kode',
            'hariInd' => 'Hari Ind',
            'hariUrut' => 'Hari Urut',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatJadwals()
    {
        return $this->hasMany(LatJadwal::className(), ['jdwlHariKode' => 'hariKode']);
    }
}
