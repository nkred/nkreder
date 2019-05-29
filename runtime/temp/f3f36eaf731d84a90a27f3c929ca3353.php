<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:31:"../Theme/admin/index\index.html";i:1557399190;s:42:"E:\php\loan\Theme\admin\Public\header.html";i:1554530736;s:42:"E:\php\loan\Theme\admin\Public\footer.html";i:1534405538;}*/ ?>
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
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?php echo url('index/index'); ?>">新生报到系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/aboutHui.shtml">H-ui</a>
			<span class="logo navbar-slogan f-l mr-10 hidden-xs">v1.0</span>
		<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
			<ul class="cl">
				<li class="dropDown dropDown_hover">
					<?php echo $user['name']; ?>
					<a href="#" class="dropDown_A"> <i class="Hui-iconfont">&#xe6d5;</i></a>
					<ul class="dropDown-menu menu radius box-shadow">
						<!--&lt;!&ndash; <li><a href="javascript:;" onClick="myselfinfo()">个人信息</a></li>-->
						<li><a href="#" onclick="public_edit('修改密码','<?php echo url('save'); ?>','800','500')">修改密码</a></li>
						<li><a href="<?php echo url('doExit'); ?>">退出</a></li>
				</ul>
			</li>
				<!--<li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li>-->
				<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
					<ul class="dropDown-menu menu radius box-shadow">
						<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
						<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
						<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
						<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
						<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
						<li><a href="javascript:;" data-val="orange" title="橙色">橙色</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</div>
</header>
<aside class="Hui-aside">
	<div class="menu_dropdown bk_2">
		<?php
		function goods_class_nav($data){
				echo '<ul class="nav nav-second-level">';
		foreach ($data as $k=>$v){
		if(count($v['class']) > 0){
		echo '<dt><i class="Hui-iconfont">&#xe616;</i> '.$v['html'].$v['name'].'<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>';
		echo '<dd>';
		echo '<ul>';
			goods_class_nav($v['class']);
			echo '</ul>';
		echo '</dd>';
		}else{
		echo '<li><a id="z_'.$v['id'].'" data-href="'.url('goods/index',['goods_id'=>$v['id']]).'" data-title="'.$v['html'].$v['name'].'" href="javascript:void(0)">'.$v['html'].$v['name'].'</a></li>';
		}
		}
		//echo '</ul>';
		return;
		}
			function linkKJ($data,$ontrue=false){
				foreach ($data as $k=>$v){
					if(count($v['class']) > 0){
						echo'<dl id="menu-'.$v['id'].'">';
						if($ontrue){
								echo '<li><a id="z_'.$v['id'].'" data-href="'.url(''.$v['controller'].'/'.$v['action'].'').'" data-title="'.$v['html'].$v['name'].'" href="javascript:void(0)">'.$v['html'].$v['name'].'</a></li>';
						}else{
								echo '<dt><i class="Hui-iconfont">&#xe616;</i> '.$v['html'].$v['name'].'<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>';
						}
					echo '<dd>';
					echo '<ul>';
					linkKJ($v['class'],true);
					echo '</ul>';
					echo '</dd>';
					echo '</dl>';
					}else{
						if($ontrue){
							if($v['controller'] == 'Goods'){ //如果是产品分类
									goods_class_nav($goodsclass);
							}else{
								echo '<li><a id="z_'.$v['id'].'" data-href="'.url(''.$v['controller'].'/'.$v['action'].'').'" data-title="'.$v['html'].$v['name'].'" href="javascript:void(0)">'.$v['html'].$v['name'].'</a></li>';
							}
						}else{
							echo '<dt><li style="list-style:none;"><a id="z_'.$v['id'].'" data-href="'.url(''.$v['controller'].'/'.$v['action'].'').'" data-title="'.$v['html'].$v['name'].'" href="javascript:void(0)" style="font-weight: normal;">'.$v['html'].$v['name'].'</a></li></dt>';
						}
					}
				}
			return;
		}
		linkKJ($link,false);
		?>
</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active">
					<span title="我的桌面" data-href="welcome.html">我的桌面</span>
					<em></em></li>
		</ul>
	</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0"
					src="<?php echo url('welcome'); ?>"
			></iframe>
	</div>
</div>
</section>

<div class="contextMenu" id="Huiadminmenu">
	<ul>
		<li id="closethis">关闭当前 </li>
		<li id="closeall">关闭全部 </li>
</ul>
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