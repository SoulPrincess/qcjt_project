<?php

namespace official\models;
/**
 * Created by PhpStorm.
 * User: LHP
 * Date: 2020/4/15
 * Time: 11:05
 */

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

class GuanProductType extends \yii\db\ActiveRecord
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
            [['type_name'], 'required'],
           // [['type_name'], 'unique'],
            [['sort','intro'], 'default'],
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
            'type_name' => '类型名称',
            'sort' => '排序',
            'intro' => '简介',
        ];
    }

    /**
     * 获取类别列表
     * @time:2020-4-15
     * @author:lhp
     */
    public static function dropTypeDown(){

        $data = self::find()
            ->orderBy('sort asc')
            ->asArray()
            ->all();
        $data_list = ArrayHelper::map($data, 'id', 'type_name');
        return $data_list;
    }
}
