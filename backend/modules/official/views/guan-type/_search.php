<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use official\models\GuanType;
/**
 * @var yii\web\View $this
 * @var official\models\searchs\guan-type $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="guan-type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline layui-form'],
		'fieldConfig' => [
		   'template' => '<div class="layui-inline">{label}：<div class="layui-input-inline">{input}</div></div><span class="help-block" style="display: inline-block;">{hint}</span>',
	   ],
    ]); ?>
    <?= $form->field($model, 'id')->dropDownList(GuanType::parentdropDown(),['prompt'=>'请选择类别','style'=>'display:none;'])->label('类别名称') ?>


    <div class="form-group">
        <?= Html::submitButton('查找', ['class' => 'layui-btn layui-btn-normal']) ?>
        <?= Html::button('添加', ['class' => 'layui-btn layui-default-add']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
