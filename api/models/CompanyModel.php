<?php
namespace api\models;
use api\models\PublicModel;
use yii\base\Model;
use yii\db\Query;
use yii\db\connection;
use yii;
use yii\behaviors\TimestampBehavior;

/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/3/26
 * Time: 11:17
 */

class CompanyModel extends PublicModel {
    /*
    * 企业详情信息
    * @author:lhp
    * @time:2020-3-26
    * */
    public function getCompanyDetail(){
        $config_data=(new Query())
            ->select(['c.id','company_name','company_allname','company_logo','pro_describe','company_describe','linkman','phone','post','service_charge','t.type_name','t.id type_id','c.check','s.name strict_name'])
            ->from(['c'=>$this->COMPANY_TABLE])
            ->leftJoin(['t'=>$this->COMPANY_TYPE_TABLE],'t.id=c.type_id')
            ->leftJoin(['s'=>$this->STRICT_TABLE],'s.id=c.strict_id')
            ->where(['c.state'=>'1'])
            ->all();
        return $config_data;
    }

    /*
  * 企业详情信息
  * $id int
  * @author:lhp
  * @time:2020-3-26
  * */
    public function getCompanyOneDetail($id){

        $config_data=(new Query())
            ->select(['c.id','company_name','company_allname','company_logo','pro_describe','company_describe','linkman','phone','post','service_charge','t.type_name','t.id type_id','s.name strict_name','c.company_pdf'])
            ->from(['c'=>$this->COMPANY_TABLE])
            ->leftJoin(['t'=>$this->COMPANY_TYPE_TABLE],'t.id=c.type_id')
            ->leftJoin(['s'=>$this->STRICT_TABLE],'s.id=c.strict_id')
            ->where(['c.id'=>$id,'c.state'=>'1'])
            ->one();

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
}