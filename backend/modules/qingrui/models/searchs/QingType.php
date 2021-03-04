<?php

namespace qingrui\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use qingrui\models\QingType as QingTypeModel;

class QingType extends QingTypeModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name'], 'string'],
            [['type_name','status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Searching QingType
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = QingTypeModel::find()
            ->from(QingTypeModel::tableName() . ' t');
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['sort'] = [
            'asc' => ['t.sort' => SORT_ASC],
            'desc' => ['t.sort' => SORT_DESC],
            'label' => 'sort',
        ];
        $sort->defaultOrder = ['sort' => SORT_ASC];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['t.id' => $this->id]);
        $query->andFilterWhere(['like', 'type_name', $this->type_name]);
        $query->andFilterWhere(['status'=>$this->status]);
        return $dataProvider;
    }
}
