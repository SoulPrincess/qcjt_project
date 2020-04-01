<?php
namespace api\models;
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/3/25
 * Time: 16:36
 */

use yii\base\Model;
use yii\db\Query;

class ConfigModel extends PublicModel {
    /*
     * 获取配置信息
     * @author:lhp
     * @time:2020-3-25
     * */
    public function getConfigData(){
       $config_data=(new Query())
           ->select(['name','title','value'])
           ->from($this->CONFIG_TABLE)
           ->all();
       return $config_data;
    }
    /*
     * 友情链接
     * @author:lhp
     * @time:2020-3-27
     * */
    public function getBlogrollData(){
        $config_data=(new Query())
            ->select(['bl_name','bl_url','bl_sort'])
            ->from($this->BLOGROLL_TABLE)
            ->all();
        return $config_data;
    }
}