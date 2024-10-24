<?php

namespace app\modules\instrukturkelas\models;

use Yii;

/**
 * This is the model class for table "lat_kelas_instruktur".
 *
 * @property string $klsId
 * @property integer $instId
 *
 * @property RefInstruktur $inst
 * @property LatKelas $kls
 */
class LatKelasInstruktur extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lat_kelas_instruktur';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['klsId', 'instId'], 'required'],
            [['instId'], 'integer'],
            [['klsId'], 'string', 'max' => 20],
            [['instId'], 'exist', 'skipOnError' => true, 'targetClass' => RefInstruktur::className(), 'targetAttribute' => ['instId' => 'instId']],
            [['klsId'], 'exist', 'skipOnError' => true, 'targetClass' => LatKelas::className(), 'targetAttribute' => ['klsId' => 'klsId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'klsId' => 'Kls ID',
            'instId' => 'Inst ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInst()
    {
        return $this->hasOne(RefInstruktur::className(), ['instId' => 'instId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKls()
    {
        return $this->hasOne(LatKelas::className(), ['klsId' => 'klsId']);
    }
}
