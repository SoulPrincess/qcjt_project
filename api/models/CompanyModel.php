<?php
namespace api\models;
use yii\db\Query;
use yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/3/26
 * Time: 11:17
 */

class CompanyModel extends PublicModel {
    /*
    * 企业详情信息
    * @author:lhp
    * @time:2020-3-26
    * */
    public function getCompanyDetail($where=[]){
        $query=new Query();
        $query->select(['c.id','company_name','company_allname','company_logo','pro_describe','company_describe','linkman','phone','post','service_charge','t.type_name','t.id type_id','c.check','c.company_url'])
            ->from(['c'=>$this->COMPANY_TABLE])
            ->leftJoin(['t'=>$this->COMPANY_TYPE_TABLE],'t.id=c.type_id')
            ->where(['c.state'=>'1','c.check'=>1]);

        $config_data=$query->all();
        return $config_data;
    }

    /*
  * 企业详情信息
  * $where array
  * @author:lhp
  * @time:2020-3-26
  * */
    public function getCompanyOneDetail($where=[]){
        $query=new Query();
        $query->select(['c.id','company_name','company_allname','company_logo','pro_describe','company_describe','linkman','phone','post','service_charge','t.type_name','t.id type_id','c.company_pdf'])
            ->from(['c'=>$this->COMPANY_TABLE])
            ->leftJoin(['t'=>$this->COMPANY_TYPE_TABLE],'t.id=c.type_id')
            ->where(['c.state'=>'1']);
        if($where){
            $query->andWhere($where);
        }
            $config_data=$query->one();

        return $config_data;
    }

    /*企业入驻
    *$param array
    *@time:2020-3-26
    *@author:Lhp
    */
    public function addCompany($param=[]){
        $connection=Yii::$app->db;
        $transaction=$connection->beginTransaction();
        try{
            $param['created_at']=time();
            $param['updated_at']=time();
            $connection->createCommand()->insert($this->COMPANY_TABLE,$param)->execute();
            $transaction->commit();
            return true;
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /*所属企业类型*/
    public function getAllType(){
        $config_data=(new Query())
            ->select(['type_name','id','pid','sort'])
            ->from([$this->COMPANY_TYPE_TABLE])
            ->all();
        $data= $this->recursion($config_data,0);
        return $data;
    }

    /*
    * 企业详情信息
    * $where array
    * @author:lhp
    * @time:2020-4-9
    * */
    public function getCompanysSearch($params=[]){

        $query = new Query();
        if (!empty($params['page'])) {
            $this->defaultPage = $params['page'];
        }
        if (!empty($params['page_size'])) {
            $this->defaultPageSize = $params['page_size'];
        }
        $params['order_by'] = 'c.created_at asc';
        $offset = ($this->defaultPage - 1) * $this->defaultPageSize;
        $query->select(['c.id','company_name','company_allname','company_logo','pro_describe','company_describe','linkman','phone','post','service_charge','t.type_name','c.type_id','c.company_pdf'])
            ->from(['c'=>$this->COMPANY_TABLE])
            ->leftJoin(['t'=>$this->COMPANY_TYPE_TABLE],'t.id=c.type_id')
            ->where(['c.state'=>1,'c.check'=>2]);
        if (!empty($params['search_key'])) {
            $query->andWhere(['or',
                ['like', 'c.company_name', $params['search_key']],
                ['like', 'c.company_allname', $params['search_key']]
            ]);
        }
        //按条件搜索
        if (!empty($params['type_id'])){
            $query->andWhere(['c.type_id'=>$params['type_id']]);
        }
        if (!empty($params['strict_state'])){
            $query->andWhere(['c.strict_state'=>$params['strict_state']]);
        }
        $countQuery = clone $query;
        $result = $query
            ->offset($offset)
            ->limit($this->defaultPageSize)
            ->orderBy($params['order_by'])
//            ->groupBy(['c.type_id'])
            ->all();
        //总数
        $count = intval($countQuery->count());
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->defaultPageSize]);
        $page_count = $pages->getPageCount();
        $page_size = $pages->getPageSize();
        $pagination = ['page' => $this->defaultPage, 'page_count' => $page_count, 'page_size' => $page_size, 'count' => $count];
        return ['data' => $result, 'pagination' => $pagination];
    }
    /*
    *企业首页数据
    *@author:lhp
    *@time:2020/04/09
    */
    public function getCompanysDetail()
    {
        $query=new Query();

        $data=$query->select(['id','type_name'])
            ->from(['s'=>$this->COMPANY_TYPE_TABLE])
            ->orderBy('s.sort asc')
            ->all();

        $query1=new Query();
        $query1->select(['c.id','company_name','company_allname','company_logo','pro_describe','company_describe','service_charge','c.type_id'])
            ->from(['c'=>$this->COMPANY_TABLE])
            ->where(['c.check'=>2,'c.state'=>1,'c.strict_state'=>1]);

        $company=$query1->orderBy('c.sort desc,c.strict_state asc')
            ->all();

        $data_list = ArrayHelper::map($data, 'id', 'type_name');
        if(!empty($company)){
            foreach($company as $k=>$v){
                if($data_list[$v['type_id']]){
                    $datas[$v['type_id']]['type_name']=$data_list[$v['type_id']];
                    $datas[$v['type_id']]['type_id']=$v['type_id'];
                    $datas[$v['type_id']]['child'][]=$v;
                }
            }
        }else{
            foreach($data_list as $k=>$v){
                $datas[$k]['type_name']=$v;
                $datas[$k]['type_id']=$k;
                $datas[$k]['child'][]=[];
            }
        }
        $datas= array_values($datas);
        return $datas;
    }
}