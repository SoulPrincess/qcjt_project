<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/5/9
 * Time: 15:02
 */
namespace wechat\controllers;

use api\models\ConfigModel;
use api\models\CompanyType;
use yii;

class ConfigController extends PublicController {

    public $enableCsrfValidation = false;

    /*
     * 获取配置信息
     * @time:2020-3-25
     * @author:Lhp
    */
    public function actionIndex(){
        $configmodel= new ConfigModel();
        $data= $configmodel->getConfigData();
        return $this->result($data,'200','成功');
    }

    /*
     * 友情链接
     * @time:2020-3-27
     * @author:Lhp
     */
    public function actionBlogrollIndex(){
        $configmodel= new ConfigModel();
        $data= $configmodel->getBlogrollData();
        return $this->result($data,'200','成功');
    }

    /*
    * 严选类别
    * @time:2020/04/07
    * @author:Lhp
    */
    public function actionStrictType(){
        $type=new CompanyType();
        $data=$type->strictType();
        return $this->result($data,'200','成功');
    }
}