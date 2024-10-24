<?php

namespace app\modules\pembayaran\models;

use Yii;

/**
 * This is the model class for table "ref_bank".
 *
 * @property string $bankId
 * @property string $bankNama
 * @property string $bankCreate
 * @property string $bankUpdate
 *
 * @property InvoiceBank[] $invoiceBanks
 */
class RefBank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankId', 'bankNama', 'bankCreate'], 'required'],
            [['bankCreate', 'bankUpdate'], 'safe'],
            [['bankId'], 'string', 'max' => 15],
            [['bankNama'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bankId' => 'ID Bank',
            'bankNama' => 'Nama Bank',
            'bankCreate' => 'Bank Create',
            'bankUpdate' => 'Bank Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceBanks()
    {
        return $this->hasMany(InvoiceBank::className(), ['invoiceBankId' => 'bankId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifBanks()
    {
        return $this->hasMany(RefTarif::className(), ['tarifBankId' => 'bankId']);
    }
}
