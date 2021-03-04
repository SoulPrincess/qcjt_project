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
    <?= $form->field($model, 'hot_img',['template' => '{label} <div class="row"><div class="col-sm-12">{input}<button type="button" class="layui-btn upload_button" id="test6"><i class="layui-icon"></i>上传图片</button>{error}{hint}</div></div>'])->textInput(['maxlength' => true,'class'=>'layui-input upload_input']) ?>

    <?= Html::img(@$model->hot_img, ['width'=>'50','height'=>'50','class'=>'layui-circle hot_img'])?>

	<?= $form->field($model, 'sort')->input('number',['class'=>'layui-input']) ?>
    <div class="layui-upload">
            <button type="button"class="layui-btn layui-btn-normal"id="testList">请选择图片</button>
            <span class="num_pic"></span>
            <div class="layui-upload-list">
                    <table class="layui-table">
                            <thead>
                                <tr>
                                        <th>文件名</th>
                                        <th id="pic">图片预览</th>
                                        <th>大小</th>
                                        <th>状态</th>
                                        <th id="cao">操作</th>
                                    </tr>
                            </thead>
                            <tbody id="demoList"></tbody>
                        </table>
                </div>
            <button type="button"class="layui-btn"id="testListAction">开始上传</button>
                <span class="num_pic"></span>
    </div>
    <div align='right' style="margin-top:10px;">
        <?=
        Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord? 'layui-btn' : 'layui-btn layui-btn-normal'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
