<?php

namespace app\modules\referensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referensi\models\RefAngkatan;

/**
 * RefAngkatanSearch represents the model behind the search form about `app\modules\referensi\models\RefAngkatan`.
 */
class RefAngkatanSearch extends RefAngkatan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['angkatan', 'angkatanNama'], 'safe'],
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
        $query = RefAngkatan::find()
                ->orderBy('angkatan DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>20,
                'page'=> Yii::$app->request->get('page')-1,
                'params'=>[
                    'angkatan'=> $this->angkatanNama,
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
        $query->andFilterWhere(['like', 'angkatan', $this->angkatan])
            ->andFilterWhere(['like', 'angkatanNama', $this->angkatanNama]);

        return $dataProvider;
    }
}
