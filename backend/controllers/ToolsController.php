<?php
namespace backend\controllers;

use yii\web\Controller;
use common\models\Config;
use yii;
class ToolsController extends Controller
{
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
        echo Config::findOne(['name'=>'WEB_SITE_RESOURCES_URL'])->value . date('Ymd').'/'.$file_save_name;
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
      //  $path="../web/uploads/";
        $path_api="../../api/web/uploads/";
       // $dir = $path.date("Ymd");
//        if (!is_dir($dir)){
//            mkdir($dir,0777);
//        }
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
       // copy($dir.'/'.$file_save_name,$dir_api.'/'.$file_save_name);
      // ;
        $arr=[
            "code"=>"200",
            "data"=> Yii::$app->params['API_PATH'].'uploads/'.date("Ymd").'/'.$file_save_name
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
       // $dir = "../web/uploads/".date("Ymd");
        $path_api="../../api/web/uploads/".date("Ymd");
//        if (!is_dir($dir)){
//            mkdir($dir,0777,true);
//        }
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
       // copy($dir.'/'.$file_save_name,$path_api.'/'.$file_save_name);
        if($info){
            //图片上传成功后，组好json格式，返回给前端
            $arr   = array(
                'code' => 0,
                'msg'=>'',
                'data' =>array(
                    'src' =>Yii::$app->params['API_PATH'].'uploads/'.date("Ymd").'/'.$file_save_name
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
       // $dir = "../web/uploads/".date("Ymd");
        $path_api="../../api/web/uploads/".date("Ymd");
//        if (!is_dir($dir)){
//            mkdir($dir,0777);
//        }
        if (!is_dir($path_api)){
            mkdir($path_api,0777,true);
        }
        $type = substr(strrchr($file_name, '.'), 1);
        $allow_type = ['pdf'];
        if(!in_array($type, $allow_type)){
            die("只允许上传pdf文件！");
        }
        $file_save_name =  $file_name;
        move_uploaded_file($file_tmp_path, $path_api.'/'.$file_save_name);
       // copy($dir.'/'.$file_save_name,$path_api.'/'.$file_save_name);
        $arr=[
            "code"=>"200",
            "data"=>Yii::$app->params['API_PATH'].'uploads/'. date('Ymd').'/'.$file_save_name,
        ];
        echo json_encode($arr);
    }
    /*
     * 图标页面
    */
	public function actionIco(){
		return $this->render('ico');
	}
}
