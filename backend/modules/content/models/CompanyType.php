<?php

namespace content\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "CompanyType".
 *
 * @property integer $id CompanyType id(autoincrement)
 * @property string $type_name CompanyType type_name
 * @property integer $pid CompanyType pid
 * @property integer $sort CompanyType sort
 *
 * @property CompanyType $CompanyTypeParent CompanyType parent
 * @property CompanyType[] $CompanyTypes CompanyType children
 *
 */
class CompanyType extends \yii\db\ActiveRecord
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
            [['type_name'], 'unique'],
            [['parent_name'], 'in',
                'range' => static::find()->select(['type_name'])->column(),
                'message' => '该父级名称不存在！'],
            [['pid',  'sort'], 'default'],
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
        ];
    }

    /**
     * Get CompanyType parent
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyTypeParent()
    {
        return $this->hasOne(CompanyType::className(), ['id' => 'pid']);
    }

    /**
     * Get CompanyType children
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyTypes()
    {
        return $this->hasMany(CompanyType::className(), ['pid' => 'id']);
    }

    /**
     * 获取类别父级菜单
     * @time:2020-3-24
     * @author:lhp
     */
    public static function dropDown()
    {
        $data = self::find()->where(['pid'=>null])->asArray()->all();
        $data_list = ArrayHelper::map($data, 'id', 'type_name');
        return $data_list;
    }
}
