<?php
namespace api\models\official;
use api\models\PublicModel;
use yii\db\Query;
use yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/4/13
 * Time: 15:15
 */

class GuanGoodsModel extends PublicModel {
    /*
    * 官网类别列表
    * @author:lhp
    * @time:2020-4-13
    * */
    public function getTypeList(){
        $query=new Query();
        $data=$query->select(['g.id g_id','type_name','t.intro','pid','t.id','t.cover_img','t.wechat_img','t.contact','t.phone','t.hot_img'])
            ->from(['t'=>$this->GUAN_TYPE_TABLE])
            ->leftJoin(['g'=>$this->GUAN_GOODS_TABLE],'g.type_id=t.id')
            ->where(['t.status'=>1])
            ->orderby('t.sort asc')
            ->all();
        $arr_data= $this->recursion($data,0);
        foreach($arr_data as $k=>$v){
            if($v['type_name']!='更多'&&isset($v['child'])){
                foreach($v['child'] as $k1=>$v1){
                    if($v1['g_id']==''){
                        unset($arr_data[$k]['child'][$k1]);
                    }
                    if(!empty($v1['hot_img'])){
                        $arr_data[$k]['hot_img'][$v1['id']]=$v1['hot_img'];
                    }
                }
            }
        }
        return $arr_data;
    }
    /*
    *官网商品列表
    *@author:lhp
    *@time:2020/04/09
    */
    public function getGuanGoodsDetail($type='')
    {
        $typelist=$this->getTypeList();//类别
        $array_he=[];
        $query=new Query();
        $query->select(['g.id g_id','t.type_name','g.intro','g.type_id','g.content','g.cost','t.cover_img type_img'])
            ->from(['g'=>$this->GUAN_GOODS_TABLE])
            ->leftJoin(['t'=>$this->GUAN_TYPE_TABLE],'t.id=g.type_id')
            ->where(['g.status'=>1]);
        if($type){
            $query->andWhere(['t.home_status'=>1]) ;
        }
        $goods=$query->orderBy('g.created_at asc,t.sort asc')
            ->all();
        foreach($typelist as $k=>$v){
            if($v['type_name']=='更多'&&isset($v['child'])){
                foreach($v['child'] as $k1=>$v1){
                    $typelist[]=$v1;
                    unset($typelist[$k]);
                }
            }
        }
        foreach($typelist as $k=>$v){
            if(isset($v['child'])){
                $typelist[$k]['child'] = ArrayHelper::map($v['child'], 'id', 'type_name');
            }
        }
        if(!empty($goods)) {
            foreach ($typelist as $k => $v) {
                if (is_array($v['child']) && $v['child']) {
                    foreach ($goods as $k2 => $v2) {
                        if ($v['child'][$v2['type_id']]) {
                            $array_he[$k]['type_id']= $v['id'];
                            $array_he[$k]['type_name'] = $v['type_name'];
                            $array_he[$k]['intro'] = $v['intro'];
                            $array_he[$k]['cover_img'] = $v['cover_img'];
                            $array_he[$k]['child'][] = $v2;
                        }
                    }
                }
            }
        }
        return $array_he;
    }
    /*
    * 商品详情
    * $where array
    * @author:lhp
    * @time:2020-4-14
    * */
    public function getGoodsOneDetail($where=[]){
        $query=new Query();
        $query->select(['g.id','t.type_name','g.intro','g.cost g_cost','g.content','g.attr_id','t.pid','g.cover_img'])
            ->from(['g'=>'t_guan_goods'])
            ->leftJoin(['t'=>'t_guan_type'],'t.id=g.type_id');
        if($where){
            $query->andWhere($where);
        }
        $config_data=$query->one();
//        $img=(new Query())->select(['t.cover_img'])
//            ->from(['t'=>'t_guan_type'])
//            ->where(['id'=>$config_data['pid']])
//            ->one();
//        $config_data['cover_img']=$img['cover_img'];

        $config_data['attr']=(new Query())->select(['a.id','a.attribute','a.cost'])
            ->from(['a'=>'t_guan_goods_attribute'])
            ->where(['goods_id'=>$where['g.id']])
            ->all();

        return $config_data;
    }

//测试
    public function getGuanGoodsDetail2()
    {
        $typelist=$this->getTypeList();//类别
        $array_he=[];
        $query=new Query();
        $query->select(['g.id','t.type_name','g.intro','g.type_id','g.content','g.cover_img','g.cost','g.wechat_img'])
            ->from(['g'=>$this->GUAN_GOODS_TABLE])
            ->leftJoin(['t'=>$this->GUAN_TYPE_TABLE],'t.id=g.type_id')
            ->where(['g.status'=>1]);
        $goods=$query->orderBy('g.created_at asc,t.sort asc')
            ->all();

        foreach($typelist as $k=>$v){
            $typelist[$k]['child'] = ArrayHelper::map($v['child'], 'id', 'type_name');
        }
        if(!empty($goods)) {
            foreach ($typelist as $k => $v) {
                if (is_array($v['child']) && $v['child']) {
                    foreach ($goods as $k2 => $v2) {
                        if ($v['child'][$v2['type_id']]) {
                            $array_he[$k]['type_id']= $v['id'];
                            $array_he[$k]['type_name'] = $v['type_name'];
                            $array_he[$k]['intro'] = $v['intro'];
                            $array_he[$k]['child'][] = $v2;
                        }
                    }
                }
            }
        }
        return $array_he;
    }

    /*
  * 服务搜索
  * $where array
  * @author:lhp
  * @time:2020-4-15
  * */
    public function getGuanGoodsSearch($params=[]){

        $query = new Query();
        $params['order_by'] = 't.sort asc';
        $query->select(['g.id g_id','t.type_name','g.intro','g.content','g.cover_img','g.cost','g.wechat_img'])
            ->from(['g'=>$this->GUAN_GOODS_TABLE])
            ->leftJoin(['t'=>$this->GUAN_TYPE_TABLE],'t.id=g.type_id')
            ->where(['g.status'=>1]);
        if (!empty($params['search_key'])) {
            $query->andWhere(
                ['like', 't.type_name', $params['search_key']]
            );
        }
        $countQuery = clone $query;
        $result = $query
            ->orderBy($params['order_by'])
            ->all();
        //总数
        $count = intval($countQuery->count());
        return ['data' => $result, 'count' => $count];
    }
    public function getGuanGoodsSearch1($params=[]){

        $query = new Query();
        $params['order_by'] = 't.sort asc';
        $query->select(['g.id g_id','t.type_name','g.intro','g.content','g.cover_img','g.cost'])
            ->from(['g'=>$this->GUAN_GOODS_TABLE])
            ->leftJoin(['t'=>$this->GUAN_TYPE_TABLE],'t.id=g.type_id')
            ->where(['g.status'=>1]);
        if (!empty($params['search_key'])) {
            $query->andWhere(
                ['like', 't.type_name', $params['search_key']]
            );
        }
        $countQuery = clone $query;
        $result = $query
            ->orderBy($params['order_by'])
            ->all();
        //总数
        $count = intval($countQuery->count());
        return ['data' => $result, 'count' => $count];
    }
}