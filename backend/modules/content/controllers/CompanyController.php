<?php
/**
 * 企业信息
 * User: LHP
 * Date: 2020/3/24
 * Time: 11:20
 */
namespace content\controllers;
use content\models\Company;
use rbac\components\Helper;
use yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use content\models\searchs\company as CompanySearch;
use yii\web\NotFoundHttpException;
class CompanyController extends Controller{
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
}