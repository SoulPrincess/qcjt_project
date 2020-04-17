<?php
/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/4/15
 * Time: 10:48
 */

namespace official\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use official\models\GuanProduct as GuanProductModel;
use official\models\GuanProductType as GuanProductTypeModel;
class GuanProduct extends GuanProductModel
{
    public $type_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
     * Searching GuanProduct
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = GuanProductModel::find()
            ->from(GuanProductModel::tableName() . ' p')
            ->joinWith(['guanProductType' => function ($q) {
                $q->from(GuanProductTypeModel::tableName() . ' t');
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['p.sort'] = [
            'asc' => ['p.sort' => SORT_ASC],
            'desc' => ['p.sort' => SORT_DESC],
            'label' => 'sort',
        ];
        $sort->defaultOrder = ['p.sort' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['p.id' => $this->id]);
        $query->andFilterWhere(['like', 'p.pro_name', $this->pro_name]);
        $query->andFilterWhere(['like', 't.type_name', $this->type_name]);

        return $dataProvider;
    }
}