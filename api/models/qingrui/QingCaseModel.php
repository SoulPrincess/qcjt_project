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

class QingCaseModel extends PublicModel {
    /*
    * 青锐案例列表
    * @author:lhp
    * @time:2020-6-2
    * */
    public function getCaseList($params = [])
    {
        $query = new Query();
        if (!empty($params['page'])) {
            $this->defaultPage = $params['page'];
        }
        if (!empty($params['page_size'])) {
            $this->defaultPageSize = $params['page_size'];
        }
        $params['order_by'] = 'c.updated_at desc';
        $offset             = ($this->defaultPage - 1) * $this->defaultPageSize;
        $query->select(['c.id', 'case_title', 'case_img', 'case_content', 'case_sort', 'case_status', 'created_at', 'updated_at'])
            ->from(['c' => $this->QING_CASE_TABLE])
            ->where(['c.case_status' => 1]);
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
    * 案列详情
    * $where array
    * @author:lhp
    * @time:2020-6-2
    * */
    public function getCaseDetail($where = [])
    {
        $query = new Query();
        $query->select(['c.id', 'case_title', 'case_img', 'case_content', 'case_sort', 'case_status','FROM_UNIXTIME(created_at, \'%Y-%m-%d / %H:%i:%S\') as created_at','FROM_UNIXTIME(updated_at, \'%Y-%m-%d / %H:%i:%S\') as updated_at'])
            ->from(['c' => $this->QING_CASE_TABLE]);
        if ($where) {
            $query->andWhere($where);
        }
        $config_data = $query->one();
        return $config_data;
    }
}