<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="qing-type-view">
    <?=DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'type_name',
            'sort',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($model) {
                    return $model->status==2?'<font color="red">隐藏</font>':'<font color="green">显示</font>';
                },
                'contentOptions' => ['style'=> 'text-align: center;','id'=>'status_1'],
                'headerOptions' => [
                    'width' => '10%',
                    'style'=> 'text-align: center;'
                ],
                'label' => '状态',
            ],
            ['label'=>'创建时间','value'=>date('Y-m-d H:i:s',$model->created_at)],
            ['label'=>'修改时间','value'=>date('Y-m-d H:i:s',$model->updated_at)],
        ],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
    ])
    ?>
</div>
