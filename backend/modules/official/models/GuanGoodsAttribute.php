<?php

namespace official\models;

use Yii;
use content\components\Configs;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class GuanGoodsAttribute extends \yii\db\ActiveRecord
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
    public static function tableName()
    {
        return 't_guan_goods_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id','attribute'], 'required'],
            [['cost','sort'], 'default'],
            [['sort'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'goods_name' => '商品名称',
            'attribute'=>'属性名称',
            'cost' => '价格',
            'sort' => '排序',
        ];
    }

    /**
     * 获取商品
     * @time:2020-4-13
     * @author:lhp
     */
    public static function dropDown(){
        $data = (new Query())->select(['g.id','t.type_name'])
            ->from(['g'=>'t_guan_goods'])
            ->leftJoin(['t'=>'t_guan_type'],'t.id=g.type_id')
            ->orderBy('g.created_at asc')
            ->all();
        $data_list = ArrayHelper::map($data, 'id', 'type_name');
        return $data_list;
    }

    public static function getGuanTypes($goods_id)
    {
        $data = (new Query())->select(['g.id','t.type_name'])
            ->from(['a'=>'t_guan_goods_attribute'])
            ->leftJoin(['g'=>'t_guan_goods'],'a.goods_id=g.id')
            ->leftJoin(['t'=>'t_guan_type'],'t.id=g.type_id')
            ->where(['a.goods_id'=>$goods_id])
            ->one();
        return $data;
    }

}
