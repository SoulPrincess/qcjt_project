<?php

namespace qingrui\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class QingType extends \yii\db\ActiveRecord
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
            [['sort','status'], 'integer'],
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
            'status'=>'状态',
        ];
    }
    /**
     * Get QingType
     * @return \yii\db\ActiveQuery
     */
    public function getQingTypes()
    {
        return $this->hasMany(QingType::className(), ['pid' => 'id']);
    }
    /**
     * 获取类别
     * @time:2020-6-2
     * @author:lhp
     */
    public static function dropDown()
    {
        $data = self::find()
            ->where(['status'=>1])
            ->orderBy('sort desc')
            ->asArray()
            ->all();
        $data_list = ArrayHelper::map($data, 'id', 'type_name');
        return $data_list;
    }
}
