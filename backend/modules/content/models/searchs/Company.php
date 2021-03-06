<?php

namespace content\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use content\models\Company as CompanyModel;
use content\models\CompanyType as CompanyTypeModel;
/**
 * Company represents the model behind the search form about [[\content\models\Company]].
 */
class Company extends CompanyModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name','company_allname'], 'safe'],
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
     * Searching Company
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = CompanyModel::find()
            ->from(CompanyModel::tableName() . ' t')
            ->joinWith(['companyType' => function ($q) {
                $q->from(CompanyTypeModel::tableName() . ' type');
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['t.state'] = [
            'asc' => ['t.state' => SORT_ASC],
            'desc' => ['t.state' => SORT_DESC],
            'label' => 'state',
        ];
        $sort->attributes['t.check'] = [
            'asc' => ['t.check' => SORT_ASC],
            'desc' => ['t.check' => SORT_DESC],
            'label' => 'check',
        ];
        $sort->defaultOrder = ['t.state' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            't.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 't.company_name', $this->company_name]);

        $query->andFilterWhere(['like', 't.company_allname', $this->company_allname]);

        return $dataProvider;
    }
}
