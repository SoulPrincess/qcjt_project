<?php
namespace backend\controllers;

use official\models\GuanGoods;
use rbac\controllers\WechatController;
use yii\web\Controller;
use common\models\Config;
use yii;
class ToolsController extends Controller
{
    public function result($data = [], $code = 0, $msg = '', $option = JSON_UNESCAPED_UNICODE)
    {
        $result = array(
            'data' => $data,
            'code' => $code,
            'msg' => $msg ? $msg : Yii::$app->params[$code]
        );
        return json_encode($result, $option);
    }
    /*ueditor富文本上传图片接口
    *@author:lhp
    *@time:2020-4-22
    */
    public function actions()
    {
        return [
            'ueditorupload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  =>strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value),//图片访问路径前缀
                    "imagePathFormat"=>"/uploads/{yyyy}{mm}{dd}/{yyyy}{mm}{dd}{hh}{ii}{ss}{rand:4}" ,//上传保存路径
                    "imageRoot" => Yii::getAlias("@apiimage"),
                ],
            ]
        ];
    }
	/**
	 * 富文本编辑器上传文件
	 */
    public function actionUploadEditor()
    {
        $file = $_FILES;
        $file_name = $file['wangEditorH5File']['name'];
        $file_tmp_path =$file['wangEditorH5File']['tmp_name'];
        $dir = "../../uploads/".date("Ymd");
        if (!is_dir($dir)){
            mkdir($dir,0777);
        }
		$type = substr(strrchr($file_name, '.'), 1);
		$mo = Config::findOne(['name'=>'WEB_SITE_ALLOW_UPLOAD_TYPE']);
		$allow_type = explode(',', $mo->value);
		if(!in_array($type, $allow_type)){
			die("文件类型为允许的格式");
		}
        $file_save_name = date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
        move_uploaded_file($file_tmp_path, $dir.'/'.$file_save_name);
        echo strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value) . date('Ymd').'/'.$file_save_name;
    }
    /*
     * 文件上传
     * @time：2020-3-23
     * @author：lhp
     * */
    public function actionUpload()
    {
        $file = $_FILES;
        $file_name = $file['file']['name'];
        $file_tmp_path =$file['file']['tmp_name'];
        $mo = Config::findOne(['name'=>'WEB_SITE_IMAGE_SIZE']);
        $file_size =$file['file']['size'];
        $max_size=$mo->value;
        if($max_size<$file_size){
            $arr=[
                "code"=>"1",
                'msg'=>'文件大小超出！',
                "data"=>''
            ];
            echo json_encode($arr);die;
        }
        if(isset($_GET['name'])){
            $path_api="../../api/web/uploads/".$_GET['name'].'/';
        }else{
            $path_api="../../api/web/uploads/";
        }
        $dir_api = $path_api.date("Ymd");
        if (!is_dir($dir_api)){
            mkdir($dir_api,0777,true);
        }
		$type = substr(strrchr($file_name, '.'), 1);
		$mo = Config::findOne(['name'=>'WEB_SITE_ALLOW_UPLOAD_TYPE']);
		$allow_type = explode(',', $mo->value);
		if(!in_array($type, $allow_type)){
            $arr=[
                "code"=>"1",
                'msg'=>'文件类型不允许！',
                "data"=>''
            ];
            echo json_encode($arr);die;
		}
        $file_save_name =date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
        move_uploaded_file($file_tmp_path, $dir_api.'/'.$file_save_name);
        if(isset($_GET['name'])){
            $url=strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/'.$_GET['name'].'/'.date("Ymd").'/'.$file_save_name;
        }else{
            $url=strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/'.date("Ymd").'/'.$file_save_name;
        }
        $arr=[
            "code"=>"200",
            "data"=> $url
        ];
        echo json_encode($arr);
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
        if(isset($_GET['name'])){
            $path_api="../../api/web/uploads/".$_GET['name'].'/'.date("Ymd");
        }else{
            $path_api="../../api/web/uploads/".date("Ymd");
        }
        if (!is_dir($path_api)){
            mkdir($path_api,0777,true);
        }
        $type = substr(strrchr($file_name, '.'), 1);
        $mo = Config::findOne(['name'=>'WEB_SITE_ALLOW_UPLOAD_TYPE']);
        $allow_type = explode(',', $mo->value);
        if(!in_array($type, $allow_type)){
            die("文件类型不允许！");
        }
        $file_save_name = date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
        $info= move_uploaded_file($file_tmp_path, $path_api.'/'.$file_save_name);
        if(isset($_GET['name'])){
            $url=strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/'.$_GET['name'].'/'.date("Ymd").'/'.$file_save_name;
        }else{
            $url=strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/'.date("Ymd").'/'.$file_save_name;
        }
        if($info){
            //图片上传成功后，组好json格式，返回给前端
            $arr   = array(
                'code' => 0,
                'msg'=>'',
                'data' =>array(
                    'src' =>$url
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

        echo json_encode($arr);
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
        if(isset($_GET['name'])){
            $path_api="../../api/web/uploads/".$_GET['name'].'/PDF';
        }else{
            $path_api="../../api/web/uploads/PDF";
        }

        if (!is_dir($path_api)){
            mkdir($path_api,0777,true);
        }
        $type = substr(strrchr($file_name, '.'), 1);
        $allow_type = ['pdf'];
        if(!in_array($type, $allow_type)){
            die("只允许上传pdf文件！");
        }
        $file_save_name = date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
//        $file_save_name =  $file_name.date("YmdHis");
//        $file_save_name=iconv("UTF-8","gb2312", $file_save_name);
        move_uploaded_file($file_tmp_path, $path_api.'/'.$file_save_name);
       // $file_save_name=iconv("gb2312","UTF-8", $file_save_name);
        if(isset($_GET['name'])){
            $url=strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/'.$_GET['name'].'/PDF/'.$file_save_name;
        }else{
            $url=strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/PDF/'.$file_save_name;
        }
        $arr=[
            "code"=>"200",
            "data"=>$url,
        ];
        echo json_encode($arr);
    }
    /*
     * 图标页面
    */
	public function actionIco(){
		return $this->render('ico');
	}
    /*
     * 获取客户端ip
    */
    public function getIp() {
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
        return $ip;
    }
    /*
     * excel导入
     * @author:LHP
     * @time:2020-04-23
     * */
    public function actionImport(){
        if (Yii::$app->request->isPost) {
            $file=$_FILES;
            $file_name = $file['file']['name'];
            $file_tmp_path =$file['file']['tmp_name'];
            $type = substr(strrchr($file_name, '.'), 1);
            $session = Yii::$app->session;
            require '../../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
            require '../../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
            ini_set('memory_limit', '2048M');
            set_time_limit(0);
            $error_row = [];//错误行
            $error_data = [];//错误数据
            $repeat_row = [];//重复行
            if (empty($file_name)) {
                $arr=[
                    'code'=>20003,
                    'msg'=>'请先上传文件',
                ];
                return json_encode($arr);
            }
            $objReader = new \PHPExcel_Reader_Excel5();
            $path_api="../../api/web/uploads/excel/";

            if (!is_dir($path_api)){
                mkdir($path_api,0777,true);
            }
            $file_save_name = date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
            move_uploaded_file($file_tmp_path, $path_api.'/'.$file_save_name);
            $url=$path_api.'/'.$file_save_name;

            $objPHPExcel = $objReader->load($url);
            $objWorksheet = $objPHPExcel->getSheet(0);
            //最大行数，为数字
            $highestRow = $objWorksheet->getHighestRow();
            //最大列数 为字母
            $highestColumn = $objWorksheet->getHighestColumn();
            //将字母变为数字
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

            $tableData = [];
            for($row = 1;$row<=$highestRow;$row++){
                for($col=0;$col< $highestColumnIndex;$col++){
                    $tableData[$row][$col] = $objWorksheet->getCellByColumnAndRow($col,$row)->getValue();
                }
            }
            //将表头去除
            unset($tableData[1]);
            //释放内存
            $PHPExcel = NULL;
            $guan = new GuanGoods();
            $repeat_data=$guan->goodsRepeat();
            foreach ($tableData as $k => &$v) {
                array_values($v);
                array_map('trim', $v);
                $error_flag = false;
                $repeat_flag = false;
                if (!empty($v[0])) {//商品名称
                    if (!in_array($v[0], $repeat_data['typename'])) {
                        $arr=[
                            'code'=>20002,
                            'msg'=>'第' . ($k + 1) . '行商品名称不存在(' . $v[0] . ')',
                        ];
                        return json_encode($arr);
                    } elseif (in_array($v[0], $repeat_data['guangoods'])) {
                        $repeat_flag = true;
                    }else{
                        $v[0] = $repeat_data[$v[0]];
                    }
                } else {
                    $arr=[
                        'code'=>20001,
                        'msg'=>'第' . ($k + 1) . '商品名称为空',
                    ];
                    return json_encode($arr);
                }
                if (empty($v[1])) {
                    $arr=[
                        'code'=>20001,
                        'msg'=>'第' . ($k + 1) . '商品简介为空',
                    ];
                    return json_encode($arr);
                }
                if (empty($v[2])) {
                    $arr=[
                        'code'=>20001,
                        'msg'=>'第' . ($k + 1) . '费用为空',
                    ];
                    return json_encode($arr);
                }elseif (!preg_match("/^[+-]?\d+(\.\d+)?$|^$|^(\d+|\-){7,}$/", $v[2])) {
                    $arr=[
                        'code'=>20002,
                        'msg'=>'第' . ($k + 1) . '费用只能为浮点数或整数',
                    ];
                    return json_encode($arr);
                }
                if (empty($v[3])) {
                    $arr=[
                        'code'=>20001,
                        'msg'=>'第' . ($k + 1) . 'Logo为空',
                    ];
                    return json_encode($arr);
                }
                if (empty($v[5])) {
                    $arr=[
                        'code'=>20001,
                        'msg'=>'第' . ($k + 1) . '状态为空',
                    ];
                    return json_encode($arr);
                }elseif(!in_array($v[5], ['显示','隐藏'])){
                    $arr=[
                        'code'=>20002,
                        'msg'=>'第' . ($k + 1) . '行状态有误（只能为显示|隐藏)',
                    ];
                    return json_encode($arr);
                }else{
                    $v[5]=($v[5]=='显示')?1:2;
                }
                $v[6] = time();
                $v[7] = time();
                if ($error_flag) {//将错误行记录
                    unset($tableData[$k]);
                    if (!in_array(($k + 2), $error_row)) {
                        $error_row[] = $k + 2;
                    }
                    $error_data[] = $v;
                }
                if ($repeat_flag) {//将重复行记录
                    unset($tableData[$k]);
                    $repeat_data[] = $v;
                    if (!in_array(($k + 2), $repeat_row)) {
                        $repeat_row[] = $k + 2;
                    }
                }
                //进度条
                $session->set('rate', round(($k + 1) / ($highestRow - 1), 2));
                session_write_close();//释放锁
            }
            $tableData=array_values($tableData);
            //进度条置空
            $session->remove('rate');
            $error_count = count($error_row);
            $repeat_count = count($repeat_row);
            $success_count = count($tableData);
            //记录错误信息
            if (!empty($tableData)) {
                foreach ($tableData as $key => $value) {
                    $guan->goodsImport($value);
                }
            }
            $msg = '导入成功' . $success_count . '条';
            if ($error_count) {
                $msg .= ',失败' . $error_count . '条';
            }
            if ($repeat_count) {
                $msg .= ',重复' . $repeat_count . '条';
            }
            $arr=[
                'error_cnt'=>$error_count + $repeat_count,
                'code'=>200,
                'msg'=>$msg
            ];
           return json_encode($arr);
        }
    }
    /*
     * 文件上传
     * @time：2020-3-23
     * @author：lhp
     * */
    public function actionUploads()
    {
        $file = $_FILES;
        $file_name = $file['file']['name'];
        $file_tmp_path =$file['file']['tmp_name'];
        $mo = Config::findOne(['name'=>'WEB_SITE_IMAGE_SIZE']);
        $file_size =$file['file']['size'];
        $max_size=$mo->value;
        if($max_size<$file_size){
            $arr=[
                "code"=>"1",
                'msg'=>'文件大小超出！',
                "data"=>''
            ];
            echo json_encode($arr);die;
        }
        if(isset($_GET['name'])){
            $path_api="../../api/web/uploads/".$_GET['name'].'/';
        }else{
            $path_api="../../api/web/uploads/";
        }
        $dir_api = $path_api.date("Ymd");
        if (!is_dir($dir_api)){
            mkdir($dir_api,0777,true);
        }
        $type = substr(strrchr($file_name, '.'), 1);
        $mo = Config::findOne(['name'=>'WEB_SITE_ALLOW_UPLOAD_TYPE']);
        $allow_type = explode(',', $mo->value);
        if(!in_array($type, $allow_type)){
            $arr=[
                "code"=>"1",
                'msg'=>'文件类型不允许！',
                "data"=>''
            ];
            echo json_encode($arr);die;
        }
        $file_save_name =date("YmdHis",time()) . mt_rand(1000, 9999) . '.' . $type;
        move_uploaded_file($file_tmp_path, $dir_api.'/'.$file_save_name);
        if(isset($_GET['name'])){
            $url='http://www.qcapi.com/uploads/'.$_GET['name'].'/'.date("Ymd").'/'.$file_save_name;
        }else{
            $url=strip_tags(Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value).'uploads/'.date("Ymd").'/'.$file_save_name;
        }
        $arr=[
            "code"=>"200",
            "data"=> $url
        ];
        echo json_encode($arr);
    }
    public function actionUpmore(){
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstances($model, 'file');
            $path='uploads/'.date("YmdH",time()).'/';
            if ($file && $model->validate()) {
                if(!file_exists($path)){
                    mkdir($path,0777);
                }
                foreach ($file as $key=> $fl) {
                    $fl->saveAs('uploads/' .$key.time() .$fl->baseName. '.' . $fl->extension);
                }
                Yii::$app->session->setFlash('success','上传成功！');
                return $this->redirect('upmore');
            }
        }

        return $this->render('upmore', ['model' => $model]);
    }
    public function actionValid()
    {
        $Wechat=new WechatController();
        $echoStr = @$_GET["echostr"];
        if (!isset($echoStr)) {
            $Wechat->responseMsg();
        } else {
            $Wechat->checkSignature();
        }
    }
}
