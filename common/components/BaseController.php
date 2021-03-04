<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/5/8
 * Time: 16:29
 */

namespace common\components;
use yii\web\Controller;
use yii;
class BaseController extends Controller
{

    /**
     * @Notes:接收前端json数据
     * @return mixed
     * @User:LHP
     * @Time: 2020/3/24
     */
    function get_json()
    {
        $json_str = file_get_contents("php://input");
        $result = json_decode($json_str, true);
        return $result;
    }

    /**
     * @Notes:判断数据字段是否为空
     * @param array $result
     * @param string $key
     * @param string $default
     * @return mixed|string
     * @User:LHP
     * @Time: 2020/3/24
     */
    function verifyEmpty($result = [], $key = '', $default = '')
    {
        if (!empty($result[$key])) {
            if (is_array($result[$key])) {
                return $result[$key];
            }
            return trim($result[$key]);
        }
        return $default;
    }

    /**
     * Notes:判断数据字段是否有传
     * @param array $result
     * @param string $key
     * @return int|mixed
     * @User:LHP
     * @Time: 2020/3/24
     */
    function verifyIsset($result = [], $key = '')
    {
        $data = isset($result[$key]) ? trim($result[$key]) : '';
        return $data;
    }

    /**
     * @Notes:验证post请求
     * @return bool|mixed
     * @User:LHP
     * @Time: 2020/3/24
     */
    protected function is_post()
    {
        return Yii::$app->request->isPost;
    }

    /**
     * Notes:验证get请求
     * @return bool|mixed
     * @User:LHP
     * @Time: 2020/3/24
     */
    protected function is_get()
    {
        return Yii::$app->request->isGet;
    }

    /**
     * @Notes: 验证ajax请求
     * @return bool|mixed
     * @User:LHP
     * @Time: 2020/3/24
     */
    protected function is_ajax()
    {
        return Yii::$app->request->isAjax;
    }

    /**
     * @Notes:正常业务数据返回
     * @param array $data
     * @param int $code
     * @param string $msg
     * @param int $option
     * @User:lhp
     * @Time: 2020/3/24
     */
    public function result($data = [], $code = 0, $msg = '', $option = JSON_UNESCAPED_UNICODE)
    {
        $result = array(
            'data' => $data,
            'code' => $code,
            'msg' => $msg ? $msg : Yii::$app->params['params'][$code]
        );
        die(json_encode($result, $option));
    }
}