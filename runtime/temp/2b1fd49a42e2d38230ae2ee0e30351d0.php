<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:35:"../Theme/admin/dormitory\index.html";i:1554716136;s:42:"E:\php\loan\Theme\admin\Public\header.html";i:1554530736;s:42:"E:\php\loan\Theme\admin\Public\footer.html";i:1534405538;}*/ ?>
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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
	<span class="c-gray en">&gt;</span>
	宿舍管理
	<span class="c-gray en">&gt;</span>
	宿舍信息
	<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="page-container">
	<!--<div class="text-c">-->
		<!--<input type="text" name="" id="" placeholder="栏目名称、id" style="width:250px" class="input-text">-->
		<!--<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>-->
	<!--</div>-->
	<div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="l">
		<a class="btn btn-primary radius" href="<?php echo url('add'); ?>"><i class="Hui-iconfont">&#xe600;</i> 添加宿舍</a>
		</span>
		<!--<span class="r">共有数据：<strong>54</strong> 条</span>-->
	</div>
	<div class="mt-20">
		<form action="" method="post" id="Myform">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
					<th>宿舍</th>
					<th>人数</th>
					<th>入住人数</th>
					<th>时间</th>
					<th width="100">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($data as $li): ?>
				<tr class="text-c">
					<td><input type="checkbox" name="id[]" value="<?php echo $li['id']; ?>"></td>
					<td><?php echo $li['id']; ?></td>
					<td><?php echo $li['name']; ?></td>
					<td><?php echo $li['num']; ?></td>
					<td><?php echo $li['l_num']; ?></td>
					<td><?php echo date("Y-m-d",$li['time']); ?></td>
					<td class="f-14"><a title="编辑" href="<?php echo url('save',array('id'=>$li['id'])); ?>"  style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
						<a title="删除" href="javascript:;" onclick="public_del('<?php echo $li['id']; ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
						<a title="清空" href="javascript:;" onclick="public_empty('<?php echo $li['id']; ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		</form>
	</div>
</div>
<script type="text/javascript">
    function public_empty(id){
        layer.confirm('确认要清空吗？',function(index){
            $.ajax({
                type: 'Get',
                url: "<?php echo url('emptys'); ?>",
                data:{id:id},
                success: function(data){
                    res = JSON.parse(data);
                    if(res['status'] == 1){
                        layer.msg('已清空',{icon:1,time:1000},function () {
                            location.reload();
                        });
                    }else {
                        layer.msg('无法清空',{icon:1,time:1000},function () {
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
</script>
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