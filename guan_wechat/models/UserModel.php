<?php
namespace guan_wechat\models;
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/5/11
 * Time: 14:55
 */
use common\components\BaseModel;
use yii;
use yii\db\Query;

class UserModel extends BaseModel
{
    /*
   * 用户信息
   * $where array
   * @author:lhp
   * @time:2020-5-11
   * */
    public function getUserDetail($where=[]){
        $query=new Query();
        $query->select([])
            ->from($this->YANXUAN_guan_WECHAT_USER);
        if($where){
            $query->andWhere($where);
        }
        $user_data=$query->one();

        return $user_data;
    }
    /*添加用户
   *$param array
   *@time:2020-5-11
   *@author:Lhp
   */
    public function addUser($param=[]){
        $connection=Yii::$app->db;
        $transaction=$connection->beginTransaction();
        try{
            $param['created_at']=time();
            $param['updated_at']=time();
            $connection->createCommand()->insert($this->GUAN_WECHAT_USER,$param)->execute();
            $transaction->commit();
            return true;
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}