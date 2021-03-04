<?php
namespace api\models\qingrui;
use api\models\PublicModel;
use yii\db\Query;
use yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/6/2
 * Time: 13:50
 */

class QingMessModel extends PublicModel {
    /*
    * 青锐信息列表
    * @author:lhp
    * @time:2020-6-2
    * */
    public function getMessList($params = [])
    {
        $query = new Query();
        if (!empty($params['page'])) {
            $this->defaultPage = $params['page'];
        }
        if (!empty($params['page_size'])) {
            $this->defaultPageSize = $params['page_size'];
        }
        $params['order_by'] = 'c.sort desc';
        $offset             = ($this->defaultPage - 1) * $this->defaultPageSize;
        $query->select(['c.id', 'title', 'type_id', 'little_title', 'img', 'content', 'c.created_at', 'c.updated_at','type_name'])
            ->from(['c' => $this->QING_MESS_TABLE])
            ->leftjoin(['t' => $this->QING_TYPE_TABLE],'c.type_id=t.id')
            ->where(['c.status' => 1]);
        //按条件搜索
        if (!empty($params['type_id'])){
            $query->andWhere(['c.type_id'=>$params['type_id']]);
        }
        $countQuery = clone $query;
        $result     = $query
            ->offset($offset)
            ->limit($this->defaultPageSize)
            ->orderBy($params['order_by'])
            ->all();
        //总数
        $count      = intval($countQuery->count());
        $pages      = new Pagination(['totalCount' => $count, 'pageSize' => $this->defaultPageSize]);
        $page_count = $pages->getPageCount();
        $page_size  = $pages->getPageSize();
        $pagination = ['page' => $this->defaultPage, 'page_count' => $page_count, 'page_size' => $page_size, 'count' => $count];
        return ['data' => $result, 'pagination' => $pagination];
    }
    /*
    * 信息详情
    * $where array
    * @author:lhp
    * @time:2020-6-2
    * */
    public function getMessDetail($where = [])
    {
        $query = new Query();
        $query->select(['c.id', 'title', 'type_id', 'little_title', 'img', 'content','FROM_UNIXTIME(c.created_at, \'%Y-%m-%d / %H:%i:%S\') as created_at','FROM_UNIXTIME(c.updated_at, \'%Y-%m-%d / %H:%i:%S\') as updated_at','type_name'])
            ->from(['c' => $this->QING_MESS_TABLE])
            ->leftjoin(['t' => $this->QING_TYPE_TABLE],'c.type_id=t.id')
            ->where(['c.status' => 1]);
        if ($where) {
            $query->andWhere($where);
        }
        $config_data = $query->one();
        return $config_data;
    }

    /**
     * 获取类别
     * @time:2020-6-2
     * @author:lhp
     */
    public function getTypeList()
    {
        $data = (new Query())
            ->select(['type_name','id'])
            ->from($this->QING_TYPE_TABLE)
            ->where(['status'=>1])
            ->orderBy('sort desc')
            ->all();
        //$data_list = ArrayHelper::map($data, 'id', 'type_name');
        return $data;
    }
}