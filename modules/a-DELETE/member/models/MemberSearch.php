<?php

namespace app\modules\member\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\member\models\Member;

/**
 * MemberSearch represents the model behind the search form about `app\modules\member\models\Member`.
 */
class MemberSearch extends Member {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['memberId', 'memberGroupId'], 'integer'],
                [['memberNama', 'memberMemberKatId', 'memberJenkel', 'memberTglLahir', 'memberTmpLahir', 'memberEmail', 'memberTelp', 'memberFoto', 'memberIsMhsUnand', 'memberMhsAngkatan', 'memberMhsNim', 'memberMhsProdiId', 'memberMhsFakId', 'memberPassword', 'memberIsAktif', 'memberCreate', 'memberUpdate'], 'safe'],
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
        $query = Member::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => Yii::$app->request->get('page') - 1,
                'params' => [
                    'id'=> $this->memberId,
                    'nama' => $this->memberNama,
                    'email' => $this->memberEmail,
                    'kat' => $this->memberMemberKatId,
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
            'memberId' => $this->memberId,
            'memberTglLahir' => $this->memberTglLahir,
            'memberGroupId' => $this->memberGroupId,
            'memberCreate' => $this->memberCreate,
            'memberMemberKatId' => $this->memberMemberKatId
        ]);

        $query->andFilterWhere(['like', 'memberNama', $this->memberNama])
                ->andFilterWhere(['like', 'memberJenkel', $this->memberJenkel])
                ->andFilterWhere(['like', 'memberTmpLahir', $this->memberTmpLahir])
                ->andFilterWhere(['like', 'memberEmail', $this->memberEmail])
                ->andFilterWhere(['like', 'memberTelp', $this->memberTelp])
                ->andFilterWhere(['like', 'memberMhsAngkatan', $this->memberMhsAngkatan])
                ->andFilterWhere(['like', 'memberMhsNim', $this->memberMhsNim])
                ->andFilterWhere(['like', 'memberMhsProdiId', $this->memberMhsProdiId])
                ->andFilterWhere(['like', 'memberMhsFakId', $this->memberMhsFakId])
                ->andFilterWhere(['like', 'memberPassword', $this->memberPassword])
                ->andFilterWhere(['like', 'memberIsAktif', $this->memberIsAktif]);

        return $dataProvider;
    }

}
