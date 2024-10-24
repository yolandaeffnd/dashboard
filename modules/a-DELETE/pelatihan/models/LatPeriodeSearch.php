<?php

namespace app\modules\pelatihan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pelatihan\models\LatPeriode;

/**
 * LatPeriodeSearch represents the model behind the search form about `app\modules\pelatihan\models\LatPeriode`.
 */
class LatPeriodeSearch extends LatPeriode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periodeId', 'periodeJnslatId'], 'integer'],
            [['periodeNama', 'periodeRegAwal', 'periodeRegAkhir', 'periodeLakMulai', 'periodeLakSelesai', 'periodeCreate', 'periodeUpdate'], 'safe'],
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
        $query = LatPeriode::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>20,
                'page'=> Yii::$app->request->get('page')-1,
                'params'=>[
                    'periode'=> $this->periodeNama,
                    'pelatihan'=> $this->periodeJnslatId
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
            'periodeId' => $this->periodeId,
            'periodeJnslatId' => $this->periodeJnslatId,
            'periodeRegAwal' => $this->periodeRegAwal,
            'periodeRegAkhir' => $this->periodeRegAkhir,
            'periodeLakMulai' => $this->periodeLakMulai,
            'periodeLakSelesai' => $this->periodeLakSelesai,
            'periodeCreate' => $this->periodeCreate,
            'periodeUpdate' => $this->periodeUpdate,
        ]);

        $query->andFilterWhere(['like', 'periodeNama', $this->periodeNama]);

        return $dataProvider;
    }
}
