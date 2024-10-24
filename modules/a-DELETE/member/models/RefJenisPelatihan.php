<?php

namespace app\modules\member\models;

use Yii;

/**
 * This is the model class for table "ref_jenis_pelatihan".
 *
 * @property integer $jnslatId
 * @property string $jnslatNama
 * @property string $jnslatDeskripsi
 * @property string $jnslatCreate
 * @property string $jnslatUpdate
 *
 * @property LatPeriode[] $latPeriodes
 * @property RefMateriPelatihan[] $refMateriPelatihans
 * @property RefTarif[] $refTarifs
 */
class RefJenisPelatihan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_jenis_pelatihan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jnslatNama', 'jnslatDeskripsi', 'jnslatCreate'], 'required'],
            [['jnslatDeskripsi'], 'string'],
            [['jnslatCreate', 'jnslatUpdate'], 'safe'],
            [['jnslatNama'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jnslatId' => 'Jnslat ID',
            'jnslatNama' => 'Jnslat Nama',
            'jnslatDeskripsi' => 'Jnslat Deskripsi',
            'jnslatCreate' => 'Jnslat Create',
            'jnslatUpdate' => 'Jnslat Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodes()
    {
        return $this->hasMany(LatPeriode::className(), ['periodeJnslatId' => 'jnslatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefMateriPelatihans()
    {
        return $this->hasMany(RefMateriPelatihan::className(), ['mapelJnslatId' => 'jnslatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefTarifs()
    {
        return $this->hasMany(RefTarif::className(), ['tarifJnslatId' => 'jnslatId']);
    }
}
