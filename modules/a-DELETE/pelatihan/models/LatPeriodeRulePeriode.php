<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "lat_periode_rule_periode".
 *
 * @property integer $rulePeriodeId
 * @property integer $ruleNotAllowPeriode
 *
 * @property LatPeriode $ruleNotAllowPeriode0
 * @property LatPeriode $rulePeriode
 */
class LatPeriodeRulePeriode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lat_periode_rule_periode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rulePeriodeId', 'ruleNotAllowPeriode'], 'required'],
            [['rulePeriodeId', 'ruleNotAllowPeriode'], 'integer'],
            [['ruleNotAllowPeriode'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeriode::className(), 'targetAttribute' => ['ruleNotAllowPeriode' => 'periodeId']],
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
            'ruleNotAllowPeriode' => 'Rule Not Allow Periode',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleNotAllowPeriode0()
    {
        return $this->hasOne(LatPeriode::className(), ['periodeId' => 'ruleNotAllowPeriode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriode()
    {
        return $this->hasOne(LatPeriode::className(), ['periodeId' => 'rulePeriodeId']);
    }
}
