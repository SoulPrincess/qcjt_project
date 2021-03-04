<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="qing-mess-view">
    <?=DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'qingType.type_name:text:类别名称',
            'title',
            'little_title',
            'sort',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($model) {
                    return $model->status==2?'<font color="red">否</font>':'<font color="green">是</font>';
                },
                'contentOptions' => ['style'=> 'text-align: center;','id'=>'status_1'],
                'headerOptions' => [
                    'width' => '10%',
                    'style'=> 'text-align: center;'
                ],
                'label' => '显示',
            ],
            [
                'attribute' => 'img',
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
            'content',
            ['label'=>'创建时间','value'=>date('Y-m-d H:i:s',$model->created_at)],
            ['label'=>'修改时间','value'=>date('Y-m-d H:i:s',$model->updated_at)],
        ],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
    ])
    ?>
</div>
