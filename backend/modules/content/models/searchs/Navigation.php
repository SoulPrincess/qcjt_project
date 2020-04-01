<?php

namespace content\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use content\models\Navigation as NavigationModel;

/**
 * Navigation represents the model behind the search form about [[\content\models\Navigation]].
 */
class Navigation extends NavigationModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'sort'], 'integer'],
            [['n_name', 'url'], 'safe'],
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
        $query = NavigationModel::find()
            ->from(NavigationModel::tableName() . ' t')
            ->joinWith(['navigationParent' => function ($q) {
                $q->from(NavigationModel::tableName() . ' parent');
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['navigationParent.n_name'] = [
            'asc' => ['parent.n_name' => SORT_ASC],
            'desc' => ['parent.n_name' => SORT_DESC],
            'label' => 'parent',
        ];
        $sort->attributes['sort'] = [
            'asc' => ['parent.sort' => SORT_ASC, 't.sort' => SORT_ASC],
            'desc' => ['parent.sort' => SORT_DESC, 't.sort' => SORT_DESC],
            'label' => 'sort',
        ];
        $sort->defaultOrder = ['navigationParent.n_name' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            't.id' => $this->id,
            't.pid' => $this->pid,
        ]);
        $query->andFilterWhere(['like', 'lower(t.n_name)', strtolower($this->n_name)]);

        return $dataProvider;
    }
}
