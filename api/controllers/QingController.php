<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/6/2
 * Time: 13:49
 */

namespace api\controllers;


use api\models\qingrui\QingCaseModel;
use api\models\qingrui\QingMessModel;
use Yii;
class QingController extends PublicController
{
    public function behaviors()
    {
        return [
            'limit' => [//接口限制过滤器
                'class' => 'api\filters\LimitFilter',
            ],
        ];
    }
    /*
      * 青锐国际案例列表
      * @time:2020-6-2
      * @author:Lhp
     */
    public function actionCaseIndex(){
        $json = $this->get_json();
        $pagination = $json['pagination'];
        $params['page'] = $pagination['page'];
        $params['page_size'] = $pagination['page_size'];
        $qingmodel= new QingCaseModel();
        $data=$qingmodel->getCaseList($params);
        return $this->result($data,'200','成功');
    }

    /*
      * 青锐国际案例详情
      * @time:2020-6-2
      * @author:Lhp
     */
    public function actionCaseDetail(){
        $qingmodel= new QingCaseModel();
        $request = Yii::$app->request;
        $id =$request->get('id');
        if($id){
            $data= $qingmodel->getCaseDetail(['c.id'=>$id]);
            return $this->result($data,'200','成功');
        }else{
            return $this->result([],'201','参数不能为空');
        }
    }

    /*
   * 青锐国际信息列表
   * @time:2020-6-2
   * @author:Lhp
  */
    public function actionMessIndex(){
        $json = $this->get_json();
        $pagination = $json['pagination'];
        $params['type_id'] = $json['type_id'];
        $params['page'] = $pagination['page'];
        $params['page_size'] = $pagination['page_size'];
        $qingmodel= new QingMessModel();
        $data=$qingmodel->getMessList($params);
        return $this->result($data,'200','成功');
    }
    /*
     * 青锐国际信息详情
     * @time:2020-6-2
     * @author:Lhp
     */
    public function actionMessDetail(){
        $qingmodel= new QingMessModel();
        $request = Yii::$app->request;
        $id =$request->get('id');
        if($id){
            $data= $qingmodel->getMessDetail(['c.id'=>$id]);
            return $this->result($data,'200','成功');
        }else{
            return $this->result([],'201','参数不能为空');
        }
    }
    /*
     * 青锐国际类别列表
     * @time:2020-6-2
     * @author:Lhp
     */
    public function actionTypeIndex(){
        $qingmodel= new QingMessModel();
        $data=$qingmodel->getTypeList();
        return $this->result($data,'200','成功');
    }
}