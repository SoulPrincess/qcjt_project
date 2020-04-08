<?php
/**
 * Note:登录状态及权限校验过滤器
 * 除登录以外的控制器全部引用该过滤器
 * User: kkoma
 * Date: 2019/7/4
 * Time: 11:14
 */

namespace frontend\filters;

use common\libs\BaseFilter;
use frontend\models\login\User;
use frontend\models\school\Menu;
use Yii;


class AuthorityFilter extends BaseFilter
{

    /**
     * @Notes:权限校验过滤器
     * 建议开发环境将此过滤器屏蔽
     * @param \yii\base\Action $action
     * @return bool
     * @User:kkoma
     * @Time: 2019/7/18  15:06
     */
    public function beforeAction($action)
    {
        //验证登录状态
        $headers = Yii::$app->request->headers;
        $request = Yii::$app->request;
        $redis = Yii::$app->redis;
        $white_list = $redis->get('white_list');
        if ($white_list) {
            $white_list = unserialize($white_list);
            $download = $white_list['download'];
            $common_controller = $white_list['common_controller'];
            $common_action = $white_list['common_action'];
        } else {
            $menu = new Menu();
            $white_list = $menu->menuWhite();
            $download = $white_list['download'];
            $common_controller = $white_list['common_controller'];
            $common_action = $white_list['common_action'];
            $redis->set('white_list', serialize($white_list));
        }
        $action_id = $action->id;
        if ($request->isGet && in_array($action_id, $download)) {
            return parent::beforeAction($action);
        }
        if ($headers->has('X-Token')) {
            if (Yii::$app->user->isGuest) {
                $this->_response([], 10002, '登录超时');//用户登录超时
            }
            $cache = Yii::$app->cache;
            $user = Yii::$app->user->identity;
            $authority_token = $user->authority_token;
            $token = $headers->get('X-Token');
            if (empty($authority_token)) {
                $this->_response([], 10002);//用户登录超时
            } elseif ($authority_token != $token) {
                $cache->delete('navigation' . $user->id);
                $cache->delete('check_depart' . $user->school_id);
                $cache->delete('check_num' . $user->school_id);
                $cache->delete('student_import_error_' . $user->id);
                $cache->delete('employ_import_error_' . $user->id);
                Yii::$app->user->logout();
                $this->_response([], 10003);//账号异地登录提醒
            } else {
                $login = User::findOne($user->id);
                $login->last_login = time();
                $login->save();
                if ($user->school_id == 292) {
                    $controller_id = Yii::$app->controller->id;
                    $session = Yii::$app->session;
                    $access = $session->get('access');
                    $json_str = file_get_contents("php://input");
                    $json_str = json_decode($json_str, true);
                    $navigation = $cache->get('navigation' . $user->id);
                    $school = $navigation['school'];
                    if ($user->role == 1) {
                        $log = [
                            'type' => 'master',
                            'school' => $school['school_name'],
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'route' => $controller_id . '/' . $action_id,
                            'data' => $json_str
                        ];
                    } else {
                        if (empty($user->controller)) {
                            $this->_response([], -1, '请联系管理员设置操作权限');
                        }
                        if (isset($access['controller']["$controller_id"]) &&
                            (isset($access['action']["$action_id"]) || isset($access['operation']["$action_id"])) ||
                            (in_array($controller_id, $common_controller) || in_array($action_id, $common_action))) {
                            $log = [
                                'type' => 'ok',
                                'school' => $school['school_name'],
                                'user_id' => $user->id,
                                'user_name' => $user->name,
                                'route' => $controller_id . '/' . $action_id,
                                'data' => $json_str
                            ];
                        } else {
                            $log = [
                                'type' => 'stop',
                                'school' => $school['school_name'],
                                'user_id' => $user->id,
                                'user_name' => $user->name,
                                'route' => $controller_id . '/' . $action_id,
                                'data' => $json_str
                            ];
                            $this->_response([], 50009);//权限不足
                        }
                    }
                    Yii::info($log, 'operate');
                }

                //记录日志操作
                //生产环境除支付外最好不要记录操作日志信息，避免频繁文件读取
//                Yii::info($log, 'operate');
                return parent::beforeAction($action);
            }
        }
        //建议在此处重定向为前端登录链接
        $this->_response([], 10001);//非法的登录凭证
    }
}