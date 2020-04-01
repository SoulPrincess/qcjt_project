<?php

namespace content\models;

use Yii;
use content\components\Configs;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;


/**
 * This is the model class for table "navigation".
 *
 * @property integer $id navigation id(autoincrement)
 * @property string $name navigation name
 * @property integer $parent navigation parent
 * @property string $route Route for this navigation
 * @property integer $order navigation order
 * @property string $icon Extra information for this navigation
 *
 * @property navigation $navigationParent navigation parent
 * @property navigation[] $navigations navigation children
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Navigation extends \yii\db\ActiveRecord
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
    public static function tableName()
    {
        return Configs::instance()->navigationTable;
    }

    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        if (Configs::instance()->db !== null) {
            return Configs::instance()->db;
        } else {
            return pid::getDb();
        }
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['n_name'], 'required'],
            [['n_name'], 'unique'],
            [['parent_name'], 'in',
                'range' => static::find()->select(['n_name'])->column(),
                'message' => '该父级名称不存在！'],
            [['pid', 'url', 'sort'], 'default'],
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
            ->where('[[n_name]]=:parent_name');
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
            'n_name' => '导航名称',
            'pid'=>'父级id',
            'parent_name' => '父级名称',
            'url' => '路由',
            'sort' => '排序',
        ];
    }

    /**
     * Get navigation parent
     * @return \yii\db\ActiveQuery
     */
    public function getNavigationParent()
    {
        return $this->hasOne(Navigation::className(), ['id' => 'pid']);
    }

    /**
     * Get navigation children
     * @return \yii\db\ActiveQuery
     */
    public function getNavigations()
    {
        return $this->hasMany(Navigation::className(), ['pid' => 'id']);
    }

}
