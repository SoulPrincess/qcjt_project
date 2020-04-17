<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use official\models\GuanGoodsAttribute;
$this->registerJs($this->render('js/upload.js'));
?>

<div class="guan-goods-attribute-form create_box">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'goods_id')->dropDownList(GuanGoodsAttribute::dropDown(),['prompt'=>'请选择类别'])->label('商品名称') ?>

	<?= $form->field($model, 'attribute')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'cost')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'sort')->input('number',['class'=>'layui-input']) ?>

    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
