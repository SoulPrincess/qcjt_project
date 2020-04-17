<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/4/13
 * Time: 10:39
 */

namespace official\controllers;

use rbac\components\Helper;
use yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use official\models\GuanGoods;
use official\models\searchs\GuanGoods as GuanGoodsSearch;

class GuanGoodsController extends Controller
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
     * 商品列表页
     * @author：lhp
     * @time：2020/4/10
     * */
    public function actionIndex()
    {
        $searchModel = new GuanGoodsSearch;
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
        $model = new GuanGoods;
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
      * 导航更新
      * @author：lhp
      * @time：2020/3/23
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
            $model = new GuanGoods;
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
        if (($model = GuanGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在!');
        }
    }

    /*
       * 商品状态
       * @author：lhp
       * @time：2020/4/13
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

    public function actionAttribute($goods_id=''){
        $model = GuanGoods::attributeList($goods_id);
        print_r($model);die;
    }
}