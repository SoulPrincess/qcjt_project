<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
$this->registerJs($this->render('js/upload.js'));
?>
<div class="company-status">
    <div class="company-form create_box">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'state')->radioList(['1'=>'通过','2'=>'待审核','不通过'],['itemOptions'=>['labelOptions'=>['class'=>'radio-inline']]])->label('审核<font color="red">（不通过记得填写不通过原因！）</font>') ;?>

        <?= $form->field($model, 'strict_state')->radioList(['1'=>'是','2'=>'否'],['itemOptions'=>['labelOptions'=>['class'=>'radio-inline']]]);?>

        <?= $form->field($model, 'check')->radioList([1=>'是',2=>'否'],['itemOptions'=>['labelOptions'=>['class'=>'radio-inline']]])->label('自营<font color="red">（只能有一家自营企业）</font>') ;?>

        <?= $form->field($model, 'reason')->textInput(['maxlength' => 50,'class'=>'layui-input'])->label('原因<font color="red">（不得超过50个字符！）</font>') ?>

        <div align='right' style="margin-top:10px;">
            <?=
            Html::submitButton('更新', ['class' => 'layui-btn layui-btn-normal'])
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
