<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;
use yii\helpers\Html;
LayuiAsset::register($this);
?>
<div class="company-view">
    <?=DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'company_name',
            'company_allname',
            'companyType.type_name:text:类型名称',
            'strictType.name:text:严选类别',
            [
                'attribute' => 'company_logo',
                'contentOptions' => ['style'=> 'text-align: center;'],
                'headerOptions' => ['width' => '50px','style'=> 'text-align: center;'],
                "format"=>[
                    "image",
                    [
                        "width"=>"30px",
                        "height"=>"30px",
                    ],
                ],
            ],
            'pro_describe',
            'company_describe',
            'service_charge',
            'linkman',
            'phone',
            'post',
            'company_pdf',
            [
                'attribute' => 'check',
                'format' => 'html',
                'value' => function($model) {
                    return $model->check==2?'<font color="red">否</font>':'<font color="green">是</font>';
                },
                'contentOptions' => ['style'=> 'text-align: center;'],
                'headerOptions' => [
                    'width' => '10%',
                    'style'=> 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'state',
                'format' => 'html',
                'value' => function($model) {
                    return $model->state==2?'<font color="red">待审核</font>':'<font color="green">已审核</font>';
                },
                'contentOptions' => ['style'=> 'text-align: center;'],
                'headerOptions' => [
                    'width' => '10%',
                    'style'=> 'text-align: center;'
                ],
                'label' => '审核状态',
            ],
            [
                'attribute' => 'created_at',
                'contentOptions' => ['class'=>'text-center'],
                'value' => function($model){
                    return date("Y-m-d H:i:s",$model->created_at);
                },
                'headerOptions' => [
                    'style'=> 'text-align: center;'
                ],
                'label' => '创建时间',
            ],[
                'attribute' => 'updated_at',
                'contentOptions' => ['class'=>'text-center'],
                'value' => function($model){
                    return date("Y-m-d H:i:s",$model->created_at);
                },
                'headerOptions' => [
                    'style'=> 'text-align: center;'
                ],
                'label' => '修改时间',
            ],
        ],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
    ])
    ?>
</div>
