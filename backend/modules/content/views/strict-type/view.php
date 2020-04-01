<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="strict-view">
    <?=DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'name',
            'sort',
            [
                'attribute' => 'state',
                'format' => 'html',
                'value' => function($model) {
                    return $model->state==2?'<font color="red">不可用</font>':'<font color="green">可用</font>';
                },
                'contentOptions' => ['style'=> 'text-align: center;'],
                'headerOptions' => [
                    'width' => '10%',
                    'style'=> 'text-align: center;'
                ],
                'label' => '状态',
            ],
        ],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
    ])
    ?>
</div>
