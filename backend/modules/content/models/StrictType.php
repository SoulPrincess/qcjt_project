<?php

namespace content\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "StrictType".
 *
 * @property integer $id StrictType id(autoincrement)
 * @property string $type_name StrictType type_name
 * @property integer $pid StrictType pid
 * @property integer $sort StrictType sort
 *
 * @property StrictType $StrictTypeParent StrictType parent
 * @property StrictType[] $StrictTypes StrictType children
 *
 */
class StrictType extends \yii\db\ActiveRecord
{
    public $parent_name;

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
            [['name'], 'required'],
            [['name'], 'unique'],
            [['sort','state'], 'default'],
            [['sort','state'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '严选类别名称',
            'sort' => '排序',
            'state' => '状态',
        ];
    }

    /**
     * 获取列表
     * @time:2020-3-30
     * @author:lhp
     */
    public static function dropDown()
    {
        $data = self::find()->where(['state'=>1])->asArray()->all();
        $data_list = ArrayHelper::map($data, 'id', 'name');
        return $data_list;
    }
}
