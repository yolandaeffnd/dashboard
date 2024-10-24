<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "ref_tarif".
 *
 * @property integer $tarifId
 * @property integer $tarifJnslatId
 * @property integer $tarifJnsBiayaId
 * @property string $tarifBankId
 * @property double $tarifJumlah
 * @property string $tarifCreate
 * @property string $tarifUpdate
 *
 * @property LatPeriodeRuleTarif[] $latPeriodeRuleTarifs
 * @property LatPeriode[] $rulePeriodes
 * @property RefBank $tarifBank
 * @property RefJenisBiaya $tarifJnsBiaya
 * @property RefJenisPelatihan $tarifJnslat
 */
class RefTarif extends \yii\db\ActiveRecord {

    public $tarifNama;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_tarif';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tarifJnslatId', 'tarifJnsBiayaId', 'tarifBankId', 'tarifJumlah', 'tarifCreate'], 'required'],
                [['tarifJnslatId', 'tarifJnsBiayaId'], 'integer'],
                [['tarifJumlah'], 'number'],
                [['tarifCreate', 'tarifUpdate'], 'safe'],
                [['tarifBankId'], 'string', 'max' => 15],
                [['tarifBankId'], 'exist', 'skipOnError' => true, 'targetClass' => RefBank::className(), 'targetAttribute' => ['tarifBankId' => 'bankId']],
                [['tarifJnsBiayaId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisBiaya::className(), 'targetAttribute' => ['tarifJnsBiayaId' => 'jnsBiayaId']],
                [['tarifJnslatId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisPelatihan::className(), 'targetAttribute' => ['tarifJnslatId' => 'jnslatId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tarifId' => 'Tarif ID',
            'tarifJnslatId' => 'Tarif Jnslat ID',
            'tarifJnsBiayaId' => 'Tarif Jns Biaya ID',
            'tarifBankId' => 'Tarif Bank ID',
            'tarifJumlah' => 'Tarif Jumlah',
            'tarifCreate' => 'Tarif Create',
            'tarifUpdate' => 'Tarif Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPeriodeRuleTarifs() {
        return $this->hasMany(LatPeriodeRuleTarif::className(), ['ruleTarifId' => 'tarifId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRulePeriodes() {
        return $this->hasMany(LatPeriode::className(), ['periodeId' => 'rulePeriodeId'])->viaTable('lat_periode_rule_tarif', ['ruleTarifId' => 'tarifId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifBank() {
        return $this->hasOne(RefBank::className(), ['bankId' => 'tarifBankId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifJnsBiaya() {
        return $this->hasOne(RefJenisBiaya::className(), ['jnsBiayaId' => 'tarifJnsBiayaId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifJnslat() {
        return $this->hasOne(RefJenisPelatihan::className(), ['jnslatId' => 'tarifJnslatId']);
    }

}
