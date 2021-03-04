<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="guan-type-view">
    <?=DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'guanTypeParent.type_name:text:父级名称',
            'type_name',
            [
                'attribute' => 'cover_img',
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
            [
                'attribute' => 'wechat_img',
                'contentOptions' => ['style'=> 'text-align: center;'],
                'headerOptions' => ['width' => '100px','style'=> 'text-align: center;'],
                "format"=>[
                    "image",
                    [
                        "width"=>"30px",
                        "height"=>"30px",
                    ],
                ],
            ],
            [
                'attribute' => 'hot_img',
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
            'intro',
            'sort',
        ],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
    ])
    ?>
</div>
