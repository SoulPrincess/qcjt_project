<?php
/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/11/29
 * Time: 14:06
 */

namespace rbac\controllers;


use content\models\Test;

class WechatController
{
    protected $appid;
    protected $secret;
    protected $accessToken;

    function __construct(){
        $this->appid       = "wxeaf881f27984d534";
        $this->secret      = "a0a0c032afac3df8e06319c7c3ba08fa";
        $this->accessToken = $this->getAccessToken();
    }

    /***
     * 获取access_token
     * token的有效时间为2小时，这里可以做下处理，提高效率不用每次都去获取，
     * 将token存储到缓存中，每2小时更新一下，然后从缓存取即可
     * @return
     **/
    private function getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->secret;
        $res = json_decode($this->httpRequest($url),true);
        return $res['access_token'];
    }

    /***
     * POST或GET请求
     * @url 请求url
     * @data POST数据
     * @return
     **/
    private function httpRequest($url, $data = ""){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)){ //判断是否为POST请求
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /***
     * 获取openID和unionId
     * @code 微信授权登录返回的code
     * @return
     **/
    public function getOpenIdOrUnionId($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->secret."&code=".$code."&grant_type=authorization_code";
        $data = $this->httpRequest($url);
        return $data;
    }

    /***
     * 通过openId获取用户信息
     * @openId
     * @return
     **/
    public function getUserInfo($openId){
        $url  = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->accessToken."&openid=".$openId."&lang=zh_CN";
        $data = $this->httpRequest($url);
        return $data;
    }

    /***
     * 发送模板短信
     * @data 请求数据
     * @return
     **/
    public function sendTemplateMessage($data = ""){
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->accessToken;
        $result = $this->httpRequest($url, $data);
        return $result;
    }

    /***
     * 生成带参数的二维码
     * @scene_id 自定义参数（整型）
     * @return
     **/
    public function getQrcode($scene_id){
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->accessToken;
        $data = array(
            "expire_seconds" => 3600, //二维码的有效时间（1小时）
            "action_name" => "QR_SCENE",
            "action_info" => array("scene" => array("scene_id" => $scene_id))
        );
        $result = $this->httpRequest($url, json_encode($data));
        return $result;
    }

    /***
     * 生成带参数的二维码
     * @scene_str 自定义参数（字符串）
     * @return
     **/
    public function getQrcodeByStr($scene_str){
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->accessToken;
        $data = array(
            "expire_seconds" => 3600, //二维码的有效时间（1小时）
            "action_name" => "QR_STR_SCENE",
            "action_info" => array("scene" => array("scene_str" => $scene_str))
        );
        $result = $this->httpRequest($url, json_encode($data));
        return $result;
    }

    /**
     * 换取二维码
     * @ticket
     * @return
     */
    public function generateQrcode($ticket){
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
    }

    /***
     * 回调函数
     **/
    public function callback(){
        $callbackXml = file_get_contents('php://input'); //获取返回的xml
        $data = json_decode(json_encode(simplexml_load_string($callbackXml, 'SimpleXMLElement', LIBXML_NOCDATA)), true); //将返回的xml转为数组

        if(count($data)){
            if($data['Event'] && $data['Event'] == 'subscribe') {
                $toUser = $data['FromUserName'];
                $fromUser = $data['ToUserName'];
                $time = time();
                $msgType = 'text';
                $content = '好嗨哟，您终于来啦！';
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
            $userInfo = $this->getUserInfo($data['FromUserName']); //获取用户信息
//            $user= new Test();
//            $user->scene_str =$username;
//            $user->info =json_encode($userInfo);
//            $user->save();
            //这里把返回的数据写入数据库（注：务必将“EventKey”也存到数据表中，后面检测登录需用到此唯一值查询记录）
            //用于前台做检测该用户扫码之后是否有关注公众号，关注了就自动登录网站
            //原理：前台通过自定义的参数（最好设成值唯一）查询数据标是否有此记录，若有则登录。
            return $userInfo;
        }
    }

    public function callback_(){
//    $postStr = file_get_contents('php://input');
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $scene_id = str_replace("qrscene_", "", $postObj->EventKey);

        $openid = $postObj->FromUserName; //openid
        $ToUserName = $postObj->ToUserName;  //转换角色
        $Event = strtolower($postObj->Event);

        $data = array(
            'scene_id'=>$scene_id,
            'openid'=>$openid,
            'ToUserName'=>$ToUserName,
            'is_first'=>'1',
            'Event'=>$Event
        );
        return json_encode($data);
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        //extract post data
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>  
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Content><![CDATA[%s]]></Content>  
                            <FuncFlag>0</FuncFlag>  
                            </xml>";
            if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = "Welcome to wechat world!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }

        }else {
            echo "";
            exit;
        }
    }

    public function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'weixin';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}