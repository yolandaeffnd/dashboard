<?php

namespace app\modules\referensi\models;

use Yii;

/**
 * This is the model class for table "ref_angkatan".
 *
 * @property string $angkatan
 * @property string $angkatanNama
 *
 * @property LatPeriodeRule[] $latPeriodeRules
 * @property LatPeriode[] $rulePeriodes
 */
class RefAngkatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_angkatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['angkatan', 'angkatanNama'], 'required'],
            [['angkatan', 'angkatanNama'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'angkatan' => 'Angkatan',
            'angkatanNama' => 'Angkatan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRules()
    {
        return $this->hasMany(LatPeriodeRule::className(), ['ruleAllowAngkatan' => 'angkatan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriodes()
    {
        return $this->hasMany(LatPeriode::className(), ['periodeId' => 'rulePeriodeId'])->viaTable('lat_periode_rule', ['ruleAllowAngkatan' => 'angkatan']);
    }
}
