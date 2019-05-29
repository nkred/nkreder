<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:32:"../Theme/admin/student\save.html";i:1557566151;s:42:"E:\php\loan\Theme\admin\Public\header.html";i:1554530736;s:42:"E:\php\loan\Theme\admin\Public\footer.html";i:1534405538;}*/ ?>
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
<nav class="breadcrumb">
	<a class="btn btn-primary radius" style="line-height:1.6em;margin-top:-5px" href="<?php echo url('index'); ?>">返回</a>
	<span class="c-gray en">修改学生信息</span>
	<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="page-container">
	<form action="<?php echo url('save'); ?>"  method="post" class="form form-horizontal" id="form-category-add" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
		<div id="tab-category" class="HuiTab">
			<div class="tabCon">
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						姓名：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" class="input-text" value="<?php echo $data['name']; ?>" placeholder="" name="name">
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						考号：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" class="input-text" value="<?php echo $data['card']; ?>" placeholder="" name="card">
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						性别：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<?php if($data['sex'] == 1): ?>
							<input type="radio" value="1" placeholder="" name="sex" checked>男
						<?php else: ?>
							<input type="radio" value="1" placeholder="" name="sex">男
						<?php endif; if($data['sex'] == 0): ?>
							<input type="radio" value="0" placeholder="" name="sex" checked>女
						<?php else: ?>
							<input type="radio" value="0" placeholder="" name="sex">女
						<?php endif; ?>
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						院系专业：</label>
					<div class="formControls col-xs-4 col-sm-4">
						<span class="select-box">
						<select class="select" id="sel_Sub2" name="">
							<?php foreach($class as $li): if($li['id'] == $data['pid']): ?>
									<option value="<?php echo $li['id']; ?>" selected><?php echo $li['name']; ?></option>
								<?php else: ?>
									<option value="<?php echo $li['id']; ?>"><?php echo $li['name']; ?></option>
								<?php endif; endforeach; ?>
						</select>
						</span>
					</div>
					<div class="formControls col-xs-4 col-sm-4">
						<span class="select-box">
						<select class="select" id="sel_Sub1" name="m_id">
							<?php foreach($list as $li): if($li['id'] == $data['pid']): ?>
									<option value="<?php echo $li['id']; ?>" selected><?php echo $li['name']; ?></option>
								<?php else: ?>
									<option value="<?php echo $li['id']; ?>"><?php echo $li['name']; ?></option>
								<?php endif; endforeach; ?>
						</select>
						</span>
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						毕业学校：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" class="input-text" value="<?php echo $data['byxx']; ?>" placeholder="" name="byxx">
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						录取分数：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" class="input-text" value="<?php echo $data['lqfs']; ?>" placeholder="" name="lqfs">
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						年级：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" class="input-text" value="<?php echo $data['nj']; ?>" placeholder="" name="nj">
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						学生类别：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" class="input-text" value="<?php echo $data['xslb']; ?>" placeholder="" name="xslb">
					</div>
					<div class="col-3">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-3">
						<span class="c-red">*</span>
						学制：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" class="input-text" value="<?php echo $data['xz']; ?>" placeholder="" name="xz">
					</div>
					<div class="col-3">
					</div>
				</div>
			</div>
		</div>
		<div class="row cl">
			<div class="col-9 col-offset-3">
				<button class="btn btn-primary radius" type="submit"> &nbsp;&nbsp;提交&nbsp;&nbsp;</button>
				<!--<input class="btn btn-primary radius" type="submit" value="">-->
			</div>
		</div>
	</form>
</div>
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
<script type="text/javascript">
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});

	$("#tab-category").Huitab({
		index:0
	});
	// $("#form-category-add").validate({
	// 	rules:{
	//
	// 	},
	// 	onkeyup:false,
	// 	focusCleanup:true,
	// 	success:"valid",
	// 	// submitHandler:function(form){
	// 	// 	//$(form).ajaxSubmit();
	// 	// 	var index = parent.layer.getFrameIndex(window.name);
	// 	// 	//parent.$('.btn-refresh').click();
	// 	// 	// parent.layer.close(index);
	// 	// }
	// });
});
$(function(){
	var ue = UE.getEditor('editor');
});
function check(obj){

    var checked=$(obj).is(':checked');
    var controller=$(obj).attr('controller');

    if(controller == '#'){ //下面全部选中或全部取消
        if(checked){ //全部选中

            var input=$(obj).parent().parent().next().find('input');
            input.each(function(){
                $(this).prop("checked",true);

            })

        }else{ //全部取消选中
            var input=$(obj).parent().parent().next().find('input');
            input.each(function(){
                $(this).prop("checked",false);

            })
        }

    }

    var length=$('.hierarchyOne ul').length;
    $('.hierarchyOne ul').each(function(i){
        var tue=false;
        var oneThis=$('.hierarchyOne ul').eq(length-i-1);
        oneThis.find('input').each(function(){
            if($(this).is(':checked')){
                tue=true;
                return false;
            }
        })
        if(tue){
            var input=oneThis.prev().find('input');
            if(!input.is(':checked')){
                input.prop('checked',!input.is(':checked'));
            }
        }else{
            var input=oneThis.prev().find('input');
            if(input.is(':checked')){
                input.prop('checked',!input.is(':checked'));
            }
        }
    })
}
$(function(){
    $("#sel_Sub2").change(function () {
        var opt=$("#sel_Sub2").val();
        $("#sel_Sub1").empty();
        if (opt != 0){
            $.ajax({
                type: 'Get',
                url: "<?php echo url('ss'); ?>",
                data:{id:opt},
                success:function(res){
                    var str = '';
                    for(var i= 0 ;;i++) {
                        var data = res[i];
                        if (data == undefined) {
                            // console.log(str);
                            $("#sel_Sub1").append(str);
                            return;
                        }
                        // console.log(data);
                        // return;
                        str += '<option value="'+data.id+'">'+data.name+'</option>';
                    }
                }
            });
        }else {
            str = '<option value="0">不限</option>';
            $("#sel_Sub1").append(str);
        }
    });
});

</script>