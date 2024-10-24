<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "lat_periode_rule_angkatan".
 *
 * @property integer $rulePeriodeId
 * @property string $ruleAllowAngkatan
 *
 * @property RefAngkatan $ruleAllowAngkatan0
 * @property LatPeriode $rulePeriode
 */
class LatPeriodeRuleAngkatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lat_periode_rule_angkatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rulePeriodeId', 'ruleAllowAngkatan'], 'required'],
            [['rulePeriodeId'], 'integer'],
            [['ruleAllowAngkatan'], 'string', 'max' => 4],
            [['ruleAllowAngkatan'], 'exist', 'skipOnError' => true, 'targetClass' => RefAngkatan::className(), 'targetAttribute' => ['ruleAllowAngkatan' => 'angkatan']],
            [['rulePeriodeId'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeriode::className(), 'targetAttribute' => ['rulePeriodeId' => 'periodeId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rulePeriodeId' => 'Rule Periode ID',
            'ruleAllowAngkatan' => 'Rule Allow Angkatan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleAllowAngkatan0()
    {
        return $this->hasOne(RefAngkatan::className(), ['angkatan' => 'ruleAllowAngkatan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriode()
    {
        return $this->hasOne(LatPeriode::className(), ['periodeId' => 'rulePeriodeId']);
    }
}
