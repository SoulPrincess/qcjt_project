<?php
namespace api\models\official;
use api\models\PublicModel;
use yii\db\Query;

/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/3/26
 * Time: 11:17
 */

class GuanNavigationModel extends PublicModel {
    /*
    * 官网导航列表
    * @author:lhp
    * @time:2020-4-13
    * */
    public function getNavigationList(){
        $query=new Query();
        $data=$query->select(['id','n_name','pid','url'])
            ->from([$this->GUAN_NAVIGATION_TABLE])
            ->orderby('sort asc')
            ->all();
       $arr_data= $this->recursion($data,0);
       return $arr_data;
    }
}