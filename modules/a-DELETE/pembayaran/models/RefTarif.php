<?php

namespace app\modules\pembayaran\models;

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
 * @property RefJenisBiaya $tarifJnsBiaya
 * @property RefJenisPelatihan $tarifJnslat
 */
class RefTarif extends \yii\db\ActiveRecord {

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
            //[['tarifJnslatId', 'tarifJnsBiayaId', 'tarifJumlah', 'tarifCreate'], 'required'],
                [['tarifJnslatId', 'tarifJnsBiayaId'], 'integer'],
                //[['tarifJumlah'], 'number'],
                [['tarifCreate', 'tarifUpdate', 'tarifJumlah', 'tarifJnslatId', 'tarifJnsBiayaId', 'tarifBankId'], 'safe'],
                [['tarifJnsBiayaId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisBiaya::className(), 'targetAttribute' => ['tarifJnsBiayaId' => 'jnsBiayaId']],
                [['tarifJnslatId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisPelatihan::className(), 'targetAttribute' => ['tarifJnslatId' => 'jnslatId']],
                [['tarifBankId'], 'exist', 'skipOnError' => true, 'targetClass' => RefBank::className(), 'targetAttribute' => ['tarifBankId' => 'bankId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tarifId' => 'Tarif ID',
            'tarifJnslatId' => 'Jenis Pelatihan',
            'tarifJnsBiayaId' => 'Jenis Biaya',
            'tarifBankId' => 'Bank Pembayaran',
            'tarifJumlah' => 'Jumlah',
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
    public function getTarifJnsBiaya() {
        return $this->hasOne(RefJenisBiaya::className(), ['jnsBiayaId' => 'tarifJnsBiayaId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifJnslat() {
        return $this->hasOne(RefJenisPelatihan::className(), ['jnslatId' => 'tarifJnslatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifBank() {
        return $this->hasOne(RefBank::className(), ['bankId' => 'tarifBankId']);
    }

}
