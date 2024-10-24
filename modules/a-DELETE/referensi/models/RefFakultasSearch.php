<?php

namespace app\modules\referensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referensi\models\RefFakultas;

/**
 * RefFakultasSearch represents the model behind the search form about `app\modules\referensi\models\RefFakultas`.
 */
class RefFakultasSearch extends RefFakultas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fakId', 'fakNama', 'fakCreate', 'fakUpdate'], 'safe'],
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
        $query = RefFakultas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>20,
                'page'=> Yii::$app->request->get('page')-1,
                'params'=>[
                    'fak'=> $this->fakNama
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'fakCreate' => $this->fakCreate,
            'fakUpdate' => $this->fakUpdate,
        ]);

        $query->andFilterWhere(['like', 'fakId', $this->fakId])
            ->andFilterWhere(['like', 'fakNama', $this->fakNama]);

        return $dataProvider;
    }
}
