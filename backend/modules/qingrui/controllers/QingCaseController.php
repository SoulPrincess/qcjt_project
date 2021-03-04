<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/6/2
 * Time: 10:39
 */

namespace qingrui\controllers;

use common\models\Config;
use kucha\ueditor\UEditorAction;
use rbac\components\Helper;
use yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use qingrui\models\QingCase;
use qingrui\models\searchs\QingCase as QingCaseSearch;

class QingCaseController extends Controller
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
     * 青锐案例列表页
     * @author：lhp
     * @time：2020/6/2
     * */
    public function actionIndex()
    {
        $searchModel = new QingCaseSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /*
     * 青锐案例视图
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
        * 青锐案例新增
         * @author：lhp
         * @time：2020/6/2
         * */
    public function actionCreate()
    {
        $model = new QingCase;
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
      * 青锐案例更新
      * @author：lhp
      * @time：2020/6/2
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
     * 青锐案例删除一条
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
     * 青锐案例批量删除
     * @author：lhp
     * @time：2020/6/2
     * */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new  QingCase;
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
     * 青锐案例查询
     * @author：lhp
     * @time：2020/6/2
     * */
    protected function findModel($id)
    {
        if (($model=QingCase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }

    /*
       * 青锐案例状态
       * @author：lhp
       * @time：2020/6/2
       * */
    public function actionStatus($id)
    {
        $model = $this->findModel($id);
      if($post= Yii::$app->request->post()){
          $model->case_status=$post['status'];
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