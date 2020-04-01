<?php

namespace content\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use content\models\StrictType as StrictTypeModel;

/**
 * StrictType represents the model behind the search form about [[\content\models\StrictType]].
 */
class StrictType extends StrictTypeModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'state', 'sort'], 'integer'],
            [['name'], 'safe'],
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
     * Searching StrictType
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = StrictTypeModel::find()
            ->from('t_strict_type');

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['name'] = [
            'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC],
            'label' => 'name',
        ];
        $sort->attributes['sort'] = [
            'asc' => ['sort' => SORT_ASC],
            'desc' => ['sort' => SORT_DESC],
            'label' => 'sort',
        ];$sort->attributes['state'] = [
            'asc' => ['state' => SORT_ASC],
            'desc' => ['state' => SORT_DESC],
            'label' => 'state',
        ];
        $sort->defaultOrder = ['sort' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            't.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'lower(t.name)', strtolower($this->name)]);

        return $dataProvider;
    }
}
