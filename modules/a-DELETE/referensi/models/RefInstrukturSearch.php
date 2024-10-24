<?php

namespace app\modules\referensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referensi\models\RefInstruktur;

/**
 * RefInstrukturSearch represents the model behind the search form about `app\modules\referensi\models\RefInstruktur`.
 */
class RefInstrukturSearch extends RefInstruktur {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['instId'], 'integer'],
                [['instNama', 'instJenkel', 'instTelp', 'instEmail', 'instIsAktif', 'instCreate', 'instUpdate'], 'safe'],
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
        $query = RefInstruktur::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => Yii::$app->request->get('page') - 1,
                'params' => [
                    'nama' => $this->instNama,
                    'email' => $this->instEmail,
                    'jenkel' => $this->instJenkel,
                    'telp' => $this->instTelp,
                    'aktif' => $this->instIsAktif
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
            'instId' => $this->instId,
            'instCreate' => $this->instCreate,
            'instUpdate' => $this->instUpdate,
        ]);

        $query->andFilterWhere(['like', 'instNama', $this->instNama])
                ->andFilterWhere(['like', 'instJenkel', $this->instJenkel])
                ->andFilterWhere(['like', 'instTelp', $this->instTelp])
                ->andFilterWhere(['like', 'instEmail', $this->instEmail])
                ->andFilterWhere(['like', 'instIsAktif', $this->instIsAktif]);

        return $dataProvider;
    }

}
