<?php
/**
 * Note:
 * User: lhp
 * Date: 2020/04/03
 * Time: 13:00
 */

namespace wechat\filters;


use Yii;
use yii\base\ActionFilter;

class BaseFilter extends ActionFilter
{



    /**
     * @Notes:正常业务数据返回
     * @param string $msg
     * @param array $data
     * @param int $code
     * @User:lhp
     */
    public function _response($data = [], $code = 0, $msg = '')
    {
        $result = array(
            'data' => $data,
            'code' => $code,
            'msg' => $msg ? $msg : Yii::$app->params[$code]
        );
        die(json_encode($result, JSON_UNESCAPED_UNICODE));
    }
}