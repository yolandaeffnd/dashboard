<?php

namespace app\modules\referensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referensi\models\RefJenisPelatihan;

/**
 * RefJenisPelatihanSearch represents the model behind the search form about `app\modules\referensi\models\RefJenisPelatihan`.
 */
class RefJenisPelatihanSearch extends RefJenisPelatihan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jnslatId'], 'integer'],
            [['jnslatNama', 'jnslatCreate', 'jnslatUpdate'], 'safe'],
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
        $query = RefJenisPelatihan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>20,
                'page'=> Yii::$app->request->get('page')-1,
                'params'=>[
                    'jnslat'=> $this->jnslatNama,
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
            'jnslatId' => $this->jnslatId,
            'jnslatCreate' => $this->jnslatCreate,
            'jnslatUpdate' => $this->jnslatUpdate,
        ]);

        $query->andFilterWhere(['like', 'jnslatNama', $this->jnslatNama]);

        return $dataProvider;
    }
}
