<?php

namespace official\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use official\models\GuanProductType as GuanProductTypeModel;

class GuanProductType extends GuanProductTypeModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort'], 'integer'],
            [['type_name','intro'], 'safe'],
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
     * Searching GuanProductType
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = GuanProductTypeModel::find()
            ->from(GuanProductTypeModel::tableName() . ' t');

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['type_name'] = [
            'asc' => ['type_name' => SORT_ASC],
            'desc' => ['type_name' => SORT_DESC],
            'label' => 'type_name',
        ];
        $sort->attributes['sort'] = [
            'asc' => ['t.sort' => SORT_ASC],
            'desc' => ['t.sort' => SORT_DESC],
            'label' => 'sort',
        ];
        $sort->defaultOrder = ['sort' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            't.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'lower(t.type_name)', strtolower($this->type_name)]);

        return $dataProvider;
    }
}
