<?php

namespace content\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;


/**
 * This is the model class for table "Company".
 * @property Company $CompanyParent Company parent
 * @property Company[] $Companys Company children
 *
 */
class Company extends \yii\db\ActiveRecord
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
            [['company_name','company_describe','pro_describe','company_allname'], 'required'],
            [['company_pdf','linkman','phone','post','company_logo','service_charge','strict_id','reason','type_id','strict_state','state','check','reason'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => '企业简称',
            'company_allname' => '企业全称',
            'company_logo'=>'企业logo',
            'company_describe' => '企业简介',
            'pro_describe' => '企业产品',
            'linkman' => '联系人',
            'phone' => '联系方式',
            'post' => '职位',
            'type_id' => '企业类型',
            'type_name' => '企业类型名称',
            'state' => '审核',
            'service_charge'=>'服务费',
            'company_pdf'=>'pdf文件',
            'check'=>'自营',
            'strict_state'=>'严选',
            'reason'=>'原因'
        ];
    }

    /**
 * @return \yii\db\ActiveQuery
 */
    public function getCompanyType()
    {
        return $this->hasOne(CompanyType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrictType()
    {
        return $this->hasOne(StrictType::className(), ['id' => 'strict_id']);
    }
}
