<?php

namespace app\modules\maping\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\maping\models\Jalur;

/**
 * JalurSearch represents the model behind the search form about `app\modules\maping\models\Jalur`.
 */
class JalurSearch extends Jalur
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idJalur'], 'integer'],
            [['namaJalur'], 'safe'],
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
        $query = Jalur::find();

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
            'idJalur' => $this->idJalur,
        ]);

        $query->andFilterWhere(['like', 'namaJalur', $this->namaJalur]);

        return $dataProvider;
    }
}
