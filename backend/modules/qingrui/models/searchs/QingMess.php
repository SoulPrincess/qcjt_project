<?php
/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/6/2
 * Time: 15:43
 */

namespace qingrui\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use qingrui\models\QingMess as QingMessModel;
use qingrui\models\QingType as QingTypeModel;
class QingMess extends QingMessModel
{
    public $type_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','little_title','status','type_id','type_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = QingMessModel::find()
            ->from(['c'=>QingMessModel::tableName()])
            ->leftJoin(['type'=>QingTypeModel::tableName()],'type.id=c.type_id')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $sort = $dataProvider->getSort();
        $sort->attributes['c.updated_at'] = [
            'asc' => ['c.updated_at' => SORT_ASC,],
            'desc' => ['c.updated_at' => SORT_DESC],
            'label' => 'updated_at',
        ];

        $sort->defaultOrder = ['c.updated_at' => SORT_DESC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'c.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'c.title', $this->title]);
        $query->andFilterWhere(['c.status'=>$this->status]);
        $query->andFilterWhere(['c.type_id'=>$this->type_id]);

        return $dataProvider;
    }
}