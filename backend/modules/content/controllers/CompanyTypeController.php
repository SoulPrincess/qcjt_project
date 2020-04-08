<?php
/**
 * 企业类型
 * User: LHP
 * Date: 2020/3/24
 * Time: 9:54
 */
namespace content\controllers;
use Yii;
use content\models\CompanyType;
use content\models\searchs\CompanyType as CompanyTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rbac\components\Helper;

class CompanyTypeController extends Controller{
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
    * 企业类型列表页
    * @author：lhp
    * @time：2020/3/24
    * */
    public function actionIndex()
    {
        $searchModel = new CompanyTypeSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /*
     * 企业类型视图
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
     * 企业类型新增
     * @author：lhp
     * @time：2020/3/24
     * */
    public function actionCreate()
    {
        $model = new CompanyType();
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            $model->parent_name=$post['CompanyType']['parent_name'];
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
      * 企业类型更新
      * @author：lhp
      * @time：2020/3/24
      * */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->companyTypeParent) {
            $model->parent_name = $model->companyTypeParent->type_name;
        }
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
     * 企业类型删除一条
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
     * 企业类型批量删除
     * @author：lhp
     * @time：2020/3/24
     * */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new CompanyType;
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
     * 企业类型查询
     * @author：lhp
     * @time：2020/3/24
     * */
    protected function findModel($id)
    {
        if (($model = CompanyType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }
}