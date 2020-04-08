<?php

namespace api\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class CompanyType extends PublicModel
{

    /**
     * 严选类别及公司
     * @time:2020/04/07
     * @author:lhp
     */
    public function strictType()
    {
        $data=(new Query())
            ->select(['s.id','substring_index(name,"严选服务商",1) as name'])
            ->from(['s'=>$this->STRICT_TABLE])
            ->where(['s.state'=>1])
            ->orderBy('s.sort asc')
            ->all();
        $company=(new Query())
            ->select(['c.id','company_name','company_allname','strict_id'])
            ->from(['c'=>$this->COMPANY_TABLE])
            ->where(['c.check'=>2,'c.state'=>1])
            ->orderBy('c.created_at asc')
            ->all();
        $data_list = ArrayHelper::map($data, 'id', 'name');
        foreach($company as $k=>$v){
            if($data_list[$v['strict_id']]){
                $datas[$v['strict_id']]['strict_name']=$data_list[$v['strict_id']];
                $datas[$v['strict_id']]['strict_id']=$v['strict_id'];
                $datas[$v['strict_id']]['child'][]=$v;
            }
        }
        return array_values($datas);
    }
}
