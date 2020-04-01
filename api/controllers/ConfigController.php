<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/3/25
 * Time: 15:02
 */
namespace api\controllers;

use api\models\ConfigModel;

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
}