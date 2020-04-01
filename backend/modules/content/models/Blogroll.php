<?php

namespace content\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;


/**
 * This is the model class for table "Blogroll".
 *
 */
class Blogroll extends \yii\db\ActiveRecord
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
            [['bl_name','bl_sort'], 'required'],
            [['bl_url'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bl_name' => '友情名称',
            'bl_url' => '友情链接',
            'bl_sort'=>'排序',
        ];
    }

}
