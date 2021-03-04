layui.use(['upload','layer','layedit'], function(){
  var upload = layui.upload,layer = parent.layer === undefined ? layui.layer : parent.layer;

    upload.render({
        elem: '#test4',
        url: "<?=yii\helpers\Url::to(['/tools/upload?name=guanwang'])?>",
        done: function(res){console.log(res);
            if(res.code==200){
                //修改上传成功后需要修改的地方
                $("#guangoods-cover_img").val(res.data);
                $(".cover_img").attr('src',res.data);
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
            url: "<?=yii\helpers\Url::to(['/tools/uploadedit?name=guanwang'])?>",
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
    layedit.build('goods_content'); //建立编辑器
    /*excel导入*/
    upload.render({
        elem: '#goods_import',
        url: "<?=yii\helpers\Url::to(['/tools/import'])?>",
        method: 'post',
        type:'file',
        accept: 'file', //允许上传的文件类型
        acceptMime:'.xls,.xlsx',
        before: function(obj){
            layer.load();
        },
        done: function(res, index, upload){ //上传后的回调
            layer.closeAll('loading');
            if(res.code==200){
                layer.msg(res.msg+',错误条数：'+res.error_cnt);
            }else{
                layer.msg(res.msg);
            }
            setTimeout(function(){
                layer.close(index);
                location.reload();
            },1000);
        },
    });
});