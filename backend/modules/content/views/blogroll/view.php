<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;
use yii\helpers\Html;
LayuiAsset::register($this);
?>
<div class="blogroll-view">
    <?=DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'bl_name',
            [
                'attribute' => 'bl_url',
                'format' => 'html',
                'value' => function($model) {
                    return $model->bl_url?$model->bl_url:'<font color="red">(未设置)</font>';
                },
                'contentOptions' => ['style'=> 'text-align: center;'],
                'headerOptions' => [
                    'width' => '10%',
                    'style'=> 'text-align: center;'
                ]
            ],
            'bl_sort',
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
