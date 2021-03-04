<?php
/**
 * Note:接口次数请求限制
 * User: lhp
 * Date: 2020/04/03
 * Time: 13:28
 */

namespace wechat\filters;

use Yii;
use yii\base\ActionFilter;

class LimitFilter extends BaseFilter
{
    /**
     * @Notes:同一接口2s内单一用户只允许post提交一次
     * @param \yii\base\Action $action
     * @return bool
     * @User:lhp
     * @Time: 2020/04/03
     */
    public function beforeAction($action)
    {
        $actionID = $action->id;
        $cache = Yii::$app->cache;
        if (Yii::$app->request->isPost && $actionID != 'upload') {
            $controllerID = Yii::$app->controller->id;
            $lock_prefix = md5(Yii::$app->request->userHost . $controllerID . $actionID);
            $num = $cache->get($lock_prefix);
            if ($num > 1) {
                //次数超过10次记录ip主机等详细信息
                if ($num >= 10) {
                    //记录数据库
                    $ip_str = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
                    $ips = explode(',', $ip_str);
                    $ip = trim(array_pop($ips));
                    $this->_response([], 50000);//该ip已被限制访问
                }
                $this->_response([], 50001);//请勿重复提交
            } else {
                $cache->set($lock_prefix, ($num + 1), 1);
            }
        }
        return parent::beforeAction($action);
    }
}