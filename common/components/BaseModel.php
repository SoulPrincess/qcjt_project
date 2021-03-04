<?php
namespace common\components;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/5/11
 * Time: 11:18
 */
class BaseModel extends \yii\db\ActiveRecord{

    /***************************************严选表***********************************/
    protected $CONFIG_TABLE='t_config';//企业表
    protected $COMPANY_TABLE='t_company';//企业表
    protected $COMPANY_TYPE_TABLE='t_company_type';//企业类型表
    protected $BLOGROLL_TABLE='t_blogroll';//友情链接表
    protected $STRICT_TABLE='t_strict_type';//严选类别表

    /***************************************严选小程序表***********************************/

    protected $YANXUAN_WECHAT_USER='t_yanxuan_wechat_user';//严选小程序用户表

    /***************************************官网表***********************************/
    protected $GUAN_NAVIGATION_TABLE='t_guan_navigation';//官网导航表
    protected $GUAN_TYPE_TABLE='t_guan_type';//官网类别表
    protected $GUAN_GOODS_TABLE='t_guan_goods';//官网商品表
    protected $GUAN_GOODS_ATTRIBUTE_TABLE='t_guan_goods_attribute';//官网商品属性表
    protected $GUAN_PRODUCT_TABLE='t_guan_product';//官网产品表

    /***************************************青商创服小程序表********************************/

    protected $GUAN_WECHAT_USER='t_guan_wechat_user';//青商创服小程序用户表
    /***************************************分页参数***********************************/
    protected $defaultPage=1;
    protected $defaultPageSize=20;


    /***************************************公共方法***********************************/
    /**递归实现
     * @param $arr array
     * @param $id   int
     * @return array
     * @author:lhp
     * @time:2020-3-27
     */
    public function recursion($arr, $id)
    {
        $list = array();
        foreach ($arr as $k => $v) {
            if ($v['pid'] == $id) {
                $child = $this->recursion($arr, $v['id']);
                if (!empty($child)) {
                    $v['child'] = $child;
                }
                $list[] = $v;
            }
        }
        return $list;
    }
}