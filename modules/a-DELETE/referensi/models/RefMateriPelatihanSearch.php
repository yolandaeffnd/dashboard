<?php

namespace app\modules\referensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referensi\models\RefMateriPelatihan;

/**
 * MateriPelatihanSearch represents the model behind the search form about `app\modules\referensi\models\RefMateriPelatihan`.
 */
class RefMateriPelatihanSearch extends RefMateriPelatihan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mapelId', 'mapelJnslatId'], 'integer'],
            [['mapelNama', 'mapelDeskripsi', 'mapelIsAktif', 'mapelCreate', 'mapelUpdate'], 'safe'],
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
        $query = RefMateriPelatihan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>20,
                'page'=> Yii::$app->request->get('page')-1,
                'params'=>[
                    'mapel'=> $this->mapelNama,
                    'jnslat'=> $this->mapelJnslatId
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
            'mapelId' => $this->mapelId,
            'mapelJnslatId' => $this->mapelJnslatId,
            'mapelCreate' => $this->mapelCreate,
            'mapelUpdate' => $this->mapelUpdate,
        ]);

        $query->andFilterWhere(['like', 'mapelNama', $this->mapelNama])
            ->andFilterWhere(['like', 'mapelDeskripsi', $this->mapelDeskripsi])
            ->andFilterWhere(['like', 'mapelIsAktif', $this->mapelIsAktif]);

        return $dataProvider;
    }
}
