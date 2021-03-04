<?php
/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/3/26
 * Time: 10:05
 */
namespace api\controllers;
use common\models\Config;
use vendor\tools\VerifyTools;
use yii\web\Controller;
use yii;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
class PublicController extends Controller{
    public $INDUSTRY_TYPE=[
        ['管理服务'=>['行业SaaS','ERP','CRM','商业智能','供应链管理','客服管理','数据分析','移动门户','知识管理']],
        ['产品研发'=>['微信小程序','网站搭建','通讯服务','工具','开发组件','多媒体服务','CDN','云存储','云计算','安全','UI','设计','运维']],
        ['工商管理'=>['法律服务','财务培训','财务咨询','代理入账','资质认定','公司注册与变更','商标注册','专利注册']],
        ['人力资源'=>['拓展团建','培训','医疗体验','薪酬','灵活用工','招聘','猎头','社保']],
        ['营销推广'=>['程序化广告','移动营销','社会化营销','ASO','SEO/SEM','会展','地推','公关','活动物料']],
        ['金融服务'=>['资产租赁','理财','支付','征信','融资']],
        ['行政办公'=>['办公场地','租赁','公司采购','商务旅行','餐饮','维修','绿植','保洁']],
        ['其他']
    ];//所属行业类型
    public $enableCsrfValidation = false;

//    public function behaviors()
//    {
//        return ArrayHelper::merge([
//            [
//                'class' => Cors::className(),
//                'cors' => [
//                    //'Origin' => ['*'],
//                    //'Access-Control-Request-Method' => ['GET','PUT','POST','DELETE'],
//                   // 'Access-Control-Allow-Credentials' => true,
//                   // 'Access-Control-Allow-Headers' => ['x-requested-with','content-type'],
//                ],
//            ],
//        ], parent::behaviors());
//    }
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
            'msg' => $msg ? $msg : Yii::$app->params[$code]
        );
        return json_encode($result, $option);
    }

    /*
      * 上传pdf文件
      * @time：2020-3-27
      * @author：lhp
      * */
    public function actionUploadPdf()
    {
        $file = $_FILES;
        $file_name = $file['file']['name'];
        $file_tmp_path =$file['file']['tmp_name'];
        $dir = "../web/uploads/".date("Ymd");
        if (!is_dir($dir)){
            mkdir($dir,0777);
        }
        $type = substr(strrchr($file_name, '.'), 1);
        $allow_type = ['pdf'];
        if(!in_array($type, $allow_type)){
            die("只允许上传pdf文件！");
        }
        $file_save_name =  $file_name;
        $file_save_name=iconv("UTF-8","gb2312", $file_save_name);
        move_uploaded_file($file_tmp_path, $dir.'/'.$file_save_name);
        $file_save_name=iconv("gb2312","UTF-8", $file_save_name);
        $arr=[
            "code"=>"200",
            "data"=>strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value) .'uploads/pdf/'.$file_save_name,
        ];
        return json_encode($arr);
    }
    /*
      * layui富文本上传图片
      * @time：2020-3-24
      * @author：lhp
      * */
    public function actionUploadedit()
    {
        $file = $_FILES;
        $file_name = $file['file']['name'];
        $file_tmp_path =$file['file']['tmp_name'];
        $dir = "../web/uploads/".date("Ymd");
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
    public function actionUploadPdf1()
    {
        $file = $_FILES;
        $file_name = $file['file']['name'];
        $file_tmp_path =$file['file']['tmp_name'];
        $dir = "../web/uploads/PDF";
        if (!is_dir($dir)){
            mkdir($dir,0777);
        }
        $type = substr(strrchr($file_name, '.'), 1);
        $allow_type = ['pdf'];
        if(!in_array($type, $allow_type)){
            die("只允许上传pdf文件！");
        }
        $file_save_name = date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
//        $file_save_name =  $file_name;
//        $file_save_name=iconv("UTF-8","gb2312", $file_save_name);
        move_uploaded_file($file_tmp_path, $dir.'/'.$file_save_name);
      //  $file_save_name=iconv("gb2312","UTF-8", $file_save_name);
        $arr=[
            "code"=>"200",
            "data"=>strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value) .'uploads/PDF/'.$file_save_name,
        ];
        return json_encode($arr);
    }
}