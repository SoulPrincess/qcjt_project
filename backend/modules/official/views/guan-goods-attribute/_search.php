<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use official\models\GuanGoodsAttribute;

/**
 * @var yii\web\View $this
 * @var official\models\searchs\guan-goods-attribute $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="guan-goods-attribute-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline layui-form'],
		'fieldConfig' => [
		   'template' => '<div class="layui-inline">{label}：<div class="layui-input-inline">{input}</div></div><span class="help-block" style="display: inline-block;">{hint}</span>',
	   ],
    ]); ?>

    <?= $form->field($model, 'goods_id')->dropDownList(GuanGoodsAttribute::dropDown(),['prompt'=>'请选择商品','style'=>'display:none;'])->label('商品名称') ?>

    <?= $form->field($model, 'attribute')->textInput(['class'=>'layui-input search_input'])?>

    <div class="form-group">
        <?= Html::submitButton('查找', ['class' => 'layui-btn layui-btn-normal']) ?>
        <?= Html::button('添加', ['class' => 'layui-btn layui-default-add']) ?>
		<?= Html::button('批量删除', ['class' => 'layui-btn layui-btn-danger layui-default-delete-all']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
