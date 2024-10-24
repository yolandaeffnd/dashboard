<?php

namespace app\modules\referensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referensi\models\RefProdi;

/**
 * RefProdiSearch represents the model behind the search form about `app\modules\referensi\models\RefProdi`.
 */
class RefProdiSearch extends RefProdi {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['prodiId', 'prodiNama', 'prodiFakId', 'prodiCreate', 'prodiUpdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = RefProdi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>20,
                'page'=> Yii::$app->request->get('page')-1,
                'params'=>[
                    'prodi'=> $this->prodiNama,
                    'fak'=> $this->prodiFakId
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
            'prodiCreate' => $this->prodiCreate,
            'prodiUpdate' => $this->prodiUpdate,
            'prodiFakId' => $this->prodiFakId,
        ]);

        $query->andFilterWhere(['like', 'prodiId', $this->prodiId])
                ->andFilterWhere(['like', 'prodiNama', $this->prodiNama]);
//            ->andFilterWhere(['like', 'prodiFakId', $this->prodiFakId]);

        return $dataProvider;
    }

}
