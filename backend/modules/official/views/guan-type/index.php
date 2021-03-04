<?php

use yii\helpers\Html;
use yii\grid\GridView;
use leandrogehlen\treegrid\TreeGrid;
use backend\assets\LayuiAsset;
use yii\helpers\Url;

LayuiAsset::register($this);
$this->registerJs($this->render('js/index.js'));
?>
<blockquote class="layui-elem-quote" style="font-size: 14px;">
	<?php  echo $this->render('_search', ['model' => $searchModel]); ?>
</blockquote>
<div class="layui-form guan-type-index">
    <?=
    TreeGrid::widget([
        'dataProvider' => $dataProvider,
		'keyColumnName' => 'id', //ID
        'parentColumnName' => 'pid', //父ID
        'parentRootValue' => '', //first parentId value
        'pluginOptions' => ['initialState' => 'collapsed'],//默认状态时 expanded展开 collapsed关闭
        'options' => ['class' => 'table table-hover','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            [
                    'attribute' => 'type_name',

                    'headerOptions' => [
                        'width' => '10%',
                        'style'=> 'text-align: center;',
                             'title'=>'type_name',
                    ],
            ],
//            'type_name',
            'intro',
//            'pid',
            'contact',
            'phone',
            [
                'label' => '首页状态',
                'format'=>'raw',
                'value' => function ($model) {
                    return \kartik\editable\Editable::widget([
                        'name' => 'GuanType[home_status]',
                        'value' => $model->home_status==2?'<font color="red">否</font>':'<font color="green">是</font>',
                        'attribute' => 'home_status',
                        'header' => '首页状态',
                        'formOptions' => [
                            'method' => 'post',
                            'action' => Yii::$app->urlManager->createAbsoluteUrl(['/official/guan-type/update', 'id' => $model->id])
                        ],
                        'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                        'data' => [
                            1 => '显示',
                            2 => '隐藏',
                        ],
                        'options' => ['class' => 'form-control', 'placeholder' =>'','style'=>'display:none'],
                    ]);
                },
            ],
            [
                'label'=>'热门',
                'format'=>'raw',
                'value' => function ($model) {
                    return \kartik\editable\Editable::widget([
                        'name' => 'GuanType[hot]',
                        'value' => $model->hot==2?'<font color="red">否</font>':'<font color="green">是</font>',
                        'attribute' => 'hot',
                        'header' => '热门',
                        'formOptions' => [
                            'method' => 'post',
                            'action' => Yii::$app->urlManager->createAbsoluteUrl(['/official/guan-type/update', 'id' => $model->id])
                        ],
                        'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                        'data' => [
                            1 => '是',
                            2 => '否',
                        ],
                        'options' => ['class' => 'form-control', 'placeholder' =>'','style'=>'display:none'],
                    ]);
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($model) {
                    return $model->status==2?'<font color="red">否</font>':'<font color="green">是</font>';
                },
                'contentOptions' => ['style'=> 'text-align: center;','id'=>'status_2'],
                'headerOptions' => [
                    'width' => '10%',
                    'style'=> 'text-align: center;'
                ],
                'label' => '显示',
            ],
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
            'sort',
            [
				'header' => '操作',
				'class' => 'yii\grid\ActionColumn',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => [
					'width' => '20%',
					'style'=> 'text-align: center;'
				],
				'template' =>'{view} {update}{status}  {delete}',
				'buttons' => [
                    'view' => function ($url, $model, $key){
						return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view"]);
                    },
                    'status' => function ($url, $model, $key) {
                        return Html::a('操作', Url::to(['status','id'=>$model->id]), ['class' => "layui-btn layui-btn-warm layui-btn-xs layui-default-status"]);
                    },
                    'update' => function ($url, $model, $key) {
						return Html::a('修改', Url::to(['update','id'=>$model->id]), ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                    },
					'delete' => function ($url, $model, $key) {
						return Html::a('删除', Url::to(['delete','id'=>$model->id]), ['class' => "layui-btn layui-btn-danger layui-btn-xs layui-default-delete"]);
					}
				]
			],
        ],
    ]);
    ?>

</div>
