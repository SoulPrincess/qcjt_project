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
       'pluginOptions' => ['initialState' => 'collapsed'],
        'options' => ['class' => 'table table-hover','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
//            [
//                'class' => 'backend\widgets\CheckboxColumn',
//                'checkboxOptions' => ['lay-skin'=>'primary','lay-filter'=>'choose'],
//                'headerOptions' => ['width'=>'50','style'=> 'text-align: center;'],
//                'contentOptions' => ['style'=> 'text-align: center;']
//            ],
            'type_name',
            'intro',
            'pid',
            'sort',
            [
                'attribute' => 'sort',
                'value' => function ($model) {
                    return $model->sort;
                },
                'action' => Url::to(['permission-sort'])
            ],
            [
                'class' => SortColumn::className()
            ],
            [
				'header' => '操作',
				'class' => 'yii\grid\ActionColumn',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => [
					'width' => '15%',
					'style'=> 'text-align: center;'
				],
				'template' =>'{view} {update} {delete}',
				'buttons' => [
                    'view' => function ($url, $model, $key){
						return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view"]);
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
