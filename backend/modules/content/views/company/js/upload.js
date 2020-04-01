layui.use(['upload','layer','layedit'], function(){
  var upload = layui.upload,layer = parent.layer === undefined ? layui.layer : parent.layer;

    upload.render({
        elem: '#test4',
        url: "<?=yii\helpers\Url::to(['/tools/upload'])?>",
        done: function(res){
            if(res.code==200){
                //修改上传成功后需要修改的地方
                $("#company-company_logo").val(res.data);
                $(".company_logo").attr('src',res.data);
                layer.msg("上传成功");
            }else{
                layer.msg("上传失败");
            }
        },
        error: function(){
            layer.msg("请求异常");
        }
    });
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
    layedit.build('test5'); //建立编辑器

    upload.render({
        elem: '#test6',
        url: "<?=yii\helpers\Url::to(['/tools/upload-pdf'])?>",
        accept: 'file', //普通文件
        done: function(res){
            if(res.code==200){console.log(res);
                //修改上传成功后需要修改的地方
                $("#company-company_pdf").val(res.data);
                $(".company_pdf").attr('src',res.data);
                layer.msg("上传成功");
            }else{
                layer.msg("上传失败");
            }
        },
        error: function(){
            layer.msg("请求异常");
        }
    });
});