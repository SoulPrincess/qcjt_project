<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="qing-case-view">
    <?=DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'case_title',
            'case_sort',
            [
                'attribute' => 'case_img',
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
            ['attribute'=>'case_content',
                'label'=>'案例内容',
                'value'=>function($model){
                  return htmlspecialchars_decode($model->case_content);
                }
            ],
            [
                'attribute' => 'case_status',
                'format' => 'html',
                'value' => function($model) {
                    return $model->case_status==2?'<font color="red">隐藏</font>':'<font color="green">显示</font>';
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
