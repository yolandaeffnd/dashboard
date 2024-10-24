<?php

namespace app\modules\aplikasi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\aplikasi\models\AppMenu;

/**
 * AppMenuSearch represents the model behind the search form about `app\modules\aplikasi\models\AppMenu`.
 */
class AppMenuSearch extends AppMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idMenu', 'parentId', 'noUrut'], 'integer'],
            [['labelMenu', 'urlModule', 'controllerName', 'isAktif', 'isSubAction', 'iconMenu'], 'safe'],
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
        $query = AppMenu::find()
                ->select([
                    '*',
                    'CONCAT(if(parentId=0,idMenu,CONCAT(parentId,".",idMenu))) AS kode',
                    'if(parentId=0,idMenu,parentId) AS urut'
                ]);
                // ->select([
                //     '*',
                //     'CONCAT(if(parentId=0,idMenu,CONCAT(parentId,".",idMenu))) AS kode',
                //     'if(parentId=0,idMenu,parentId) AS urut'
                // ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query
                ->orderBy('urut ASC,idMenu'),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idMenu' => $this->idMenu,
            'parentId' => $this->parentId,
            'noUrut' => $this->noUrut,
        ]);

        $query->andFilterWhere(['like', 'labelMenu', $this->labelMenu])
            ->andFilterWhere(['like', 'urlModule', $this->urlModule])
            ->andFilterWhere(['like', 'controllerName', $this->controllerName])
            ->andFilterWhere(['like', 'isAktif', $this->isAktif])
            ->andFilterWhere(['like', 'isSubAction', $this->isSubAction])
            ->andFilterWhere(['like', 'iconMenu', $this->iconMenu]);

        return $dataProvider;
    }
}
