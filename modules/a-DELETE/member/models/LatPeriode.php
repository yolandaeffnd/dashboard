<?php

namespace app\modules\member\models;

use Yii;

/**
 * This is the model class for table "lat_periode".
 *
 * @property integer $periodeId
 * @property integer $periodeJnslatId
 * @property string $periodeNama
 * @property string $periodeRegAwal
 * @property string $periodeRegAkhir
 * @property string $periodeLakMulai
 * @property string $periodeLakSelesai
 * @property double $periodeMaxSkor
 * @property string $periodeIsAktif
 * @property string $periodeCreate
 * @property string $periodeUpdate
 *
 * @property LatKelas[] $latKelas
 * @property RefJenisPelatihan $periodeJnslat
 * @property LatPeriodeRuleAngkatan[] $latPeriodeRuleAngkatans
 * @property RefAngkatan[] $ruleAllowAngkatans
 * @property LatPeriodeRuleMemberKategori[] $latPeriodeRuleMemberKategoris
 * @property MemberKategori[] $ruleAllowMemberKats
 * @property LatPeriodeRulePeriode[] $latPeriodeRulePeriodes
 * @property LatPeriodeRulePeriode[] $latPeriodeRulePeriodes0
 * @property LatPeriode[] $rulePeriodes
 * @property LatPeriode[] $ruleNotAllowPeriodes
 * @property LatPeriodeRuleTarif[] $latPeriodeRuleTarifs
 * @property RefTarif[] $ruleTarifs
 */
class LatPeriode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lat_periode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periodeJnslatId', 'periodeNama', 'periodeRegAwal', 'periodeRegAkhir', 'periodeLakMulai', 'periodeLakSelesai', 'periodeMaxSkor', 'periodeCreate'], 'required'],
            [['periodeJnslatId'], 'integer'],
            [['periodeRegAwal', 'periodeRegAkhir', 'periodeLakMulai', 'periodeLakSelesai', 'periodeCreate', 'periodeUpdate'], 'safe'],
            [['periodeMaxSkor'], 'number'],
            [['periodeIsAktif'], 'string'],
            [['periodeNama'], 'string', 'max' => 250],
            [['periodeJnslatId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisPelatihan::className(), 'targetAttribute' => ['periodeJnslatId' => 'jnslatId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'periodeId' => 'Periode ID',
            'periodeJnslatId' => 'Periode Jnslat ID',
            'periodeNama' => 'Periode Nama',
            'periodeRegAwal' => 'Periode Reg Awal',
            'periodeRegAkhir' => 'Periode Reg Akhir',
            'periodeLakMulai' => 'Periode Lak Mulai',
            'periodeLakSelesai' => 'Periode Lak Selesai',
            'periodeMaxSkor' => 'Periode Max Skor',
            'periodeIsAktif' => 'Periode Is Aktif',
            'periodeCreate' => 'Periode Create',
            'periodeUpdate' => 'Periode Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatKelas()
    {
        return $this->hasMany(LatKelas::className(), ['klsPeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeJnslat()
    {
        return $this->hasOne(RefJenisPelatihan::className(), ['jnslatId' => 'periodeJnslatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRuleAngkatans()
    {
        return $this->hasMany(LatPeriodeRuleAngkatan::className(), ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleAllowAngkatans()
    {
        return $this->hasMany(RefAngkatan::className(), ['angkatan' => 'ruleAllowAngkatan'])->viaTable('lat_periode_rule_angkatan', ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRuleMemberKategoris()
    {
        return $this->hasMany(LatPeriodeRuleMemberKategori::className(), ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleAllowMemberKats()
    {
        return $this->hasMany(MemberKategori::className(), ['memberKatId' => 'ruleAllowMemberKatId'])->viaTable('lat_periode_rule_member_kategori', ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRulePeriodes()
    {
        return $this->hasMany(LatPeriodeRulePeriode::className(), ['ruleNotAllowPeriode' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRulePeriodes0()
    {
        return $this->hasMany(LatPeriodeRulePeriode::className(), ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriodes()
    {
        return $this->hasMany(LatPeriode::className(), ['periodeId' => 'rulePeriodeId'])->viaTable('lat_periode_rule_periode', ['ruleNotAllowPeriode' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleNotAllowPeriodes()
    {
        return $this->hasMany(LatPeriode::className(), ['periodeId' => 'ruleNotAllowPeriode'])->viaTable('lat_periode_rule_periode', ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRuleTarifs()
    {
        return $this->hasMany(LatPeriodeRuleTarif::className(), ['rulePeriodeId' => 'periodeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleTarifs()
    {
        return $this->hasMany(RefTarif::className(), ['tarifId' => 'ruleTarifId'])->viaTable('lat_periode_rule_tarif', ['rulePeriodeId' => 'periodeId']);
    }
}
