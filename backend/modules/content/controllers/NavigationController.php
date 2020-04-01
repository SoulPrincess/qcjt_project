<?php

namespace content\controllers;

use Yii;
use content\models\navigation;
use content\models\searchs\Navigation as NavigationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rbac\components\Helper;

/**
 *@前端导航栏
 *@time:2020-3-20
 */
class NavigationController extends Controller
{

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
     * 导航列表页
     * @author：lhp
     * @time：2020/3/23
     * */
    public function actionIndex()
    {
        $searchModel = new NavigationSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /*
     * 导航视图
     * @author：lhp
     * @time：2020/3/23
     * */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /*
        * 导航新增
         * @author：lhp
         * @time：2020/3/23
         * */
    public function actionCreate()
    {
        $model = new Navigation;
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            $model->parent_name=$post['Navigation']['parent_name'];
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
      * 导航更新
      * @author：lhp
      * @time：2020/3/23
      * */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->navigationParent) {
            $model->parent_name = $model->navigationParent->n_name;
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
     * 导航删除一条
     * @author：lhp
     * @time：2020/3/23
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
     * 导航批量删除
     * @author：lhp
     * @time：2020/3/23
     * */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new Navigation;
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
     * 导航查询
     * @author：lhp
     * @time：2020/3/23
     * */
    protected function findModel($id)
    {
        if (($model = Navigation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }
}
