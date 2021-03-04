<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use official\models\GuanType;
use official\models\GuanGoods;
$this->registerJs($this->render('js/upload.js'));
?>

<div class="guan-goods-form create_box">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type_id')->dropDownList(GuanType::dropDown(),['prompt'=>'请选择类别'])->label('类别名称') ?>

<!--    --><?//= $form->field($model, 'attr_id')->dropDownList(GuanGoods::guanGoodsAttribute(),['prompt'=>'请选择类别'])->label('属性') ?>

	<?= $form->field($model, 'intro')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'cost')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'cover_img',['template' => '{label} <div class="row"><div class="col-sm-12">{input}<button type="button" class="layui-btn upload_button" id="test4"><i class="layui-icon"></i>上传图片</button>{error}{hint}</div></div>'])->textInput(['maxlength' => true,'class'=>'layui-input upload_input']) ?>

    <?= Html::img(@$model->cover_img, ['width'=>'50','height'=>'50','class'=>'layui-circle cover_img'])?>

    <?=$form->field($model, 'content')->textarea(['maxlength' => true,'rows'=>"10",'cols'=>"30",'autofocus'=>'autofocus','id'=>'goods_content']) ?>

    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
