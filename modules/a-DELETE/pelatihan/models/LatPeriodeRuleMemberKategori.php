<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "lat_periode_rule_member_kategori".
 *
 * @property integer $rulePeriodeId
 * @property integer $ruleAllowMemberKatId
 *
 * @property MemberKategori $ruleAllowMemberKat
 * @property LatPeriode $rulePeriode
 */
class LatPeriodeRuleMemberKategori extends \yii\db\ActiveRecord {

    public $memberKatNama;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'lat_periode_rule_member_kategori';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['rulePeriodeId', 'ruleAllowMemberKatId'], 'required'],
                [['rulePeriodeId', 'ruleAllowMemberKatId'], 'integer'],
                [['ruleAllowMemberKatId'], 'exist', 'skipOnError' => true, 'targetClass' => MemberKategori::className(), 'targetAttribute' => ['ruleAllowMemberKatId' => 'memberKatId']],
                [['rulePeriodeId'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeriode::className(), 'targetAttribute' => ['rulePeriodeId' => 'periodeId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rulePeriodeId' => 'Rule Periode ID',
            'ruleAllowMemberKatId' => 'Rule Allow Member Kat ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleAllowMemberKat() {
        return $this->hasOne(MemberKategori::className(), ['memberKatId' => 'ruleAllowMemberKatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriode() {
        return $this->hasOne(LatPeriode::className(), ['periodeId' => 'rulePeriodeId']);
    }

}
