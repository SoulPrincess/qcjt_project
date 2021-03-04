<?php
/**
 * User: LHP
 * Date: 2020/6/2
 * Time: 14:33
 */
namespace qingrui\controllers;
use Yii;
use qingrui\models\QingType;
use qingrui\models\searchs\QingType as QingTypeSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rbac\components\Helper;

class QingTypeController extends Controller{
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
    * 青锐类型列表页
    * @author：lhp
    * @time：2020/6/2
    * */
    public function actionIndex()
    {
        $searchModel = new QingTypeSearch;
        $query = $searchModel->search(Yii::$app->request->getQueryParams());
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
     * 青锐类型视图
     * @author：lhp
     * @time：2020/6/2
     * */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /*
     * 青锐类型新增
     * @author：lhp
     * @time：2020/6/2
     * */
    public function actionCreate()
    {
        $model = new QingType();
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
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
      * 青锐类型更新
      * @author：lhp
      * @time：2020/6/2
      * */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($post_data=Yii::$app->request->post()){
            if ($model->load($post_data) && $model->save()) {
                Helper::invalidate();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
         else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /*
     * 青锐类型删除一条
     * @author：lhp
     * @time：2020/6/2
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
     * 青锐类型批量删除
     * @author：lhp
     * @time：2020/6/2
     * */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new QingType();
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
     * 青锐类型查询
     * @author：lhp
     * @time：2020/6/2
     * */
    protected function findModel($id)
    {
        if (($model = QingType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }

    /*
      * 青锐类别状态
      * @author：lhp
      * @time：2020/6/2
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