<?php
/**
 * Note:公共入口文件
 * User: LHP
 * Date: 2020/5/11
 * Time: 09:57
 */

namespace guan_wechat\controllers;

use common\components\BaseController;
use guan_wechat\models\UserModel;
use common\models\Config;
use Yii;
use WXBizDataCrypt;

class PublicController extends BaseController
{
    public $enableCsrfValidation = false;
    public $user = [];//存储用户基本信息
    public $openid = '';
    public $session_key = '';
    public $is_bind = 0;
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = '';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if ($tmpStr == $signature ) {
            return true;
        } else {
            return false;
        }
    }
    /*
    * 获取token
    * @time：2020-5-28
    * @author：lhp
    * @time：2020-5-28
    * */
    public function get_access_token(){
        $params = Yii::$app->params['components']['guan_wechat'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$params['appid']."&secret=".$params['secret'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        $data['data'] = $jsoninfo;
        return $data;
    }
    /**
     * @Notes:小程序登录入口
     * @param $action
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     * @User:LHP
     * @Time: 2020/5/11
     */
    public function GetOpenid()
    {
        $headers = Yii::$app->request->headers;
        if ($headers->has('X-Code')) {
            $params = Yii::$app->params['components']['guan_wechat'];
            $_code = $headers->get('X-Code');
            $url='https://api.weixin.qq.com/sns/jscode2session?appid=' .$params['appid'] . '&secret=' . $params['secret']  . '&js_code=' . $_code . '&grant_type=authorization_code';
            $ret = file_get_contents($url);
            $ret = json_decode($ret, true);
            if($ret['errcode']!=0){
                $this->_response([], $ret['errcode']);
            }
            if (isset($ret['openid'])) {
                $openId = $ret['openid'];
                $this->openid = $openId;
                $this->session_key = (isset($ret['session_key']) && !empty($ret['session_key'])) ? $ret['session_key'] : '';
            } else {
               $this->result([], -1, '网络错误');
            }
            $this->openid = $openId;
            $user_model=new UserModel();
            $user_data=$user_model->getUserDetail(['openid'=>$this->openid]);
            if (!empty($user_data['id'])) {
                $this->user = $user_data;
                $this->is_bind = 1;
                $this->_response([], 200);
            }
        }else{
           $this->_response([], 10001);
        }
    }
    /*获取小程序用户私密信息
     * @User:LHP
     * @Time: 2020/5/11
    * */
    public function actionGetUser(){
        $json = $this->get_json();
        if ($this->is_post()) {
            $this->GetOpenid();
            $save['openid'] = $this->openid;
            $save['session_key'] = $this->session_key;
            $save['nickName'] = $this->verifyEmpty($json,'nickName');
            $save['gender'] =$this->verifyEmpty($json,'gender');//性别 0：未知、1：男、2：女
            $save['city'] = $this->verifyEmpty($json,'city');//城市
            $save['province'] = $this->verifyEmpty($json,'province');//省份
            $save['country'] = $this->verifyEmpty($json,'country');//国家
            $save['avatarUrl'] = $this->verifyEmpty($json,'avatarUrl');//用户头像
            $save['language'] = $this->verifyEmpty($json,'language');//语言
            $user_model=new UserModel();
            $db = $user_model ->addUser($save);
            if($db !== false){
                $this->_response([], 200);
            }else{
                $this->_response([], 100);
            }
        } else {
            $this->_response([], '-1');
        }
    }

    /**
     * @Notes:小程序数据返回
     * @param string $msg
     * @param array $data
     * @param int $code
     * @User:lhp
     * @Time: 2020-5-13
     */
    public function _response($data = [], $code = 0, $msg = '', $option = JSON_UNESCAPED_UNICODE)
    {
        $data['openid'] = $this->openid;
        $data['is_bind'] = $this->is_bind;
        $data['user'] = $this->user;
        $result = array(
            'data' => $data,
            'code' => $code,
            'msg' => $msg ? $msg : Yii::$app->params[$code]
        );
        die(json_encode($result, $option));
    }

    /*
     * 上传图片
     * @time：2020-5-18
     * @author：lhp
     * */
    public function actionUploadedit()
    {
        $file = $_FILES;
        $file_name = $file['file']['name'];
        $file_tmp_path =$file['file']['tmp_name'];
        $dir = "../uploads/".date("Ymd");
        if (!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $type = substr(strrchr($file_name, '.'), 1);
        $mo = Config::findOne(['name'=>'WEB_SITE_ALLOW_UPLOAD_TYPE']);
        $allow_type = explode(',', $mo->value);
        if(!in_array($type, $allow_type)){
            die("文件类型不允许！");
        }
        $file_save_name = date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
        $info= move_uploaded_file($file_tmp_path, $dir.'/'.$file_save_name);
        if($info){
            //图片上传成功后，组好json格式，返回给前端
            $arr   = array(
                'code' => 0,
                'msg'=>'',
                'data' =>array(
                    'src' =>strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/'.date("Ymd").'/'.$file_save_name
                ),
            );
        }else{
            $arr   = array(
                'code' => 1,
                'msg'=>'上传失败',
                'data' =>array(
                    'src' => ''
                ),
            );
        }

        return json_encode($arr);
    }
}