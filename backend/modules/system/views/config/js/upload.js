layui.use(['upload','layer','layedit'], function(){
  var upload = layui.upload,layer = parent.layer === undefined ? layui.layer : parent.layer;

    var layedit = layui.layedit;
    layedit.set({
        uploadImage: {
            url: "<?=yii\helpers\Url::to(['/tools/uploadedit'])?>",
            type: 'post',
            done: function (res) {
                // 如果上传失败
                if (res.code > 0) {
                    return layer.msg(res.msg);
                }
                else {
                    return layer.msg(res.msg);
                }
                // 上传成功
            }, error: function () {
                layer.msg('上传异常,请重试');
            },
            tool: ['left', 'center', 'right', 'italic','strong','underline','del','link','unlink','|', 'face','image']
        }
    });
    layedit.build('value_area'); //建立编辑器
});