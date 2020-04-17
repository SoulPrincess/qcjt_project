<?php

namespace content\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use content\models\Blogroll as BlogrollModel;
/**
 * Blogroll represents the model behind the search form about [[\content\models\Blogroll]].
 */
class Blogroll extends BlogrollModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bl_name'], 'safe'],
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
     * Searching Blogroll
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = BlogrollModel::find()
            ->from(BlogrollModel::tableName());

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['bl_sort'] = [
            'asc' => ['bl_sort' => SORT_ASC],
            'desc' => ['bl_sort' => SORT_DESC],
            'label' => 'bl_sort',
        ];
        $sort->defaultOrder = ['bl_sort' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            't.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 't.bl_name', $this->bl_name]);

        return $dataProvider;
    }
}
