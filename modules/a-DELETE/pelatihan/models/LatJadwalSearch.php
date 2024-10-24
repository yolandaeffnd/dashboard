<?php

namespace app\modules\pelatihan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pelatihan\models\LatJadwal;

/**
 * LatJadwalSearch represents the model behind the search form about `app\modules\pelatihan\models\LatJadwal`.
 */
class LatJadwalSearch extends LatJadwal {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['jdwlId', 'jdwlRuangId'], 'integer'],
                [['jdwlKlsId', 'jdwlHariKode', 'jdwlJamMulai', 'jdwlJamSelesai', 'jdwlCreate', 'jdwlUpdate'], 'safe'],
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
        $query = LatJadwal::find()
                ->join('JOIN', 'lat_kelas', 'lat_kelas.klsId=lat_jadwal.jdwlKlsId')
                ->join('JOIN', 'lat_periode', 'lat_periode.periodeId=lat_kelas.klsPeriodeId')
                ->where('periodeIsAktif="1"');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => Yii::$app->request->get('page') - 1,
                'params' => [
                    'kls' => $this->jdwlKlsId,
                    'ruang' => $this->jdwlRuangId,
                    'hari' => $this->jdwlHariKode
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'jdwlId' => $this->jdwlId,
            'jdwlRuangId' => $this->jdwlRuangId,
            'jdwlJamMulai' => $this->jdwlJamMulai,
            'jdwlJamSelesai' => $this->jdwlJamSelesai,
            'jdwlCreate' => $this->jdwlCreate,
            'jdwlUpdate' => $this->jdwlUpdate,
        ]);

        $query->andFilterWhere(['like', 'jdwlKlsId', $this->jdwlKlsId])
                ->andFilterWhere(['like', 'jdwlHariKode', $this->jdwlHariKode]);

        return $dataProvider;
    }

}
