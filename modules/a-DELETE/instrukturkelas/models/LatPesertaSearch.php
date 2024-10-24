<?php

namespace app\modules\instrukturkelas\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\instrukturkelas\models\LatPeserta;
use app\modules\instrukturkelas\models\LatKelas;
use app\modules\instrukturkelas\models\RefInstruktur;


/**
 * LatPesertaSearch represents the model behind the search form about `app\modules\pelatihan\models\LatPeserta`.
 */
class LatPesertaSearch extends LatPeserta {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['pesertaId', 'pesertaKlsId', 'klsNama','klsPeriodeId', 'pesertaCreate', 'pesertaUpdate'], 'safe'],
                [['pesertaMemberId'], 'integer'],
                [['pesertaSkorTerakhirTest'], 'number'],
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
        $query = LatPeserta::find();

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
            'pesertaMemberId' => $this->pesertaMemberId,
            'pesertaSkorTerakhirTest' => $this->pesertaSkorTerakhirTest,
            'pesertaCreate' => $this->pesertaCreate,
            'pesertaUpdate' => $this->pesertaUpdate,
        ]);

        $query->andFilterWhere(['like', 'pesertaId', $this->pesertaId])
                ->andFilterWhere(['like', 'pesertaKlsId', $this->pesertaKlsId]);

        return $dataProvider;
    }

    public function searchKelas($params) {
        $instruktur = RefInstruktur::find()
                ->where('instEmail=:email', [
                    ':email'=> \Yii::$app->user->identity->userUsername
                ])->one();
        $query = LatKelas::find()
                ->join('JOIN', 'lat_kelas_instruktur', 'lat_kelas_instruktur.klsId=lat_kelas.klsId')
                ->where('lat_kelas_instruktur.instId=:instruktur',[
                    ':instruktur'=>$instruktur->instId
                ])
                ->orderBy('klsId DESC');
        //Cek Apakah ada peserta dikelas tersebut
        $peserta = LatPeserta::find()
                ->groupBy('pesertaKlsId');
        $arr = [];
        foreach ($peserta->each() as $val) {
            $arr[] = $val['pesertaKlsId'];
        }
        $query->andWhere(['IN', 'lat_kelas.klsId', $arr]);

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
        ]);

        $query->andFilterWhere(['like', 'klsNama', $this->klsNama]);

        return $dataProvider;
    }

}