<?php
/**
 * 官网类型
 * User: LHP
 * Date: 2020/4/13
 * Time: 9:54
 */
namespace official\controllers;
use Yii;
use official\models\GuanType;
use official\models\searchs\GuanType as GuanTypeSearch;
use yii\data\ActiveDataProvider;
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
        $query = GuanType::find()->orderBy('sort asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        if ($model->guanTypeParent) {
            $model->parent_name = $model->guanTypeParent->type_name;
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
}