<?php

namespace app\modules\pembayaran\models;

use Yii;

/**
 * This is the model class for table "invoice_bank".
 *
 * @property integer $invoiceId
 * @property string $invoicePesertaId
 * @property string $invoiceNama
 * @property integer $invoiceJnsBiayaId
 * @property string $invoiceUraian
 * @property double $invoiceJumlah
 * @property string $invoiceBankId
 * @property string $invoiceBuktiBayar
 * @property string $invoiceTglBayar
 * @property string $invoiceTglReversal
 * @property string $invoiceFlag
 * @property string $invoiceTglBerlaku
 * @property string $invoiceCreate
 *
 * @property RefBank $invoiceBank
 * @property RefJenisBiaya $invoiceJnsBiaya
 * @property LatPeserta $invoicePeserta
 */
class InvoiceBank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoicePesertaId', 'invoiceNama', 'invoiceJnsBiayaId', 'invoiceUraian', 'invoiceJumlah', 'invoiceBankId', 'invoiceTglBerlaku', 'invoiceCreate'], 'required'],
            [['invoiceJnsBiayaId'], 'integer'],
            [['invoiceJumlah'], 'number'],
            [['invoiceTglBayar', 'invoiceTglReversal', 'invoiceTglBerlaku', 'invoiceCreate'], 'safe'],
            [['invoiceFlag'], 'string'],
            [['invoicePesertaId'], 'string', 'max' => 10],
            [['invoiceNama', 'invoiceUraian', 'invoiceBuktiBayar'], 'string', 'max' => 250],
            [['invoiceBankId'], 'string', 'max' => 15],
            [['invoiceBankId'], 'exist', 'skipOnError' => true, 'targetClass' => RefBank::className(), 'targetAttribute' => ['invoiceBankId' => 'bankId']],
            [['invoiceJnsBiayaId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenisBiaya::className(), 'targetAttribute' => ['invoiceJnsBiayaId' => 'jnsBiayaId']],
            [['invoicePesertaId'], 'exist', 'skipOnError' => true, 'targetClass' => LatPeserta::className(), 'targetAttribute' => ['invoicePesertaId' => 'pesertaId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceId' => 'Invoice ID',
            'invoicePesertaId' => 'Invoice Peserta ID',
            'invoiceNama' => 'Invoice Nama',
            'invoiceJnsBiayaId' => 'Invoice Jns Biaya ID',
            'invoiceUraian' => 'Invoice Uraian',
            'invoiceJumlah' => 'Invoice Jumlah',
            'invoiceBankId' => 'Invoice Bank ID',
            'invoiceBuktiBayar' => 'Invoice Bukti Bayar',
            'invoiceTglBayar' => 'Invoice Tgl Bayar',
            'invoiceTglReversal' => 'Invoice Tgl Reversal',
            'invoiceFlag' => 'Invoice Flag',
            'invoiceTglBerlaku' => 'Invoice Tgl Berlaku',
            'invoiceCreate' => 'Invoice Create',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceBank()
    {
        return $this->hasOne(RefBank::className(), ['bankId' => 'invoiceBankId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceJnsBiaya()
    {
        return $this->hasOne(RefJenisBiaya::className(), ['jnsBiayaId' => 'invoiceJnsBiayaId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicePeserta()
    {
        return $this->hasOne(LatPeserta::className(), ['pesertaId' => 'invoicePesertaId']);
    }
}
