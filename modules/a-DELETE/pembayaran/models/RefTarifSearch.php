<?php

namespace app\modules\pembayaran\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pembayaran\models\RefTarif;

/**
 * RefTarifSearch represents the model behind the search form about `app\modules\pembayaran\models\RefTarif`.
 */
class RefTarifSearch extends RefTarif
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarifId', 'tarifJnslatId', 'tarifJnsBiayaId'], 'integer'],
            [['tarifJumlah'], 'number'],
            [['tarifCreate', 'tarifUpdate'], 'safe'],
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
        $query = RefTarif::find();

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
            'tarifId' => $this->tarifId,
            'tarifJnslatId' => $this->tarifJnslatId,
            'tarifJnsBiayaId' => $this->tarifJnsBiayaId,
            'tarifJumlah' => $this->tarifJumlah,
            'tarifCreate' => $this->tarifCreate,
            'tarifUpdate' => $this->tarifUpdate,
        ]);

        return $dataProvider;
    }
}
