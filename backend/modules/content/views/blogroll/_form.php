<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use content\models\CompanyType;
?>

<div class="menu-form create_box">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bl_name')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'bl_url')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'bl_sort')->input('number',['class'=>'layui-input']) ?>

    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
