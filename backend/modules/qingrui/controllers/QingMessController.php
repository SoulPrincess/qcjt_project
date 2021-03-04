<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/6/2
 * Time: 15:43
 */

namespace qingrui\controllers;


use rbac\components\Helper;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use qingrui\models\QingMess;
use qingrui\models\searchs\QingMess as QingMessSearch;
class QingMessController extends Controller
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
     * 青锐信息列表页
     * @author：lhp
     * @time：2020/6/2
     * */
    public function actionIndex()
    {
        $searchModel = new QingMessSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /*
     * 青锐信息视图
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
        * 青锐信息新增
         * @author：lhp
         * @time：2020/6/2
         * */
    public function actionCreate()
    {
        $model = new QingMess;
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
      * 青锐信息更新
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
     * 青锐信息删除一条
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
     * 青锐信息批量删除
     * @author：lhp
     * @time：2020/6/2
     * */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new  QingMess;
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
     * 青锐信息查询
     * @author：lhp
     * @time：2020/6/2
     * */
    protected function findModel($id)
    {
        if (($model=QingMess::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }

    /*
       * 青锐信息状态
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