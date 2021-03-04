<?php
namespace api\controllers;
header('Access-Control-Allow-Headers:x-requested-with,content-type');
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/3/26
 * Time: 11:15
 */
use yii;
use api\models\CompanyModel;
class CompanyController extends PublicController{
    public function behaviors()
    {
        return [
            'limit' => [//接口限制过滤器
                'class' => 'api\filters\LimitFilter',
            ],
        ];
    }
    /*
    * 严选企业信息
    * @time:2020-3-26
    * @author:Lhp
   */
    public function actionIndex(){
        $type=new CompanyModel();
        $data=$type->getCompanysDetail();
        return $this->result($data,'200','成功');
    }
    /*
       * 关于我们
       * @time:2020-4-9
       * @author:Lhp
      */
    public function actionAbout(){
        $type=new CompanyModel();
        $data=$type->getCompanyDetail();
        return $this->result($data,'200','成功');
    }
    /*
    * 企业详情
    * @time:2020-3-26
    * @author:Lhp
   */
    public function actionCompanyDetail(){

        $companymodel= new CompanyModel();
        $json = $this->get_json();
        $id = $this->verifyEmpty($json, 'id');
        if($id){
            $data= $companymodel->getCompanyOneDetail(['c.id'=>$id]);
            return $this->result($data,'200','成功');
        }else{
            return $this->result([],'201','参数不能为空');
        }
    }

    /*
   * 企业入住信息
   * @time:2020-3-26
   * @author:Lhp
  */
    public function actionCompanyAdd(){
        $companymodel= new CompanyModel();
        $json = $this->get_json();
        if($this->is_post()){
            $data['company_name'] =$this->verifyEmpty($json,'company_name');//企业简称
            $data['company_allname'] =$this->verifyEmpty($json,'company_allname');//企业全称
            $data['company_url'] =$this->verifyEmpty($json,'company_url');//企业url
            $data['company_describe'] =$this->verifyEmpty($json,'company_describe');//企业简介
            $data['company_logo'] =$this->verifyEmpty($json,'company_logo');//企业logo
            $data['pro_describe'] =$this->verifyEmpty($json,'pro_describe');//具体产品
            $data['linkman'] =$this->verifyEmpty($json,'linkman');//企业联系人
            $data['phone'] =$this->verifyEmpty($json,'phone');//手机号
            $data['post'] =$this->verifyEmpty($json,'post');//职位
            $data['service_charge'] =$this->verifyEmpty($json,'service_charge');//服务费
            $data['type_id'] =$this->verifyEmpty($json,'type_id');//企业联系人
            $data['company_pdf'] =$this->verifyEmpty($json,'company_pdf','');//pdf文件
            $datas= $companymodel->addCompany($data);
            return $this->result($datas,'200','成功');
        }else{
           $alltype= $companymodel->getAllType();
            return $this->result([
                'data'=>$alltype
            ]);
        }
    }

    /*
    * 类别企业/搜索
    * @time:2020/04/07
    * @author:Lhp
    */
    public function actionCompanySearch(){
            $json = $this->get_json();
            $strict_id =$this->verifyEmpty($json,'type_id');//严选类别id
            $search_key =$this->verifyEmpty($json,'search_key');//搜索关键字
            $pagination = $json['pagination'];
            $params['page'] = $pagination['page'];
            $params['page_size'] = $pagination['page_size'];
            $params['type_id']=$strict_id;
            $params['search_key']=$search_key;
            $type=new CompanyModel();
            $data=$type->getCompanysSearch($params);
            return $this->result($data,'200','成功');
    }
}