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
            [['intro', 'content', 'cost','cover_img'], 'default'],
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
}