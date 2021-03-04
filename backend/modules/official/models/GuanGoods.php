<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/4/13
 * Time: 10:42
 */

namespace official\models;

use yii;
use yii\behaviors\TimestampBehavior;
class GuanGoods extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id'], 'required'],
           // [['type_id'], 'unique'],
            [['intro', 'content', 'cost','cover_img','contact','phone'], 'default'],
            [['type_id'], 'integer'],
            [['status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => '类别id',
            'type_name' => '类别名称',
            'intro'=>'商品简介',
            'content' => '商品内容',
            'cost' => '费用',
            'cover_img' => 'LOGO',
            'status' => '是否显示',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuanType()
    {
        return $this->hasOne(GuanType::className(), ['id' => 'type_id']);
    }

    /*获取商品属性*/
    public function getguanGoodsAttribute()
    {
        return $this->hasOne(GuanGoodsAttribute::className(), ['id' => 'attr_id']);
    }

    /*
     * 商品属性列表
     * @author:LHP
     * @time:2020-4-15
    */
    public static function attributeList($goods_id=''){

        $data = (new yii\db\Query())->select(['g.id','t.type_name'])
            ->from(['a'=>'t_guan_goods_attribute'])
            ->leftJoin(['g'=>'t_guan_goods'],'a.goods_id=g.id')
            ->leftJoin(['t'=>'t_guan_type'],'t.id=g.type_id')
            ->where(['a.goods_id'=>$goods_id])
            ->all();
        return $data;
    }

    /*官网商品导入查重
     *@author:LHP
     * @time:2020-04-26
     * */
    public function goodsRepeat()
    {
        $query = new yii\db\Query();
        $result = $query->select(['g.id g_id','type_name','t.intro','pid','t.id'])
            ->from(['t'=>'t_guan_type'])
            ->leftJoin(['g'=>'t_guan_goods'],'g.type_id=t.id')
            ->where(['<>','pid',''])
            ->orderby('t.sort asc')
            ->all();
        foreach ($result as $v) {//商品名称
            $result['typename'][] = $v['type_name'];

            if ($v['id']) {
                $result[$v['type_name']] = $v['id'];
            }
            if(!empty($v['g_id'])){
                $result['guangoods'][] = $v['type_name'];
            }
        }
        return $result;
    }

    public function goodsImport($param = [])
    {
        $data=[
            'type_id'=>$param[0],
            'intro'=>$param[1],
            'content'=>$param[4],
            'cost'=>$param[2],
            'status'=>$param[5],
            'cover_img' => $param[3],
            'created_at' =>$param[6],
            'updated_at' =>$param[7]
        ];
        $result=self::addGuanGoods($data);
        return $result;
    }

    /*官网商品导入数据库
   *$param array
   *@time:2020-4-26
   *@author:Lhp
   */
    public function addGuanGoods($param=[]){
        $connection=Yii::$app->db;
        $transaction=$connection->beginTransaction();
        try{
            $connection->createCommand()->insert('t_guan_goods',$param)->execute();
            $transaction->commit();
            return true;
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}