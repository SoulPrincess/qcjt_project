<?php
/**
 * 官网类型
 * User: LHP
 * Date: 2020/4/13
 * Time: 9:54
 */
namespace official\controllers;
use official\models\GuanTypeImg;
use Yii;
use official\models\GuanType;
use official\models\searchs\GuanType as GuanTypeSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rbac\components\Helper;

class GuanTypeController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /*
    * 官网类型列表页
    * @author：lhp
    * @time：2020/4/13
    * */
    public function actionIndex()
    {
        $searchModel = new GuanTypeSearch;
        $query = $searchModel->search(Yii::$app->request->getQueryParams());
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = GuanType::findOne(['id' => $id]);
            $out = Json::encode(['output'=>'', 'message'=>'']);
            $posted = current($_POST['GuanType']);
            $post = ['GuanType' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output = '';
                isset($posted['hot']) && $output = $model->hot;
            }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            echo $out;
            return;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->query,
            'pagination' => false
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /*
     * 官网类型视图
     * @author：lhp
     * @time：2020/4/13
     * */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /*
     * 官网类型新增
     * @author：lhp
     * @time：2020/4/13
     * */
    public function actionCreate()
    {
        $model = new GuanType();
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            $model->parent_name=$post['GuanType']['parent_name'];
            if ($model->load($post) && $model->save()) {
                Helper::invalidate();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /*
      * 官网类型更新
      * @author：lhp
      * @time：2020/4/13
      * */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        if($_POST){
            $post=Yii::$app->request->post('imgs');
            if($imgs=Yii::$app->request->post('imgs')){
                $typeimgmodel=new GuanTypeImg();
                foreach($imgs as $k=>$v){
                    $parmes['file_name']=$v;
                    $parmes['type_id']=$id;
                    $typeimgmodel->addTypeImg($parmes);
                }
            }
        }
        if (Yii::$app->request->post('hasEditable')) {
            $out = Json::encode(['output'=>'', 'message'=>'']);
            $posted = $_POST['GuanType'];
            $post = ['GuanType' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output = '';
                isset($posted['hot']) && $output = $model->hot;
                isset($posted['home_status']) && $output = $model->home_status;
            }
            $out = Json::encode(['output'=>$output==2?'<font color="red">否</font>':'<font color="green">是</font>', 'message'=>'']);
            return $out;
        }
        if ($model->guanTypeParent) {
            $model->parent_name = $model->guanTypeParent->type_name;
        }
        if($post_data=Yii::$app->request->post()){
            $model->parent_name=$post_data['GuanType']['parent_name'];
            if ($model->load($post_data) && $model->save()) {
                Helper::invalidate();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
         else {
             $searchModel = new GuanTypeSearch;
            $imgmodel= $searchModel->GuanTypeImg(['id'=>$id]);
            return $this->render('update', [
                'model' => $model,
                'imgmodel' => $imgmodel,
            ]);
        }
    }
    /*
      * 教育经历
      * @author：lhp
      * @time：2020/6/17
      * */
    public function actionResumeUpdate($id)
    {
        $model = GuanTypeImg::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->resume_id]);
        }
    }
    /*
     * 官网类型删除一条
     * @author：lhp
     * @time：2020/4/13
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
     * 官网类型批量删除
     * @author：lhp
     * @time：2020/4/13
     * */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new GuanType;
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
     * 官网类型查询
     * @author：lhp
     * @time：2020/4/13
     * */
    protected function findModel($id)
    {
        if (($model = GuanType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }

    /*
      * 官网类别状态
      * @author：lhp
      * @time：2020/5/22
      * */
    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        if($post= Yii::$app->request->post()){
            $model->status=$post['status'];
            if($model->save()){
                $arr=[
                    'code'=>200,
                    'msg'=>'操作成功'
                ];
                return json_encode($arr);
            }else{
                $arr=[
                    'code'=>-1,
                    'msg'=>'操作失败'
                ];
                return json_encode($arr);
            }
        } else {
            $arr=[
                'code'=>-1,
                'msg'=>'操作失败'
            ];
            return json_encode($arr);
        }
    }
}