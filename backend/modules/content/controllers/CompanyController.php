<?php
/**
 * 企业信息
 * User: LHP
 * Date: 2020/3/24
 * Time: 11:20
 */
namespace content\controllers;
use content\models\Company;
use content\models\Test;
use rbac\components\Helper;
use rbac\controllers\WechatController;
use yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use content\models\searchs\Company as CompanySearch;
use yii\web\NotFoundHttpException;
class CompanyController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'limit' => [//接口限制过滤器
                'class' => 'api\filters\LimitFilter',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /*
    * 企业信息列表页
    * @author：lhp
    * @time：2020/3/24
    * */
    public function actionIndex()
    {
        $searchModel = new CompanySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination->defaultPageSize =10;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /*
     * 企业信息视图
     * @author：lhp
     * @time：2020/3/24
     * */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /*
     * 企业信息新增
     * @author：lhp
     * @time：2020/3/24
     * */
    public function actionCreate()
    {
        $model = new Company();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        }else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /*
      * 企业信息更新
      * @author：lhp
      * @time：2020/3/24
      * */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /*
     * 企业信息删除一条
     * @author：lhp
     * @time：2020/3/24
     * */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->delete()){
            Helper::invalidate();
            return json_encode(['code'=>200,"msg"=>"删除成功"]);
        }else{
            $errors = $model->firstErrors;
            return json_encode(['code'=>400,"msg"=>reset($errors)]);
        }
    }
    /*
     * 企业信息批量删除
     * @author：lhp
     * @time：2020/3/24
     * */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new Company;
            $count = $model->deleteAll(["in","id",$data['keys']]);
            if($count>0){
                Helper::invalidate();
                return json_encode(['code'=>200,"msg"=>"删除成功"]);
            }else{
                $errors = $model->firstErrors;
                return json_encode(['code'=>400,"msg"=>reset($errors)]);
            }
        }else{
            return json_encode(['code'=>400,"msg"=>"请选择数据"]);
        }
    }
    /*
     * 是否严选设置
     * @time:2020-3-24
     * @author:Lhp
      */
    public function actionActive($id)
    {
        $model = $this->findModel($id);
        if($model->state==2){
            $model->state=1;
        }else{
            $model->state=2;
        }
        if($model->save()){
            return json_encode(['code'=>200,"msg"=>"操作成功"]);
        }else{
            $errors = $model->firstErrors;
            return json_encode(['code'=>400,"msg"=>reset($errors)]);
        }
    }

    /*
     * 企业信息查询
     * @author：lhp
     * @time：2020/3/24
     * */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }
    /*
         * 企业信息更新
         * @author：lhp
         * @time：2020/3/24
         * */
    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('status', [
                'model' => $model,
            ]);
        }
    }
    public function actionGetcode(){
        $scene_str = $_GET['timestamp_version'];
        $wxservice=new WechatController();
        $result = json_decode($wxservice->getQrcodeByStr($scene_str), true);
        if(isset($result['ticket'])){
            return json_encode(['code'=>200,"msg"=>$result['ticket']]);
        }else{
            return json_encode(['code'=>500,"msg"=>'失败重新获取']);
        }
    }
    public function actionCheckLogin(){
        if(isset($_GET['scene_str']) && $_GET['scene_str']){
            $arr=Test::find()->select('id')->where(['scene_str'=>$_POST['scene_str']])->one();
            if($arr){
                return json_encode(['code'=>200,"msg"=>'login']);
            }else{
                return json_encode(['code'=>100,"msg"=>'no login']);
            }
        }else{
            return json_encode(['code'=>-1,"msg"=>'网络错误']);
        }
    }
    public function actionCallBack(){
        $WeChat = new WechatController();
        $result = $WeChat->callback();
        print_r($result);die;
    }
    public function actionServerCheck(){
        $WeChat = new WechatController();
        $result = $WeChat->bindServerCheck('weixin');
        print_r($result);die;
    }
}