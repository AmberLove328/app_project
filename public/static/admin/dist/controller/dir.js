
layui.use(['jquery', 'layer', 'contextMenu', 'upload', 'laytpl'], function () {
    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;
    var laytpl = layui.laytpl;
    var contextMenu = layui.contextMenu;
    var baseServer = window.location.protocol + '//' + window.location.host + '/';

    // 渲染文件列表
    function renderList(dir) {
        if (!dir) {
            dir = $('#tvFP').text();
        }
        layer.load(2,{time:5});
        $.get( 'fileList', {
            dir:dir
        }, function (res) {
            layer.closeAll('loading');
            if (res.code === 0) {
                for (var i = 0; i < res.data.length; i++) {
                    if(res.data[i].url !== 'null'){
                        res.data[i].url = baseServer + res.data[i].url;
                    }
                }
                initHtml(dir); //初始化导航栏数据
                $('#tvFP').html(dir);
                laytpl(fileTpl.innerHTML).render(res.data, function (html) {
                    $('.file-list').html(html);
                });
            } else {
                layer.msg(res.msg, {icon: 5,time:1000});
            }
        },'json');
    }

    renderList();


    // 上传文件事件
    upload.render({
        elem: '#btnUpload',
        url: 'upload',
        data: {
            path: function(){
                return $('#tvFP').text();
            }
        },
        multiple:true,
        size: 20*1024,  //限制最大上传20M
        choose: function (obj) {
            layer.load(2,{time:5});
        },
        done: function (res, index, upload) {
            layer.closeAll('loading');
            if (res.code !== 0) {
                layer.msg(res.msg, {icon: 5,time:1000});
            } else {
                layer.msg('上传成功', {icon: 1,time:1000});
                renderList();
            }
        },
        error: function () {
            layer.closeAll('loading');
            layer.msg('上传失败', {icon: 5,time:1000});
        },
        accept: 'file'
    });

    // 刷新
    $('#btnRefresh').click(function () {
        var cDir = $('#tvFP').text();
        renderList(cDir);
    });

    var mUrl;
    // 列表点击事件
    $('body').on('click', '.file-list-item', function (e) {
        var isDir = $(this).data('dir');
        var name = $(this).data('name');
        var hasSm = $(this).data('has');
        mUrl = $(this).data('url');
        $('#copy').attr('data-clipboard-text', mUrl);
        if (isDir) {
            var cDir = $('#tvFP').text();
            cDir += (cDir == '/' ? name : ('/' + name));
            $('#tvFP').text(cDir);
            renderList(cDir);
        } else {
            var $target = $(this).find('.file-list-img');
            $('#dropdownFile').css({
                'top': $target.offset().top + 90,
                'left': $target.offset().left + 25
            });
            $('#dropdownFile').addClass('dropdown-opened');
            if (e !== void 0) {
                e.preventDefault();
                e.stopPropagation();
            }
            if(hasSm){
                $(".checkdown").html("&emsp;查看&emsp;");
            }else{
                $(".checkdown").html("&emsp;下载&emsp;");
            }
        }
    });

    // 重写右键菜单
    $('body').delegate('.dir', "contextmenu", function(f) {
        var old_name = $(this).data('name');
        var path = $('#tvFP').text();
        contextMenu.show(
            [{
                icon: 'layui-icon layui-icon-edit',
                name: '重命名',
                click: function () {
                    layer.prompt({title: '文件夹名称',value: old_name},function(text, index){
                        layer.load(2,{time:5});
                        $.ajax({
                            url:'renameFolder',
                            data:{dir_path:path,old_name:old_name,new_name:text},
                            dataType:'json',
                            type:'POST',
                            success:function(res){
                                layer.closeAll('loading');
                                layer.close(index);
                                if(res.code === 0){
                                    layer.msg('操作成功！',{icon:1,time:1000});
                                    renderList();
                                }else{
                                    layer.msg(res.msg,{icon:5,time:1000});
                                }
                            }
                        })
                    });

                }
            }, {
                icon: 'layui-icon layui-icon-delete',
                name: '删除',
                click: function () {
                    layer.confirm('确定要删除此文件夹吗？', function (index) {
                        layer.load(2,{time:5});
                        $.ajax({
                            url:'delFolder',
                            data:{path:path+'/'+old_name},
                            dataType:'json',
                            type:'POST',
                            success:function(res){
                                layer.closeAll('loading');
                                layer.close(index);
                                if(res.code === 0){
                                    layer.msg('操作成功！',{icon:1,time:1000});
                                    renderList();
                                }else{
                                    layer.msg(res.msg,{icon:5,time:1000});
                                }
                            }
                        })
                    });
                }
            }],f.clientX, f.clientY
        );
        return false

    });


    // 返回上级
    $('#btnBack').click(function () {
        var cDir = $('#tvFP').text();
        if (cDir == '/') {
            // layer.msg('已经是根目录了', {icon: 5,time:1000})
        } else {
            cDir = cDir.substring(0, cDir.lastIndexOf('/'));
            if (!cDir) {
                cDir = '/';
            }
            $('#tvFP').text(cDir);
            renderList(cDir);
        }
    });

    //主页
    $('#btnHome').click(function () {
        $('#tvFP').text("/");
        renderList("/");
    });

    //导航处理A标签
    function initHtml(cDir) {
        if( cDir === "/" ){
            $('#tvFP1').html(retunrName("/" ,"/"));
            return false;
        }
        var ms = cDir.substring(1,cDir.length);
        var ss = ms.split("/");
        var s = retunrName("/" ,"/");
        for ( i = 0; i < ss.length; i++ ){
            var name = "/";
            for ( j = 0; j < i; j++ ) {
                name += ss[j]+"/";
            }
            name += ss[i];
            if ( i > 0) {
                s += "/&nbsp;&nbsp;";
            }
            s += retunrName( ss[i] ,name);
        }
        $('#tvFP1').html(s);
    }
    function retunrName(name ,aname) {
        return "<a href='javascript:void(0);'  class='js-href' data-name='"+aname+"'>"+name+"</a>&nbsp;&nbsp;";
    }
    //处理导航链接
    $("body").on("click",".js-href",function () {
        var dir =  $(this).attr("data-name");
        renderList(dir);
    });

    // 点击空白隐藏下拉框
    $('html').off('click.dropdown').on('click.dropdown', function () {
        $('#copy').attr('data-clipboard-text', '');
        $('#dropdownFile').removeClass('dropdown-opened');
    });

    // 打开
    $('#open').click(function () {
        window.open(mUrl);
        // $.get('/index/api/downFile',{file:mUrl},function (res) {
        //     if(res.code !== 0){
        //         layer.msg(res.msg, {icon: 5,time:1000});
        //     }
        //
        // })
    });
    // 删除
    $('#del').click(function () {
        layer.confirm('确定要删除此文件吗？', function () {
            layer.load(2,{time:5});
            $.get('fileDel', {
                file: mUrl.substring(mUrl.indexOf(baseServer) + 6)
            }, function (res) {
                layer.closeAll('loading');
                if (res.code === 0) {
                    layer.msg("删除成功", {icon: 1,time:1000});
                    renderList();
                } else {
                    layer.msg(res.msg, {icon: 5,time:1000});
                }
            });
        });
    });

    //添加文件夹
    $('#btnPlusFolder').click(function () {
        var path = $('#tvFP').text();
        //弹出输入文件夹名称
        layer.prompt({title: '文件夹名称',value: ''},function(text, index){
            layer.load(2,{time:5});
            $.ajax({
                url:'addFolder',
                data:{dir_path:path,dir_name:text},
                dataType:'json',
                type:'POST',
                success:function(res){
                    layer.closeAll('loading');
                    layer.close(index);
                    if(res.code === 0){
                        layer.msg('操作成功',{icon:1,time:1000});
                        renderList();
                    }else{
                        layer.msg(res.msg,{icon:5,time:1000});
                    }
                }
            })
        });
    });






});

