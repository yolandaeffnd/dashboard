<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "ref_instruktur".
 *
 * @property integer $instId
 * @property string $instNama
 * @property string $instJenkel
 * @property string $instTelp
 * @property string $instEmail
 * @property string $instIsAktif
 * @property string $instCreate
 * @property string $instUpdate
 *
 * @property LatKelas[] $latKelas
 */
class RefInstruktur extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_instruktur';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instNama', 'instJenkel', 'instTelp', 'instEmail', 'instCreate'], 'required'],
            [['instJenkel', 'instIsAktif'], 'string'],
            [['instCreate', 'instUpdate'], 'safe'],
            [['instNama'], 'string', 'max' => 200],
            [['instTelp'], 'string', 'max' => 100],
            [['instEmail'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'instId' => 'Inst ID',
            'instNama' => 'Inst Nama',
            'instJenkel' => 'Inst Jenkel',
            'instTelp' => 'Inst Telp',
            'instEmail' => 'Inst Email',
            'instIsAktif' => 'Inst Is Aktif',
            'instCreate' => 'Inst Create',
            'instUpdate' => 'Inst Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatKelas()
    {
        return $this->hasMany(LatKelas::className(), ['klsInstId' => 'instId']);
    }
}