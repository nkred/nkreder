<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:31:"../Theme/admin/login\index.html";i:1554530714;s:42:"F:\php\loan\Theme\admin\Public\header.html";i:1554530736;s:42:"F:\php\loan\Theme\admin\Public\footer.html";i:1534405538;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link rel="Bookmark" href="/favicon.ico" >
<link rel="Shortcut Icon" href="/favicon.ico" />
<link href="/static/admin/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/static/admin/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="/static/admin/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/static/admin/h-ui.admin/skin/default/skin.css" id="skin" rel="stylesheet" type="text/css"/>
<link href="/static/admin/h-ui.admin/css/H-ui.admin.css" rel="stylesheet" type="text/css"/>
<link href="/static/admin/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css" >
<title>新生报到系统</title>
</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form action="<?php echo url('login'); ?>" class="form form-horizontal" method="post">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
          <input name="id" type="hidden" >
          <input name="username" type="text" placeholder="账户" value="" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          <input name="password" type="password" placeholder="密码" value="" class="input-text size-L">
        </div>
      </div>
      <!--<div class="row cl">-->
          <!--<div class="formControls col-xs-8 col-xs-offset-3">-->
              <!--&lt;!&ndash;<input  class="input-text size-L" type="text" id="code_input" value="" placeholder="请输入验证码"  style="width: 150px; margin-bottom: 20px;"/>&ndash;&gt;-->
              <!--&lt;!&ndash;<div id="v_container" style="width: 225px;height: 50px;float: right;"></div>&ndash;&gt;-->
            <!--<input class="input-text size-L" type="text" name="code" placeholder="验证码"  style="width: 150px; margin-bottom: 20px;">-->
            <!--<img src="<?php echo captcha_src(); ?>" id="captimg" alt="captcha" onclick="kbq();"> </div>-->
      <!--</div>-->
      <!--<div class="row cl">-->
      <!--<div class="formControls col-xs-8 col-xs-offset-3">-->
      <!--<label for="online">-->
      <!--<input type="checkbox" name="online" id="online" value="">-->
      <!--使我保持登录状态</label>-->
      <!--</div>-->
      <!--</div>-->
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input name="" type="submit"class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>
    </form>
  </div>
</div>
<div class="footer">Copyright @ 重庆工商大学派斯学院</div>
<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/static/admin/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/static/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/zoom.js"></script>
<script type="text/javascript" src="/static/admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="/static/admin/lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script>
<script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript" src="/static/admin/lib/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="/static/admin/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="/static/admin/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="/static/admin/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script>
<script src="/static/admin/gVerify.js"></script>
<!-- 公用的方法 -->
<script type="text/javascript">
    function kbq() {
        var ts=Date.parse(new Date())/1000;
        $('#captimg').attr('src','<?php echo captcha_src(); ?>?id='+ts);
    }
/*个人信息*/
function public_sys_select(){
	layer.open({
		type: 1,
		area: ['300px','200px'],
		fix: false, //不固定
		maxmin: true,
		shade:0.4,
		title: '查看信息',
		content: '<div>管理员信息</div>'
	});
}
/*添加*/
function public_add(title,url,w,h){
	layer_show(title,url,w,h);
}
/*删除*/
    function public_del(id){
        layer.confirm('确认要删除吗？',function(index){
            $.ajax({
                type: 'Get',
                url: "<?php echo url('del'); ?>",
                data:{id:id},
                success: function(data){
                    res = JSON.parse(data);
                    if(res['status'] == 1){
                        layer.msg('已删除',{icon:1,time:1000},function () {
                            location.reload();
                        });
                    }else {
                        layer.msg('无法删除',{icon:1,time:1000},function () {
                            location.reload();
                        });
                    }
                },
                error:function(data) {
                    console.log(data.msg);
                },
            });
        });
    }

// 批量删除
    function Alldel() {
        if (confirm("真的要删除吗？")){
            $('#Myform').attr('action',"<?php echo url('Alldel'); ?>");
            $('#Myform').submit();
        }else{
            return false;
        }
    }
/*编辑*/
function public_edit(title,url,id,w,h){
	layer_show(title,url,w,h);

}
/*用户-查看*/
function public_show(title,url,id,w,h){
	layer_show(title,url,w,h);
}
    function public_stop(obj,id,url,status){
        layer.confirm('确认要停用吗？',function(index){
            //此处请求后台程序，下方是成功后的前台处理……
            $.ajax({
                url:url,
                data:{'id':id,'status':status},
                type:"POST",
                success:function(res){
                    if(res.code==1){
                        layer.msg('已停用!',{icon: 5,time:1000},function(){
                            window.location.reload();
                        });
                    }else{
                        layer.msg(res.msg);return false;
                    }
                }
            });
        });
    }

    /*启用*/
    function public_start(obj,id,url,status,title){
        layer.confirm('确认要'+title+'吗？',function(index){
            //此处请求后台程序，下方是成功后的前台处理……
            $.ajax({
                url:url,
                data:{'id':id,'status':status},
                type:"POST",
                success:function(res){
                    if(res.code==1){
                        layer.msg(res.msg, {icon: 1,time:1000},function(){
                            window.location.reload();
                        });
                    }else{
                        layer.msg(res.msg,{icon:2,time:1000});return false;
                    }
                }
            });
        });
    }

</script>
<!-- 用户注册列表 -->
<script type="text/javascript">
    $(function(){
        $('.table-sort').dataTable({
        });
    });
</script>

</body>
</html>