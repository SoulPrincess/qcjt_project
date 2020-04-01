<?php

namespace content\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use content\models\CompanyType as CompanyTypeModel;

/**
 * CompanyType represents the model behind the search form about [[\content\models\CompanyType]].
 */
class CompanyType extends CompanyTypeModel
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
     * Searching CompanyType
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = CompanyTypeModel::find()
            ->from(CompanyTypeModel::tableName() . ' t')
            ->joinWith(['companyTypeParent' => function ($q) {
                $q->from(CompanyTypeModel::tableName() . ' parent');
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['companyTypeParent.type_name'] = [
            'asc' => ['parent.type_name' => SORT_ASC],
            'desc' => ['parent.type_name' => SORT_DESC],
            'label' => 'parent',
        ];
        $sort->attributes['sort'] = [
            'asc' => ['parent.sort' => SORT_ASC, 't.sort' => SORT_ASC],
            'desc' => ['parent.sort' => SORT_DESC, 't.sort' => SORT_DESC],
            'label' => 'sort',
        ];
        $sort->defaultOrder = ['companyTypeParent.type_name' => SORT_ASC];
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
