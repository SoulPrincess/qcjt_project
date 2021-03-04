<?php
/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/4/13
 * Time: 10:48
 */

namespace official\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use official\models\GuanGoods as GuanGoodsModel;
use official\models\GuanType as GuanTypeModel;
class GuanGoods extends GuanGoodsModel
{
    public $type_name;
    public $pid;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name','cover_img','status','pid'], 'safe'],
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
        $query = GuanGoodsModel::find()
            ->from(GuanGoodsModel::tableName() . ' g')
            ->joinWith(['guanType' => function ($q) {
                $q->from(GuanTypeModel::tableName() . ' type');
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $sort = $dataProvider->getSort();
        $sort->attributes['g.updated_at'] = [
            'asc' => ['g.updated_at' => SORT_ASC,],
            'desc' => ['g.updated_at' => SORT_DESC],
            'label' => 'updated_at',
        ];

        $sort->defaultOrder = ['g.updated_at' => SORT_DESC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'g.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'type.type_name', $this->type_name]);
        $query->andFilterWhere(['g.status'=>$this->status]);
        $query->andFilterWhere(['type.pid'=>$this->pid]);

        return $dataProvider;
    }
}