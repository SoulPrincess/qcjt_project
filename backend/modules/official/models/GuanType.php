<?php

namespace official\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "GuanType".
 *
 * @property integer $id GuanType id(autoincrement)
 * @property string $type_name GuanType type_name
 * @property integer $pid GuanType pid
 * @property integer $sort GuanType sort
 *
 * @property GuanType $GuanTypeParent GuanType parent
 * @property GuanType[] $GuanTypes GuanType children
 *
 */
class GuanType extends \yii\db\ActiveRecord
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
            [['type_name'], 'required'],
           // [['type_name'], 'unique'],
            [['parent_name'], 'in',
                'range' => static::find()->select(['type_name'])->column(),
                'message' => '该父级名称不存在！'],
            [['pid','sort','intro'], 'default'],
            [['parent_name'], 'filterParent'],
            [['sort'], 'integer'],
        ];
    }

    /**
     * Use to loop detected.
     */
    public function filterParent()
    {
        $parent_name = $this->parent_name;
        $db = static::getDb();
        $query = (new Query)->select(['id'])
            ->from(static::tableName())
            ->where('[[type_name]]=:parent_name');
        if (empty($parent_name)) {
            $this->addError('parent_name', '不存在！');
            return;
        }
        $parent = $query->params([':parent_name' => $parent_name])->scalar($db);
        $this->pid=$parent;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_name' => '类型名称',
            'pid'=>'父级id',
            'parent_name' => '父级名称',
            'sort' => '排序',
            'intro' => '简介',
        ];
    }

    /**
     * Get GuanType parent
     * @return \yii\db\ActiveQuery
     */
    public function getGuanTypeParent()
    {
        return $this->hasOne(GuanType::className(), ['id' => 'pid']);
    }

    /**
     * Get GuanType children
     * @return \yii\db\ActiveQuery
     */
    public function getGuanTypes()
    {
        return $this->hasMany(GuanType::className(), ['pid' => 'id']);
    }

    /**
     * 获取类别二级菜单
     * @time:2020-4-13
     * @author:lhp
     */
    public static function dropDown(){

        $data = self::find()
            ->where(['<>','pid',0])
            ->orderBy('pid asc')
            ->asArray()
            ->all();
        $data_list = ArrayHelper::map($data, 'id', 'type_name');
        return $data_list;
    }
}
