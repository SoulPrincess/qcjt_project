<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/**
 * @var yii\web\View $this
 * @var official\models\searchs\guan-goods $model
 * @var yii\widgets\ActiveForm $form
 */
$this->registerJs($this->render('js/upload.js'));
?>

<div class="guan-goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline layui-form'],
		'fieldConfig' => [
		   'template' => '<div class="layui-inline">{label}：<div class="layui-input-inline">{input}</div></div><span class="help-block" style="display: inline-block;">{hint}</span>',
	   ],
    ]); ?>
    <?= $form->field($model, 'pid')->dropDownList(\official\models\GuanType::parentdropDown(),['prompt'=>'请选择类别','style'=>'display:none;'])->label('父级类别') ?>

    <?= $form->field($model, 'type_name')->textInput(['class'=>'layui-input search_input'])->label('商品名称') ?>

    <?= $form->field($model, 'status')->dropDownList(['1'=>'是','2'=>'否'],['prompt'=>'请选择类别','style'=>'display:none;width:100px'])->label('是否显示') ?>

    <div class="form-group" >
        <?= Html::submitButton('查找', ['class' => 'layui-btn layui-btn-normal']) ?>
        <?= Html::button('添加', ['class' => 'layui-btn layui-default-add']) ?>
		<?= Html::button('批量删除', ['class' => 'layui-btn layui-btn-danger layui-default-delete-all']) ?>
        <?= Html::button("<i class='layui-icon'>&#xe67c;</i>批量导入", ['class' => 'layui-btn layui-btn-normal layui-default-import','id'=>'goods_import']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
