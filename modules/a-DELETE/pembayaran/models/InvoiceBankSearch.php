<?php

namespace app\modules\pembayaran\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pembayaran\models\InvoiceBank;

/**
 * InvoiceBankSearch represents the model behind the search form about `app\modules\pembayaran\models\InvoiceBank`.
 */
class InvoiceBankSearch extends InvoiceBank
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoiceId', 'invoiceJnsBiayaId'], 'integer'],
            [['invoicePesertaId', 'invoiceNama', 'invoiceUraian', 'invoiceBankId', 'invoiceBuktiBayar', 'invoiceTglBayar', 'invoiceTglReversal', 'invoiceFlag', 'invoiceTglBerlaku', 'invoiceCreate'], 'safe'],
            [['invoiceJumlah'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InvoiceBank::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'invoiceId' => $this->invoiceId,
            'invoiceJnsBiayaId' => $this->invoiceJnsBiayaId,
            'invoiceJumlah' => $this->invoiceJumlah,
            'invoiceTglBayar' => $this->invoiceTglBayar,
            'invoiceTglReversal' => $this->invoiceTglReversal,
            'invoiceTglBerlaku' => $this->invoiceTglBerlaku,
            'invoiceCreate' => $this->invoiceCreate,
        ]);

        $query->andFilterWhere(['like', 'invoicePesertaId', $this->invoicePesertaId])
            ->andFilterWhere(['like', 'invoiceNama', $this->invoiceNama])
            ->andFilterWhere(['like', 'invoiceUraian', $this->invoiceUraian])
            ->andFilterWhere(['like', 'invoiceBankId', $this->invoiceBankId])
            ->andFilterWhere(['like', 'invoiceBuktiBayar', $this->invoiceBuktiBayar])
            ->andFilterWhere(['like', 'invoiceFlag', $this->invoiceFlag]);

        return $dataProvider;
    }
}
