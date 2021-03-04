<?php
/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/6/2
 * Time: 10:48
 */

namespace qingrui\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use qingrui\models\QingCase as QingCaseModel;
class QingCase extends QingCaseModel
{
    public $type_name;
    public $pid;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_title','case_status'], 'safe'],
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
        $query = QingCaseModel::find()
            ->from(QingCaseModel::tableName() . 'c');

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
        $query->andFilterWhere(['like', 'c.case_title', $this->case_title]);
        $query->andFilterWhere(['c.case_status'=>$this->case_status]);

        return $dataProvider;
    }
}