<?php
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/6/2
 * Time: 10:42
 */

namespace qingrui\models;

use yii;
use yii\behaviors\TimestampBehavior;
class QingCase extends \yii\db\ActiveRecord
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
            [['case_title'], 'required'],
            [['case_img', 'case_content',], 'default'],
            [['case_sort','case_status'], 'integer'],
            [['case_status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'case_title' => '案例标题',
            'case_img' => '案例图',
            'case_content'=>'案例简介',
            'case_status' => '状态',
            'case_sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}