<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJs($this->render('js/_script.js'));
?>

<div class="qing-type-form create_box">
    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'type_name')->textInput(['maxlength' => 128,'class'=>'layui-input']) ?>
	
	<?= $form->field($model, 'sort')->input('number',['class'=>'layui-input']) ?>

    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
