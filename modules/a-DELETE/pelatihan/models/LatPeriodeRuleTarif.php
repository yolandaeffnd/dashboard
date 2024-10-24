<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "lat_periode_rule_tarif".
 *
 * @property integer $rulePeriodeId
 * @property integer $ruleTarifId
 *
 * @property LatPeriode $rulePeriode
 * @property RefTarif $ruleTarif
 */
class LatPeriodeRuleTarif extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lat_periode_rule_tarif';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rulePeriodeId', 'ruleTarifId'], 'required'],
            [['rulePeriodeId', 'ruleTarifId'], 'integer'],
            [['rulePeriodeId'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeriode::className(), 'targetAttribute' => ['rulePeriodeId' => 'periodeId']],
            [['ruleTarifId'], 'exist', 'skipOnError' => true, 'targetClass' => RefTarif::className(), 'targetAttribute' => ['ruleTarifId' => 'tarifId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rulePeriodeId' => 'Rule Periode ID',
            'ruleTarifId' => 'Rule Tarif ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriode()
    {
        return $this->hasOne(LatPeriode::className(), ['periodeId' => 'rulePeriodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleTarif()
    {
        return $this->hasOne(RefTarif::className(), ['tarifId' => 'ruleTarifId']);
    }
}
