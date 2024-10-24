<?php

namespace app\modules\pembayaran\models;

use Yii;

/**
 * This is the model class for table "member_kategori".
 *
 * @property integer $memberKatId
 * @property string $memberKatNama
 * @property string $memberKatResource
 *
 * @property LatPeriodeRuleMemberKategori[] $latPeriodeRuleMemberKategoris
 * @property LatPeriode[] $rulePeriodes
 * @property Member[] $members
 */
class MemberKategori extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_kategori';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['memberKatId', 'memberKatNama', 'memberKatResource'], 'required'],
            [['memberKatId'], 'integer'],
            [['memberKatNama'], 'string', 'max' => 100],
            [['memberKatResource'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'memberKatId' => 'Member Kat ID',
            'memberKatNama' => 'Member Kat Nama',
            'memberKatResource' => 'Member Kat Resource',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRuleMemberKategoris()
    {
        return $this->hasMany(LatPeriodeRuleMemberKategori::className(), ['ruleAllowMemberKatId' => 'memberKatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriodes()
    {
        return $this->hasMany(LatPeriode::className(), ['periodeId' => 'rulePeriodeId'])->viaTable('lat_periode_rule_member_kategori', ['ruleAllowMemberKatId' => 'memberKatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['memberMemberKatId' => 'memberKatId']);
    }
}
