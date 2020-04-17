<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/4/15
 * Time: 10:42
 */

namespace official\models;

use yii;
use yii\behaviors\TimestampBehavior;
class GuanProduct extends \yii\db\ActiveRecord
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
            [['pro_name'], 'required'],
            [['pro_name'], 'unique'],
            [['pro_intro', 'nav_id', 'type_id','content','cover_img'], 'default'],
            [['sort'], 'integer'],
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
            'pro_name' => '产品名称',
            'pro_intro' => '产品简介',
            'nav_id'=>'导航id',
            'nav_name'=>'导航名称',
            'type_id' => '类别id',
            'type_name' => '类别名称',
            'content' => '产品内容',
            'cover_img' => '封面图',
            'status' => '是否显示',
            'sort' => '排序',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuanProductType()
    {
        return $this->hasOne(GuanProductType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuanNavigation()
    {
        return $this->hasOne(GuanNavigation::className(), ['id' => 'nav_id']);
    }
}