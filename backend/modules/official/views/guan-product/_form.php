<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use official\models\GuanproductType;
use official\models\Guanproduct;
use official\models\GuanNavigation;
$this->registerJs($this->render('js/upload.js'));
?>

<div class="guan-product-form create_box">
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'pro_name')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'type_id')->dropDownList(GuanproductType::dropTypeDown(),['prompt'=>'请选择类别'])->label('产品类别') ?>

    <?= $form->field($model, 'nav_id')->dropDownList(GuanNavigation::dropNavigationDown(),['prompt'=>'请选择导航'])->label('导航') ?>

	<?= $form->field($model, 'pro_intro')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>


    <?= $form->field($model, 'cover_img',['template' => '{label} <div class="row"><div class="col-sm-12">{input}<button type="button" class="layui-btn upload_button" id="test4"><i class="layui-icon"></i>上传图片</button>{error}{hint}</div></div>'])->textInput(['maxlength' => true,'class'=>'layui-input upload_input']) ?>

    <?= Html::img(@$model->cover_img, ['width'=>'50','height'=>'50','class'=>'layui-circle cover_img'])?>

    <?=$form->field($model, 'content')->textarea(['maxlength' => true,'rows'=>"10",'cols'=>"30",'autofocus'=>'autofocus','id'=>'product_content']) ?>

    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
