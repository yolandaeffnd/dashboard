<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "ref_jenis_pelatihan".
 *
 * @property integer $jnslatId
 * @property string $jnslatNama
 * @property string $jnslatCreate
 * @property string $jnslatUpdate
 *
 * @property LatPeriode[] $latPeriodes
 * @property RefMateriPelatihan[] $refMateriPelatihans
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
            [['jnslatNama', 'jnslatCreate'], 'required'],
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
}
