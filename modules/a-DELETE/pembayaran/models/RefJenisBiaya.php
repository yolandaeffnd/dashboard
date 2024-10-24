<?php

namespace app\modules\pembayaran\models;

use Yii;

/**
 * This is the model class for table "ref_jenis_biaya".
 *
 * @property integer $jnsBiayaId
 * @property string $jnsBiayaNama
 * @property string $jnsBiayaCreate
 * @property string $jnsBiayaUpdate
 *
 * @property InvoiceBank[] $invoiceBanks
 */
class RefJenisBiaya extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_jenis_biaya';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jnsBiayaNama', 'jnsBiayaCreate'], 'required'],
            [['jnsBiayaCreate', 'jnsBiayaUpdate'], 'safe'],
            [['jnsBiayaNama'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jnsBiayaId' => 'Jns Biaya ID',
            'jnsBiayaNama' => 'Jenis Biaya',
            'jnsBiayaCreate' => 'Jns Biaya Create',
            'jnsBiayaUpdate' => 'Jns Biaya Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceBanks()
    {
        return $this->hasMany(InvoiceBank::className(), ['invoiceJnsBiayaId' => 'jnsBiayaId']);
    }
}
