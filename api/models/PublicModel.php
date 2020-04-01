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
    protected $CONFIG_TABLE='t_config';//企业表
    protected $COMPANY_TABLE='t_company';//企业表
    protected $COMPANY_TYPE_TABLE='t_company_type';//企业类型表
    protected $BLOGROLL_TABLE='t_blogroll';//友情链接表
    protected $STRICT_TABLE='t_strict_type';//严选类别表
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