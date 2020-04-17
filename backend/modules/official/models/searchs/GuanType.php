<?php

namespace official\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use official\models\GuanType as GuanTypeModel;

/**
 * GuanType represents the model behind the search form about [[\official\models\GuanType]].
 */
class GuanType extends GuanTypeModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'sort'], 'integer'],
            [['type_name'], 'safe'],
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
     * Searching GuanType
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = GuanTypeModel::find()
            ->from(GuanTypeModel::tableName() . ' t')
            ->joinWith(['guanTypeParent' => function ($q) {
                $q->from(GuanTypeModel::tableName() . ' parent');
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['guanTypeParent.type_name'] = [
            'asc' => ['parent.type_name' => SORT_ASC],
            'desc' => ['parent.type_name' => SORT_DESC],
            'label' => 'parent',
        ];
        $sort->attributes['t.pid'] = [
            'asc' => ['t.pid' => SORT_ASC],
            'desc' => ['t.pid' => SORT_DESC],
            'label' => 'pid',
        ];
        $sort->attributes['sort'] = [
            'asc' => ['parent.sort' => SORT_ASC, 't.sort' => SORT_ASC],
            'desc' => ['parent.sort' => SORT_DESC, 't.sort' => SORT_DESC],
            'label' => 'sort',
        ];
        $sort->defaultOrder = ['sort' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            't.id' => $this->id,
            't.pid' => $this->pid,
        ]);
        $query->andFilterWhere(['like', 'lower(t.type_name)', strtolower($this->type_name)]);

        return $dataProvider;
    }
}
