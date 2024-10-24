<?php

namespace app\modules\pembayaran\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pembayaran\models\RefBank;

/**
 * RefBankSearch represents the model behind the search form about `app\modules\referensi\models\RefBank`.
 */
class RefBankSearch extends RefBank
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankId', 'bankNama', 'bankCreate', 'bankUpdate'], 'safe'],
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
        $query = RefBank::find();

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
            'bankCreate' => $this->bankCreate,
            'bankUpdate' => $this->bankUpdate,
        ]);

        $query->andFilterWhere(['like', 'bankId', $this->bankId])
            ->andFilterWhere(['like', 'bankNama', $this->bankNama]);

        return $dataProvider;
    }
}
