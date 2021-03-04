<?php
/**
 * Note:登录ip限制过滤器
 * User: lhp
 * Date: 2020/4/3
 * Time: 11:17
 */

namespace wechat\filters;


use common\libs\BaseFilter;
use Yii;


class IpFilter extends BaseFilter
{

    public function beforeAction($action)
    {
        //限制黑名单访问
        return parent::beforeAction($action);
    }
}