<?php

namespace app\modules\informasi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\informasi\models\Broadcast;

/**
 * BroadcastSearch represents the model behind the search form about `app\modules\informasi\models\Broadcast`.
 */
class BroadcastSearch extends Broadcast
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bcId'], 'integer'],
            [['bcTo', 'bcJudul', 'bcIsi', 'bcCreate'], 'safe'],
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
        $query = Broadcast::find()
                ->orderBy('bcCreate DESC');

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
            'bcId' => $this->bcId,
            'bcCreate' => $this->bcCreate,
        ]);

        $query->andFilterWhere(['like', 'bcTo', $this->bcTo])
            ->andFilterWhere(['like', 'bcJudul', $this->bcJudul])
            ->andFilterWhere(['like', 'bcIsi', $this->bcIsi]);

        return $dataProvider;
    }
}
