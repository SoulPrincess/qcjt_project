<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs($this->render('js/upload.js'));
?>

<div class="guan-type-form create_box">
    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'pid', ['id' => 'parent_id']); ?>

	<?= $form->field($model, 'type_name')->textInput(['maxlength' => 100,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'intro')->textInput(['maxlength' => 100,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => 50,'class'=>'layui-input']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 20,'class'=>'layui-input']) ?>

	<?= $form->field($model, 'parent_name')->textInput(['id' => 'parent_name','class'=>'layui-input']) ?>

    <?= $form->field($model, 'cover_img',['template' => '{label} <div class="row"><div class="col-sm-12">{input}<button type="button" class="layui-btn upload_button" id="test4"><i class="layui-icon"></i>上传图片</button>{error}{hint}</div></div>'])->textInput(['maxlength' => true,'class'=>'layui-input upload_input']) ?>

    <?= Html::img(@$model->cover_img, ['width'=>'50','height'=>'50','class'=>'layui-circle cover_img'])?>

    <?= $form->field($model, 'wechat_img',['template' => '{label} <div class="row"><div class="col-sm-12">{input}<button type="button" class="layui-btn upload_button" id="test5"><i class="layui-icon"></i>上传图片</button>{error}{hint}</div></div>'])->textInput(['maxlength' => true,'class'=>'layui-input upload_input']) ?>

    <?= Html::img(@$model->wechat_img, ['width'=>'50','height'=>'50','class'=>'layui-circle wechat_img'])?>

	<?= $form->field($model, 'sort')->input('number',['class'=>'layui-input']) ?>
    <?php

    foreach($imgmodel as $k=>$v){
        $v=(object)$v;
        foreach($v as $k1=>$v1){
            $v1=(object)$v1;
            ?>
            <div class="resume-school-form create_box">
                <?php $form = ActiveForm::begin(['action' => ['img-update?id='.$v1->id],'method'=>'post','enableAjaxValidation' => false]); ?>
                <div class="layui-row">
                    <div class="layui-col-xs6">
                            <?= Html::img(@$v1->file_name, ['width'=>'50','height'=>'50','class'=>'layui-circle cover_img layui-upload-list','id'=>'demo2'])?>
                        <?= $form->field($v1, 'file_name')->textInput(['id' => 'imgs','class'=>'layui-input'])?>
                    </div>
                </div>
                <div align='center'>
                    <?=
                    Html::a('删除', \yii\helpers\Url::to(['school-delete','id'=>$v1->id]), ['class' => "layui-btn resume_school"]);
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        <?php }}?>
<!--    --><?//= $form->field($model, 'cover_img',['template' => '
//{label}
//<div class="row">
//  <div class="col-sm-12" id="path1">{input}
//     <button type="button" class="layui-btn upload_button" id="test6">
//         <i class="layui-icon"></i>上传图片
//     </button>{error}{hint}
//  </div>
//</div>'
//    ])->textInput(['maxlength' => true,'class'=>'layui-input']) ?>
    <div class="row">
        <div class="col-sm-12" id="path1">
            <button type="button" class="layui-btn upload_button" id="test6">
                <i class="layui-icon"></i>上传图片
            </button>
        </div>
    </div>
    <div class='layui-upload-list' id='demo2'></div>
<!--    --><?//= Html::img(@$model->cover_img, ['width'=>'50','height'=>'50','class'=>'layui-circle cover_img layui-upload-list','id'=>'demo2'])?>

    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
