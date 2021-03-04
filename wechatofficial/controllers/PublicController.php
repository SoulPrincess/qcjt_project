<?php
/**
 * Note:公共入口文件
 * User: LHP
 * Date: 2020/5/11
 * Time: 09:57
 */

namespace wechatofficial\controllers;

use common\components\BaseController;
use wechat\models\UserModel;
use Yii;


class PublicController extends BaseController
{
    public $enableCsrfValidation = false;
    public $user = [];//存储用户基本信息
    public $openid = '';
    public $session_key = '';
    /**
     * @Notes:公众号登录入口
     * @param $action
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     * @User:LHP
     * @Time: 2020/5/11
     */
    public function beforeAction($action)
    {
        $headers = Yii::$app->request->headers;
        if ($headers->has('X-Code')) {
            $params = Yii::$app->params['components']['yanxuan_wechat'];
            $_code = $headers->get('X-Code');
            $url='https://api.weixin.qq.com/sns/jscode2session?appid=' .$params['appid'] . '&secret=' . $params['secret']  . '&js_code=' . $_code . '&grant_type=authorization_code';
            $ret = file_get_contents($url);
            $ret = json_decode($ret, true);
            if($ret['errcode']!=0){
                $this->result([], $ret['errcode']);
            }
            if (isset($ret['openid'])) {
                $openId = $ret['openid'];
                $this->openid = $openId;
                $this->session_key = (isset($ret['session_key']) && !empty($ret['session_key'])) ? $ret['session_key'] : '';
            } else {
                $this->result([], -1, '网络错误');
            }
        }else{
           $this->result([], 10001);
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
    /*获取小程序用户私密信息
     * @User:LHP
     * @Time: 2020/5/11
    * */
    public function actionGetUser(){
        $params = Yii::$app->params['components']['yanxuan_wechat'];
        $appid = $params['appid'];
        $json = $this->get_json();
        $sessionKey = $this->session_key;
        $encryptedData = $this->verifyEmpty($json, 'encryptedData');
        $iv = $this->verifyEmpty($json, 'iv');
        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $data = json_decode($data,true);
            session('yanxuan_myinfo',$data);
            $save['openid'] = $data['openId'];
            $save['nick_name'] = $data['nickName'];
            $save['sex'] = $data['gender'];//性别 0：未知、1：男、2：女
            $save['city'] = $data['city'];//城市
            $save['province'] = $data['province'];//省份
            $save['country'] = $data['country'];//国家
            $save['avatar'] = $data['avatarUrl'];//用户头像
            $save['wx_union_id'] = $data['unionId'];//unionId
            $save['time'] = time();
            !empty($data['unionId']) && $save['wx_union_id'] = $data['unionId'];
            $user_model=new UserModel();
            $user_data=$user_model->getUserDetail(['openid'=>$save['openid']]);
            if(!$user_data){
                $db = $user_model ->addUser($save);
                if($db !== false){
                    $this->result([], 200);
                }else{
                    $this->result([], 100);
                }
            }else{
                $this->result([], 10006);
            }
        } else {
            $this->result([], $errCode);
        }
    }


    function http_curl($url,$data =array(),$method ="get",$returnType ="json")

    {

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

        if($method!="get"){

            curl_setopt($ch,CURLOPT_POST,TRUE);

            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

        }

        curl_setopt($ch,CURLOPT_URL,$url);

        $json = curl_exec($ch);

        curl_close($ch);

        if($returnType == "json"){

            return json_decode($json,true);

        }

        return $json;

    }


  public  function get_access_token()

    {

        $appid = "wx1ba8f59d9e2c0be0"; //微信的appid

        $secret ="9e65155599fb9ec047455e197ff6e121"; //微信的开发者密钥

// 读取缓存中的内容

        include_once "MyMemcache.php";  //引入缓存方法文件

        $obj = new \MyMemcache("47.104.71.253");

        $value = $obj ->get($appid);

        if(!$value){

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;

            $result = $this->http_curl($url);

            $value = $result['access_token'];

            $obj->set($appid,$value,7000);

        }

        return $value;

    }
}