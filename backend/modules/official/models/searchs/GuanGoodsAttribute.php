<?php

namespace official\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use official\models\GuanGoodsAttribute as GuanGoodsAttributeModel;

class GuanGoodsAttribute extends GuanGoodsAttributeModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id','attribute'], 'safe'],
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
     * Searching navigation
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = GuanGoodsAttributeModel::find()
            ->from(GuanGoodsAttributeModel::tableName() . ' a');

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['cost'] = [
            'asc' => ['cost' => SORT_ASC],
            'desc' => ['cost' => SORT_DESC],
            'label' => 'cost',
        ];
        $sort->attributes['sort'] = [
            'asc' => ['sort' => SORT_ASC],
            'desc' => ['sort' => SORT_DESC],
            'label' => 'sort',
        ];
        $sort->defaultOrder = ['sort' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            't.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'attribute', $this->attribute]);
        $query->andFilterWhere(['goods_id'=>$this->goods_id]);

        return $dataProvider;
    }
}
