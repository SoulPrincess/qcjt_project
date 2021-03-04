<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/6/2
 * Time: 15:43
 */

namespace qingrui\models;

use yii;
use yii\behaviors\TimestampBehavior;
class QingMess extends \yii\db\ActiveRecord
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
            [['title'], 'required'],
            [['little_title','img', 'content',], 'default'],
            [['type_id','sort','status'], 'integer'],
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
            'title' => '标题',
            'little_title' => '副标题',
            'type_id' => '类别id',
            'type_name' => '类别名称',
            'img'=>'图片',
            'status' => '状态',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'content' => '内容简介',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQingType()
    {
        return $this->hasOne(QingType::className(), ['id' => 'type_id']);
    }
}