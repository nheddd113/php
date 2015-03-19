<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title>游奇IDC管理平台</title>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/top.css" />
</head>
<body>
<div class="indextop">
<img src="__PUBLIC__/images/logo.jpg">
<ul id="nav">
	<li><a href="" onclick="return false;">新建列表</a>
		<ul>
			<li><a href="<?php echo U(APP_NAME . '/Create/addHouse');?>" target="pageContent">新建机房</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Create/addCupboard');?>" target="pageContent">新建机柜</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Create/addHost');?>" target="pageContent">新建服务器</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Create/addIp');?>" target="pageContent">新建IP段</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Create/addGame');?>" target="pageContent">新建游戏</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Create/hostTempLate');?>" target="pageContent">新建主机模板</a></li>
		</ul>
	</li>
	<li><a href="" onclick="return false;">查看列表</a>
		<ul>
			<li><a href="<?php echo U(APP_NAME . '/Public/showip');?>" target="pageContent">IP列表</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Public/showHouse');?>" target="pageContent">机房列表</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Search/search',array('ishost'=>1));?>" target="pageContent">宿主机列表</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Public/showlog');?>" target="pageContent">日志列表</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Create/addOps');?>" target="pageContent">更新运营商</a></li>
		</ul>
	</li>
	<?php if(($_SESSION['level']) > "99"): ?><li><a href="" onclick="return false;">费用列表</a>
			<ul>
				<li><a href="<?php echo U(APP_NAME . '/Amount/index');?>" target="pageContent">费用查询</a></li>
			</ul>
		</li><?php endif; ?>
	<li><a href="" onclick="return false;">监控列表</a>
		<ul>
			<li><a href="<?php echo U(APP_NAME . '/Monitor/index');?>" target="pageContent">各国监控</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Monitor/addMonitor');?>" target="pageContent">增加监控</a></li>
			<li><a href="<?php echo U(APP_NAME . '/Public/cdn');?>" target="pageContent">CDN推送</a></li>
		</ul>
	</li>
	<li><a href="" onclick="return false;">用户管理</a>
		<ul>
			<?php if(($_SESSION['level']) > "99"): ?><li><a href="<?php echo U(APP_NAME . '/User/index');?>" target="pageContent">用户列表</a></li><?php endif; ?>
			<?php if(($_SESSION['level']) > "49"): ?><li><a href="<?php echo U(APP_NAME . '/User/adduser');?>" target="pageContent">增加用户</a></li><?php endif; ?>
			<li><a href="<?php echo U(APP_NAME . '/User/manager');?>" target="pageContent">修改密码</a></li>
		</ul>
	</li>
</ul>
  <div class="login">
  当前用户:<span style="color:blue">[<?php echo session('realname');?>][<a href="<?php echo U(APP_NAME . '/Login/logout');?>" alt="退出">退出</a>]</span>
  报警状态:<?php echo ($notify?'开启':'关闭'); ?>|<a href="<?php echo U(APP_NAME . '/Public/changeNotify',array('state'=>$notify));?>" ><?php echo ($notify?'关闭':'开启'); ?></a>
  </div>
</div>


<div class="jifang" id="jifang">
<h3 class="title">IDC后台管理</h3>
<?php if(is_array($house)): foreach($house as $key=>$value): ?><div class="list">
		<span>
		<input type="button" class="but" value="+" name="box<?php echo ($value["id"]); ?>" />
			<a href="<?php echo U(APP_NAME . '/Search/house/',array('houid'=>$value['id']));?>" target="pageContent"><?php echo ($value["houname"]); ?></a>
		</span>
	</div>
	<div class="jg" id="box<?php echo ($value['id']); ?>" style="display:none">
		<?php if(is_array($cupborad)): foreach($cupborad as $key=>$jg_name): if($value['id'] == $jg_name['houid']): ?><p><a href="<?php echo U(APP_NAME . '/Search/cupBoard',array('cupid'=>$jg_name['id'],'houid'=>$value['id']));?>" target="pageContent"><?php echo ($jg_name["cupname"]); ?></a></p><?php endif; endforeach; endif; ?>
	</div><?php endforeach; endif; ?>
</div>



