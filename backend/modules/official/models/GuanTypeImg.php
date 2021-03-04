<?php

namespace official\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class GuanTypeImg extends \yii\db\ActiveRecord
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
            [['file_name'], 'required'],
            [['type_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => '文件名称',
            'type_id' => '类被id',
        ];
    }

    /*类别多图片
   *$param array
   *@time:2020-6-29
   *@author:Lhp
   */
    public function addTypeImg($param=[]){
        $connection=Yii::$app->db;
        $transaction=$connection->beginTransaction();
        try{
            $param['created_at']=time();
            $param['updated_at']=time();
            $connection->createCommand()->insert('t_guan_type_img',$param)->execute();
            $transaction->commit();
            return true;
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
