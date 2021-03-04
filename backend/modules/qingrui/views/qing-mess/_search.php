<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJs($this->render('js/upload.js'));
?>

<div class="qing-mess-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline layui-form'],
		'fieldConfig' => [
		   'template' => '<div class="layui-inline">{label}：<div class="layui-input-inline">{input}</div></div><span class="help-block" style="display: inline-block;">{hint}</span>',
	   ],
    ]); ?>
    <?= $form->field($model, 'type_id')->dropDownList(\qingrui\models\QingType::dropDown(),['prompt'=>'请选择类别','style'=>'display:none;'])->label('类别') ?>

    <?= $form->field($model, 'title')->textInput(['class'=>'layui-input search_input'])->label('标题') ?>


    <?= $form->field($model, 'status')->dropDownList(['1'=>'是','2'=>'否'],['prompt'=>'请选择类别','style'=>'display:none;width:100px'])->label('是否显示') ?>

    <div class="form-group" >
        <?= Html::submitButton('查找', ['class' => 'layui-btn layui-btn-normal']) ?>
        <?= Html::button('添加', ['class' => 'layui-btn layui-default-add']) ?>
		<?= Html::button('批量删除', ['class' => 'layui-btn layui-btn-danger layui-default-delete-all']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
