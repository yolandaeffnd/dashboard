<?php

namespace app\modules\pelatihan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pelatihan\models\LatKelas;

/**
 * LatKelasSearch represents the model behind the search form about `app\modules\pelatihan\models\LatKelas`.
 */
class LatKelasSearch extends LatKelas {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['klsId', 'klsNama', 'klsIsPublish', 'klsCreate', 'klsUpdate'], 'safe'],
                [['klsPeriodeId'], 'integer'],
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
        $query = LatKelas::find()
                ->join('JOIN', 'lat_periode', 'lat_periode.periodeId=lat_kelas.klsPeriodeId')
                ->where('periodeIsAktif="1"')
                ->orderBy('klsId DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>20,
                'page'=> Yii::$app->request->get('page')-1,
                'params'=>[
                    'kls'=> $this->klsNama,
                    'periode'=> $this->klsPeriodeId
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
            'klsPeriodeId' => $this->klsPeriodeId,
            'klsCreate' => $this->klsCreate,
            'klsUpdate' => $this->klsUpdate,
        ]);

        $query->andFilterWhere(['like', 'klsId', $this->klsId])
                ->andFilterWhere(['like', 'klsNama', $this->klsNama])
                ->andFilterWhere(['like', 'klsIsPublish', $this->klsIsPublish]);

        return $dataProvider;
    }

}
