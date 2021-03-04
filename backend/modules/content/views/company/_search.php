<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;
use content\models\CompanyType;

/**
 * @var yii\web\View $this
 * @var rbac\models\searchs\company $model
 * @var yii\widgets\ActiveForm $form
 */
AppAsset::register($this);
?>

<div class="company-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline layui-form'],
		'fieldConfig' => [
		   'template' => '<div class="layui-inline">{label}：<div class="layui-input-inline">{input}</div></div><span class="help-block" style="display: inline-block;">{hint}</span>',
	   ],
    ]); ?>

    <?= $form->field($model, 'company_name')->textInput(['class'=>'layui-input search_input']) ?>

    <?= $form->field($model, 'type_id')->dropDownList(CompanyType::dropDown(),['prompt'=>'请选择','style'=>'display:none;']) ?>

    <?= $form->field($model, 'state')->dropDownList(['1'=>'已审核','2'=>'待审核','3'=>'不通过'],['prompt'=>'请选择','style'=>'display:none;']) ?>

    <?= $form->field($model, 'strict_state')->dropDownList(['1'=>'是','2'=>'否'],['prompt'=>'请选择','style'=>'display:none;']) ?>

    <div class="form-group">
        <?= Html::submitButton('查找', ['class' => 'layui-btn layui-btn-normal']) ?>
        <?= Html::button('添加', ['class' => 'layui-btn layui-default-add']) ?>
		<?= Html::button('批量删除', ['class' => 'layui-btn layui-btn-danger layui-default-delete-all']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