<div class="count">
  <table border="0">
    <tr>
      <td style="width:50%"><table width="600"  border="0">
          <tr>
            <td >物理机:</td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search');?>" target="pageContent">总数(<?php echo ($count['notWCOUNT']+$count['WCOUNT']+$count['notWHOST']+$count['WHOST']); ?>)</a></td>
            <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td>非托管:</td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'ishost'=>0));?>" target="pageContent">总数(<?php echo ($count['notWCOUNT'] + $count['notWHOST']); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'status'=>1,'ishost'=>0));?>" target="pageContent">闲置(<?php echo ($count["notWXZ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'status'=>0,'ishost'=>0));?>" target="pageContent">下架(<?php echo ($count["notWXJ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'hostype'=>1,'ishost'=>0));?>" target="pageContent">申请(<?php echo ($count["notWSQ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'hostype'=>2,'ishost'=>0));?>" target="pageContent">运营(<?php echo ($count["notWYY"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'ishost'=>1));?>"  target="pageContent">宿主机(<?php echo ($count["notWHOST"]); ?>)</a></td>
          </tr>
          <tr>
            <td style="padding-left:15px;">托管:</td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'ishost'=>0));?>" target="pageContent">总数(<?php echo ($count['WCOUNT'] + $count['WHOST']); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'status'=>1,'ishost'=>0));?>" target="pageContent">闲置(<?php echo ($count["WXZ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'status'=>0,'ishost'=>0));?>" target="pageContent">下架(<?php echo ($count["WXJ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'hostype'=>1,'ishost'=>0));?>" target="pageContent">申请(<?php echo ($count["WSQ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'hostype'=>2,'ishost'=>0));?>" target="pageContent">运营(<?php echo ($count["WYY"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'ishost'=>1));?>"  target="pageContent">宿主机(<?php echo ($count["WHOST"]); ?>)</a></td>
          </tr>
        </table></td>
      <td rowspan="2">
          <form name="queryform" target="pageContent" action="<?php echo U(APP_NAME . '/Search/postHandle');?>" method="post" >
          <div class="form"> <input id="q_contnet" type="text" size="15" name="query" style="height:23px;" onfocus="this.value=''"/>
            <input type="submit" name="submit" value="查询" style="width:70px;"/>
          </div>
            <select name="query_type" size="1" id="query_type" style="height:23px;margin-top:30px;float:right">
              <option value="mainip" selected="selected">按IP查询</option>
              <option value="hostid" >按资产编号查询</option>
              <option value="sertag" >按服务编号查询</option>
              <option value="remark" >按备注查询</option>
              <option value="log" >查询日志</option>
            </select>
        </form>
     </td>
    </tr>
    <tr>
      <td><table width="600" border="0">
          <tr>
            <td style="width:95px;">虚拟机:</td>
            <td>总数(<?php echo ($count['notXCOUNT'] + $count['XCOUNT']); ?>)</td>
            <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td>非托管:</td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'ishost'=>2));?>" target="pageContent">总数(<?php echo ($count['notXCOUNT']); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'status'=>1,'ishost'=>2));?>" target="pageContent">闲置(<?php echo ($count["notXXZ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'status'=>0,'ishost'=>2));?>" target="pageContent">下架(<?php echo ($count["notXXJ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'hostype'=>1,'ishost'=>2));?>" target="pageContent">申请(<?php echo ($count["notXSQ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>0,'hostype'=>2,'ishost'=>2));?>" target="pageContent">运营(<?php echo ($count["notXYY"]); ?>)</a></td>
            <td style="visibility:hidden">宿主机()</td>
          </tr>
          <tr>
          	<td>托管:</td>
          	<td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'ishost'=>2));?>" target="pageContent"> 总数(<?php echo ($count['XCOUNT']); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'status'=>1,'ishost'=>2));?>" target="pageContent">闲置(<?php echo ($count["XXZ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'status'=>0,'ishost'=>2));?>" target="pageContent">下架(<?php echo ($count["XXJ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'hostype'=>1,'ishost'=>2));?>" target="pageContent">申请(<?php echo ($count["XSQ"]); ?>)</a></td>
            <td><a href="<?php echo U(APP_NAME . '/Search/search',array('ismanager'=>1,'hostype'=>2,'ishost'=>2));?>" target="pageContent">运营(<?php echo ($count["XYY"]); ?>)</a></td>
            <td style="visibility:hidden">宿主机()</td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>


<input type="hidden" value="<?php echo ($post); ?>" id="isPost" />
<script type="text/javascript">
	var isPost = $("#isPost").val();
	if(isPost == '1'){
		var astr = '<iframe id="pageContent" width="87%" scrolling="no" frameborder="0" name="pageContent" src="<?php echo U("Home/Search/searchHandle/",array("query_type"=>$where[query_type],"query"=>$where[query]));?>"></iframe>';
	}else{
		var astr = '<iframe id="pageContent" width="87%" scrolling="no" frameborder="0" name="pageContent" src="<?php echo U("Home/Search/search");?>"></iframe>';
	}
	document.write(astr);
</script>
</body>
</html>