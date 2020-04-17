<?php
namespace api\controllers;
/**
 * 官网导航栏
 * User: LHP
 * Date: 2020/4/13
 * Time: 15:12
 */
use api\models\official\GuanGoodsModel;
class GuanGoodsController extends PublicController{
    /*
    * 官网类别
    * @time:2020-4-13
    * @author:Lhp
   */
    public function actionTypeList(){
        $type=new GuanGoodsModel();
        $data=$type->getTypeList();
        return $this->result($data,'200','成功');
    }
    public function actionTypeList1(){
        $type=new GuanGoodsModel();
        $data=$type->getTypeList1();print_r($data);die;
        return $this->result($data,'200','成功');
    }
    /*
   * 官网商品
   * @time:2020-4-13
   * @author:Lhp
  */
    public function actionGoodsList(){
        $type=new GuanGoodsModel();
        $data=$type->getGuanGoodsDetail();
        return $this->result($data,'200','成功');
    }

    /*
   * 商品详情
   * @time:2020-4-14
   * @author:Lhp
  */
    public function actionGoodsDetail(){

        $goodsmodel= new GuanGoodsModel();
        $id =$_GET['id'];
        if($id){
            $data= $goodsmodel->getGoodsOneDetail(['g.id'=>$id]);
            return $this->result($data,'200','成功');
        }else{
            return $this->result([],'201','参数不能为空');
        }
    }
    /*
  * 服务搜索
  * @time:2020/04/15
  * @author:Lhp
  */
    public function actionGuanGoodsSearch(){
        $json = $this->get_json();
        $search_key =$this->verifyEmpty($json,'search_key');//搜索关键字
        $params['search_key']=$search_key;
        $type=new GuanGoodsModel();
        $data=$type->getGuanGoodsSearch($params);
        return $this->result($data,'200','成功');
    }
    public function actionGuanGoodsSearch1(){
        $json = $this->get_json();
        $search_key =$this->verifyEmpty($json,'search_key','税务');//搜索关键字
        $params['search_key']=$search_key;
        $type=new GuanGoodsModel();
        $data=$type->getGuanGoodsSearch1($params);print_r($data);die;
        return $this->result($data,'200','成功');
    }
    //测试
    public function actionGoodsList1(){
        $type=new GuanGoodsModel();
        $data=$type->getGuanGoodsDetail2();print_r($data);die;
        return $this->result($data,'200','成功');
    }
}