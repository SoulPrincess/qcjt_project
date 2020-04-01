<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="strict-form create_box">
    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 128,'class'=>'layui-input']) ?>
	
	<?= $form->field($model, 'sort')->input('number',['class'=>'layui-input']) ?>

    <?= $form->field($model, 'state')->radioList([
        '1'=>'可用',
        '2'=>'不可用',
    ],['style' => 'padding: 10px;'])?>


    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
