<?php
namespace api\models;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/3/26
 * Time: 11:18
 */
class PublicModel extends \yii\db\ActiveRecord{

    /***************************************严选表***********************************/
    protected $CONFIG_TABLE='t_config';//企业表
    protected $COMPANY_TABLE='t_company';//企业表
    protected $COMPANY_TYPE_TABLE='t_company_type';//企业类型表
    protected $BLOGROLL_TABLE='t_blogroll';//友情链接表
    protected $STRICT_TABLE='t_strict_type';//严选类别表

    /***************************************官网表***********************************/
    protected $GUAN_NAVIGATION_TABLE='t_guan_navigation';//官网导航表
    protected $GUAN_TYPE_TABLE='t_guan_type';//官网类别表
    protected $GUAN_GOODS_TABLE='t_guan_goods';//官网商品表
    protected $GUAN_GOODS_ATTRIBUTE_TABLE='t_guan_goods_attribute';//官网商品属性表
    protected $GUAN_PRODUCT_TABLE='t_guan_product';//官网产品表
    /*************************************青锐国际表***********************************/
    protected $QING_CASE_TABLE='t_qing_case';//案例表
    protected $QING_TYPE_TABLE='t_qing_type';//类别表
    protected $QING_MESS_TABLE='t_qing_mess';//信息表

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