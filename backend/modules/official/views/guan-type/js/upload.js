layui.use(['upload','layer','layedit'], function(){
  var upload = layui.upload,layer = parent.layer === undefined ? layui.layer : parent.layer;

    upload.render({
        elem: '#test4',
        url: "<?=yii\helpers\Url::to(['/tools/upload?name=guanwang'])?>",
        done: function(res){console.log(res);
            if(res.code==200){
                //修改上传成功后需要修改的地方
                $("#guantype-cover_img").val(res.data);
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

    upload.render({
        elem: '#test5',
        url: "<?=yii\helpers\Url::to(['/tools/upload?name=guanwang_wechat'])?>",
        done: function(res){
            if(res.code==200){
                //修改上传成功后需要修改的地方
                $("#guantype-wechat_img").val(res.data);
                $(".wechat_img").attr('src',res.data);
                layer.msg("上传成功");
            }else{
                layer.msg("上传失败");
            }
        },
        error: function(){
            layer.msg("请求异常");
        }
    });
    upload.render({
        elem: '#test6',
        url: "<?=yii\helpers\Url::to(['/tools/upload?name=guanwang'])?>",
        done: function(res){
            if(res.code==200){
                //修改上传成功后需要修改的地方
                $("#guantype-hot_img").val(res.data);
                $(".hot_img").attr('src',res.data);
                layer.msg("上传成功");
            }else{
                layer.msg("上传失败");
            }
        },
        error: function(){
            layer.msg("请求异常");
        }
    });

    layui.use('upload', function() {
        var $ = layui.jquery,
            upload = layui.upload;
        //多文件列表示例
        var demoListView = $('#demoList'),
            uploadListIns = upload.render({
                elem: '#testList',
                url: "<?=yii\helpers\Url::to(['/tools/uploads?name=guanwang1'])?>",
                accept: 'images',
                acceptMime: 'image/*',
                size: 8192,
                multiple: true,
                number: 400,
                auto: false,
                exts: 'jpg|png|jpeg',
                bindAction: '#testListAction',
                choose: function(obj) {
                    var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                    //读取本地文件
                    obj.preview(function(index, file, result) {
                        var tr = $(['<tr id="upload-' + index + '">', '<td>' + file.name + '</td>', '<td><img src="' + result + '" alt="' + file.name + '" style="width: 100px;height: 40px;"></td>', '<td>' + (file.size / 1014).toFixed(1) + 'kb</td>', '<td>等待上传</td>', '<td>', '<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>', '<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>', '</td>', '</tr>'].join(''));
                        //单个重传
                        tr.find('.demo-reload').on('click', function() {
                            obj.upload(index, file);
                            $("#upload-" + index).find("td").eq(2).html((file.size / 1014).toFixed(1) + 'kb');
                        });

                        //删除
                        tr.find('.demo-delete').on('click', function() {
                            delete files[index]; //删除对应的文件
                            tr.remove();
                            uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                        });

                        demoListView.append(tr);
                        $(".num_pic").text("总共【" + demoListView.find("tr").length + "】张图片");
                    });
                },

                done: function(res, index, upload) {
                    if(res.code == 200) { //上传成功
                        $("#cao").text("地址");
                        var tr = demoListView.find('tr#upload-' + index),
                            tds = tr.children();
                        tds.eq(3).html('<span style="color: #5FB878;">上传成功</span>');
                        tds.eq(4).html('<input type="text" name="imgs[]"  value="' + res.data + '" class="layui-input" />'); //清空操作
                        return delete this.files[index]; //删除文件队列已经上传成功的文件
                    }
                    this.error(index, upload);
                },
                allDone: function(obj) { //当文件全部被提交后，才触发
                    layer.msg("上传文件数量：【" + obj.total + "】张，上传成功：【" + obj.successful + "】张，失败：【" + obj.aborted + "】", {
                        time: 3000
                    });
                    console.log(obj.total); //得到总文件数
                    console.log(obj.successful); //请求成功的文件数
                    console.log(obj.aborted); //请求失败的文件数
                },
                error: function(index, upload) {
                    var tr = demoListView.find('tr#upload-' + index),
                        tds = tr.children();
                    tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                    tds.eq(4).find('.demo-reload').removeClass('layui-hide'); //显示重传
                }
            });

    });
});