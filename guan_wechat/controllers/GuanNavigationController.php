<?php
namespace guan_wechat\controllers;
/**
 * 官网导航栏
 * User: LHP
 * Date: 2020/4/13
 * Time: 11:15
 */
use api\models\official\GuanNavigationModel;
class GuanNavigationController extends PublicController{
    /*
    * 官网导航
    * @time:2020-4-13
    * @author:Lhp
   */
    public function actionIndex(){
        $type=new GuanNavigationModel();
        $data=$type->getNavigationList();
        return $this->result($data,'200','成功');
    }
}