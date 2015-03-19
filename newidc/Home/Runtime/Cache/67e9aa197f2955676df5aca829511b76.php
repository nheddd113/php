<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title><?php echo ($title); ?></title>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/hostinfo.css" />
<script type="text/javascript" src="__PUBLIC__/js/hostinfo.js"></script>
<script src="__PUBLIC__/js/artDialog/jquery.artDialog.js?skin=blue"></script>
<script type="text/javascript" src="__PUBLIC__/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
var getMainUrl = "<?php echo U('Home/Ajax/index');?>"
var ajaxUrl = "<?php echo U('Home/Ajax/subIndex');?>";
var seatUrl = "<?php echo U('Home/Ajax/seatid');?>";
var chkStaUrl = "<?php echo U('Home/Ajax/checkState');?>";
var getHostUrl = "<?php echo U('Home/Ajax/getHostInfo');?>";
var switchInfoUrl = "<?php echo U('Home/Ajax/handleHost');?>";
var seatStatUrl = "<?php echo U('Home/Ajax/seatState');?>";
var getVirHostUrl = "<?php echo U('Home/Ajax/getVirhostCount');?>";
var updateUuidUrl = "<?php echo U('/Ajax/updateuuid');?>";
var getSinfo = "<?php echo U('/Ajax/getSinfo');?>";
var syncUrl = "<?php echo U('/Ajax/syncAll');?>";
</script>
</head>
<body>
<div class="hostinfo">
  <table width="100%" style="BACKGROUND-COLOR:#D55F17">
    <form target="pageContent" name="chgserver" id="chgserver" method="post" action="<?php echo U('Home/Hostinfo/changeHost');?>" onsubmit="return check_change_item()">
      <tr>
        <td width="100%" colspan="2" style="BACKGROUND-COLOR: #cae8ea;font-weight:bold;">修改服务器</td>
        <td width="100%" colspan="2" style="BACKGROUND-COLOR: #cae8ea;font-weight:bold; text-align:right"><span id="showIsHost1">宿主机IP：<?php echo ($superip['mainip']); ?></span>
        	<a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$hostinfo['parentid']));?>" id="showIsHost" >查看宿主机</a>
        </td>
      </tr>
      <tr>
        <td align="right" id="tdbg" >资产编号：</td>
        <td style="width:30%"><div>
        <input type="hidden" value="<?php echo ($hostinfo["id"]); ?>" name='id' id="id"/>
        <input type="hidden" value="<?php echo ($hostinfo["parentid"]); ?>" name='parentid' id="parentid"/>
        <input type="text" name="hostid" value="<?php echo ($hostinfo["hostid"]); ?>" readonly="readonly" id="hostid" style="background-color:#D3D3D3;height:25px;vertical-align:middle;">
          服务编码：
          <input type="text" name="sertag" maxlength="16" value="<?php echo ($hostinfo["sertag"]); ?>" id="" readonly="readonly" style="width:100px;height:25px;background-color:#D3D3D3;vertical-align:middle;"></div>
        </td>
        <td align="right" id="tdbg">机   位：</td>
        <td style="width:30%"><span style="padding-left:15px;">机柜:</span>
        	<input type="hidden" id="h_cupid" name='h_cupid' value="<?php echo ($hostinfo["cupid"]); ?>"/>
			  <select style="width:100px;" url="" id="cupid" name="cupid" class="<?php echo ($hostinfo["cupid"]); ?>">
			  		<option  value="0" selected >=请选择=</option>
			  		<?php if(is_array($cupBorard)): foreach($cupBorard as $key=>$value): ?><option value="<?php echo ($value["id"]); ?>"><?php echo ($value["cupname"]); ?></option><?php endforeach; endif; ?>
              </select>
			  <span style="padding-left:15px;">机位:</span>
			  <input type="hidden" id="h_seatid" name='h_seatid' value="<?php echo ($hostinfo["seatid"]); ?>"/>
			  <select style="width:80px;" url="" id="seatid" name="seatid" class="<?php echo ($hostinfo["seatid"]); ?>">
			      <option  value="0">=请选择=</option>
			      <?php if(is_array($seatInfo)): foreach($seatInfo as $key=>$value): ?><option value="<?php echo ($value["seatid"]); ?>"><?php echo ($value["seatid"]); ?></option><?php endforeach; endif; ?>
              </select>
			  </td>
      </tr>
      <tr>
        <td align="right" id="tdbg">电信IP：</td>
        <input type="hidden" id="h_mainip" name="h_mainip" value="<?php echo ($hostinfo["mainip"]); ?>" />
        <td><input autoComplete="off" id="mainip" type="text" value="<?php echo ($hostinfo["mainip"]); ?>" class="mainip" name="mainip" style="position: relative ;height:23px;width:180px;" /></td>
        <div  class="style2" id="ajaxShowIp"></div>
        <td align="right" id="tdbg">网通IP：</td>
        <td><input id="inputtype" type="text" value="<?php echo ($hostinfo["subip"]); ?>" class="subip" name="subip" /></td>
      </tr>
      <tr>
        <td align="right" id="tdbg">内网IP：</td>
        <td><input id="inputtype" type="text" value="<?php echo ($hostinfo["innip"]); ?>" class="innip" name="innip" /></td>
        <td align="right" id="tdbg">状   态：</td>
        <td><select class="<?php echo ($hostinfo["status"]); ?>" id="status" name="status" style="width:180px;height:23px;">
            <option value="1">闲置</option>
            <option value="2">上架</option>
            <option value="0">下架</option>
          </select>
        </td>
      </tr>
      <script>
      	var status = $("#status").attr('class')
      	$("#status").val(status);
	 </script>
      <tr>
        <td align="right" id="tdbg">硬   盘：</td>
        <td><input id="inputtype" type="text" value="<?php echo ($hostinfo["disk"]); ?>GB" name="disk" /></td>
        <td align="right" id="tdbg">类   型：</td>
        <td><select id="hostype" class="<?php echo ($hostinfo["hostype"]); ?>" style="width:180px;height:23px;" onchange=""  name="hostype" >
            <option value="0">非运营</option>
            <option value="1">申请</option>
            <option value="2">运营</option>
          </select>
      <script>
		var hostype = chgserver.hostype.className;
		var sel = chgserver.hostype;
		sel[hostype].selected=true;
	 </script>
        </td>
      </tr>
      <tr>
        <td align="right" id="tdbg">内   存：</td>
        <td><input id="inputtype" type="text" value="<?php echo ($hostinfo["mem"]); ?>GB" name="mem" /></td>
        <td align="right" id="tdbg">C P U：</td>
        <td><input id="inputtype" type="text"  value="<?php echo ($hostinfo["cpu"]); ?>" name="cpu" /></td>
      </tr>
      <tr>
        <td align="right" id="tdbg">备   注：</td>
        <td><input id="inputtype" type="text"  value="<?php echo ($hostinfo["remark"]); ?>" name="remark" /></td>
        <td align="right" id="tdbg">机   房：</td>
        <td>
        	<select name="house" id="houseInfo" disabled style="width:180px;height:23px;">
        		<?php if(is_array($houseInfo)): foreach($houseInfo as $key=>$housevalue): ?><option <?php if(($housevalue["id"]) == $hostinfo[houid]): ?>selected<?php endif; ?> value='<?php echo ($housevalue["id"]); ?>'><?php echo ($housevalue["houname"]); ?></option><?php endforeach; endif; ?>
        	</select>
        	<input id="houid" readonly type="hidden" value="<?php echo ($hostinfo[houid]); ?>" name="houid" />
        </td><!--
        <script>
        	var houid = $("input[name='houid']").val();
        	$('#houseInfo option[value='+houid+']').attr('selected',true);
        	$('#houseInfo').attr('disabled',true);
        </script>
      --></tr>
      <tr>
        <td align="right" id="tdbg">运营商：</td>
        <td><select name = 'owner' style='width:180px;height:23px;' id = 'owner'>
			<option value="0" >=请选择=</option>
			<?php if(is_array($OpsArray)): foreach($OpsArray as $key=>$OpsValue): ?><option value='<?php echo ($OpsValue["seq"]); ?>'><?php echo ($OpsValue["name"]); ?></option><?php endforeach; endif; ?>
          </select> <input type="text" name="searchops" id="searchops" > <span style="color:red;vertical-align: middle;">平台名快速搜索</span>
          <input type="hidden" name="h_owner" id="h_owner" value="<?php echo ($hostinfo["owner"]); ?>" />

        </td>
        <td align="right" id="tdbg">运营时间：</td>
        <td><input id="inputtype" type="text" value="<?php echo ($hostinfo["starttime"]); ?>" name="starttime" readonly/></td>
      </tr>
      <tr>
        <td align="right" id="tdbg">预定运营时间：</td>
        <td><input id="inputtype" type="text" name="pretime" value="<?php echo ($hostinfo["pretime"]); ?>"  onClick="WdatePicker()"/></td>
        <td align="right" id="tdbg">修改时间：</td>
        <td><input id="inputtype" type="text" name="changetime" readonly value="<?php echo (date('Y-m-d H:i:s',$hostinfo["changetime"])); ?>" /></td>
      </tr>
      <tr>
        <td align="right" id="tdbg">服务器类型：</td>
        <td>
        <input type="hidden" name="h_ishost" id="h_ishost" value="<?php echo ($hostinfo["ishost"]); ?>" />
        <select name="ishost" style="width:180px;height:23px;" id="ishost">
        	<?php if(is_array($ishostArr)): foreach($ishostArr as $key=>$value): ?><option value="<?php echo ($key); ?>"><?php echo ($value); ?></option><?php endforeach; endif; ?>
        </select>
        </td>

        <td align="right" id="tdbg">是否托管：</td>
        <td><input type="radio" name="ismanager" id="ismanager1" value="1"/>
          <label for="ismanager1">托管</label>
          &nbsp;<input type="radio" name="ismanager" id="ismanager0"  value="0"/>
          <label for="ismanager0">非托管</label>
          &nbsp;&nbsp;&nbsp;<font color="red">(托管是国外 非托管是国内)</font> </td>
          <input type="hidden" id="h_ismanager" value="<?php echo ($hostinfo["ismanager"]); ?>" />
      </tr>
      <tr>
        <td align="right" id="tdbg">所属用途：</td>
        <td><select id="gameid" class="<?php echo ($hostinfo["gameid"]); ?>" name="gameid" style="width:180px;height:23px;">
            <option value="0" selected="selected">=请选择=</option>
            <?php if(is_array($gameCode)): foreach($gameCode as $key=>$game): ?><option value="<?php echo ($game["id"]); ?>" ><?php echo ($game["name"]); ?></option><?php endforeach; endif; ?>
          </select>
        </td>
        <script>
			var gameid = chgserver.gameid.className;
			var gamesel = chgserver.gameid;
			gamesel.selected=false;
			gamesel[gameid].selected=true;
	  </script>
        <td align="right" id="tdbg">操作系统：</td>
        <td><input id="inputtype" type="text"  value="<?php echo ($hostinfo["system"]); ?>" name="system" /></td>
      </tr>
       <tr>
        <td align="right" id="tdbg">创建时间：</td>
        <td ><input id="inputtype" type="text" name="createtime" disabled value="<?php echo ($hostinfo["createtime"]); ?>" /></td>
        <td align="right" id="tdbg">Salt UUID：</td>
        <td ><input id="inputtype" type="text" name="uuid" readonly value="<?php echo ($hostinfo["uuid"]); ?>" />
          <?php if(empty($hostinfo['uuid'])): ?><input name="getuuid" type="button" value="获取UUID" onclick="return updateuuid(<?php echo ($hostinfo['id']); ?>);"><?php endif; ?>
        </td>
      </tr>
      <!--<tr>
        <td align="right" id="tdbg">主机名：</td>
        <td ><input id="inputtype" type="text" name="hostname" disabled value="" /></td>
        <td align="right" id="tdbg">所运行的游戏：</td>
        <td ><input id="inputtype" type="text" name="rungame" readonly value="<?php echo L($serverInfo[gamename]);?>" /></td>
      </tr>-->
      <tr>
        <td colspan="2" align="right">
            <input type="button" name="sync_all" id="sync_all" value="同步salt文件" onclick="return sycn_all(<?php echo ($hostinfo["id"]); ?>);"/>
        </td>
        <td colspan="2" align="right">
        <a href="<?php echo U('Home/Create/addVirHost',array('id'=>$hostinfo['id']));?>" target="pageContent" style="margin-right:40px;">
        <input type="button" name="add_vir" id="add_vir" value="添加虚拟机" style="width:100px;"></a>
        <input type="submit" name="sub" value="修 改" style="width:80px;margin-right:40px;"/></td>
      </tr>
    </form>
    





    <form action="__URL__/systemOp" enctype="application/x-www-form-urlencoded" method="post" name="system" id="system">
      <tr  style="color:#4d6b72;font-weight:bold">
        <td colspan="4" width="464" height="24" align="left" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;">服务器配置操作</td>
      </tr>
      <tr>
        <td width="90" height="25" align="right" id="tdbg">系统维护：</td>
        <td width="147"  align="left" style="padding-left:5px">
        <table width="100%" border="1">
            <tr style = "height:20px;">
              <td style="height:33%;">
                <select name="chaozuo" id="chaozuo" style="width:105px;" onchange="">
                  <option value="0">=请选择=</option>
                  <option value="4">关闭游戏</option>
                  <option value="5">开启游戏</option>
                  <option value="6">详细信息</option>
                  <option value="1">清档</option>
                  <option value="2">下架</option>
                  <option value="3">寄出</option>
                </select>
              </td>
              <td style="width:33%">
              <select name="jichujifang" id="jichujifang" style="width:180px;height:23px;">
              	<?php if(is_array($houseInfo)): foreach($houseInfo as $key=>$housevalue): ?><option value='<?php echo ($housevalue["id"]); ?>'><?php echo ($housevalue["houname"]); ?></option><?php endforeach; endif; ?>
              </select>
              </td>
              <td style="width:33%">&nbsp;</td>
            </tr>
          </table>
        </td>
        <td align="right" id="tdbg">说&nbsp;&nbsp;&nbsp;明：</td>
        <td>
            <input type="text" name="content" value="" id="content" style="width:180px;height:23px;"/>
            <input align="right"  type="button" style="margin-left:30px;width:80px;" onclick="systemOp(<?php echo ($hostinfo["id"]); ?>);" value="确  定"/>
        </td>
      </tr>
    </form>
    
    <form action="" enctype="application/x-www-form-urlencoded" method="post" name="">
      <tr style="color:#4d6b72;font-weight:bold">
        <td height="24" colspan="4" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea; " align="left">付费操作</td>
      </tr>
      <tr>
        <td align="right" id="tdbg">开始时间:</td>
        <td align="left"><input type="text"  name="staTime" id="staTime" value="2013-08-02 17:34:04" maxlength="19"  onClick="WdatePicker()"/>
          &nbsp;&nbsp;<font color="blue">示例:2013-08-02 17:34:04</font> </td>
        <td align="right" id="tdbg">类&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;型:</td>
        <td align="left"><select name="payBean.paType" id="payBean.paType" style="width:180px;height:23px;" onchange="comTypeClick()">
            <option value="0">新交</option>
            <option value="1">续费</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="right" id="tdbg">支付时间:</td>
        <td align="left"><input type="text" maxlength="19" readonly="readonly" name="payTime" id="payTime" value="2013-08-02 17:34:04"  onClick="WdatePicker()"/>
        </td>
        <td align="right" id="tdbg">金&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额:</td>
        <td align="left"><input type="text" name="" value="" id=""/>
          (￥) </td>
      </tr>
      <tr>
        <td align="right" id="tdbg">结束时间:</td>
        <td align="left"> 月数:
          <select id="timeJump" name="timeJump" onchange="monthChange()">
            <option value="0">=请选择=</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
          时间:
          <input type="text" name="enTime" maxlength="19" value="" readonly="readonly" id="enTime"/>
        </td>
        <td align="right" id="tdbg">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注:</td>
        <td align="left"><input type="text" name="" value="" id=""/>
          <input value="确   定" type="button" class="" onclick=""/></td>
      </tr>
    </form>
    
  </table>
  </div>
  <div id="virHostList">
  <table class="vir_list" width="100%" align="left" >
  <tr style="height:25px;">
      <td colspan="2" align="left" width="50%">虚拟机列表:
        <input type="button" value="显示/隐藏虚拟机列表" id="hide_vir_list" style="width:140px;"/>
      </td>
    </tr>
</table>
<br />

  <table class="vir_list" width="100%" id="vir_list_2">
  <tr  style="color:#4d6b72;font-weight:bold;height:25px;">
		                  <td height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">序号</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;width:150px;" align="center"> 虚拟机标识码</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;width:150px;" align="center">电信IP</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">所属用途</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">所在位置</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">硬盘</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">CPU</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">内存</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">运营商</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;width:200px;" align="center">创建时间</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">状态</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;width:200px;" align="center">描述</td>
		                  <td style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;width:130px;" align="center">操作</td>
		                </tr>
		                <?php if(is_array($virHistInfo)): foreach($virHistInfo as $key=>$virList): ?><div style="display:none"><?php echo ($classid = $key % 2 + 1); ?></div>
		                	<tr id="<?php echo ($classid); ?>" class="tr_<?php echo ($classid); ?>" name="tr_<?php echo ($classid); ?>"><td><?php echo ($key+1); ?></td>
		                		<td><a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$virList['id']));?>"><?php echo ($virList["hostid"]); ?></a></td>
		                		<td><a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$virList['id']));?>"><?php echo ($virList["mainip"]); ?></a></td>
		                		<td><?php echo ($virList["gamename"]); ?></td>
		                		<td><?php echo ($virList["housename"]); ?></td>
		                		<td><?php echo ($virList["disk"]); ?></td>
		                		<td><?php echo ($virList["cpu"]); ?></td>
		                		<td><?php echo ($virList["mem"]); ?></td>
		                		<td><?php echo ($virList["ownername"]); ?></td>
		                		<td><?php echo ($virList["createtime"]); ?></td>
		                		<td><?php echo ($virList["status"]); ?></td>
		                		<td><?php echo ($virList["remark"]); ?></td>
		                		<td>
		                			<a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$virList['id']));?>">[详细]</a>
		                			<a href="" onclick="return deleteHost({id:<?php echo ($virList['id']); ?>,url:'<?php echo U(APP_NAME . '/Hostinfo/deleteHost','','');?>'});" >[删除]</a>

		                		</td>
		                	</tr><?php endforeach; endif; ?>
  </table>
  </div>
   <script>
      	var ishost = $("input[name='ishost']:checked").val();
      	if(ishost == 0){
      		$("#vir_list_1").hide();
      		$("#vir_list_2").hide();
      	}else{
      		$("#vir_list_1").show();
      		$("#vir_list_2").show();
      	}
      </script>
  <table width="100%" align="left" style="font-size:12px;margin-top:10px;">

  <tr style="height:25px;">
      <td colspan="2" align="left" width="50%">服务器日志列表:
        <input type="button" value="隐藏日志列表" id="hide_log_list" style="width:90px;"/>
      </td>
      <td colspan="2" align="right" width="50%">数据显示方式：
        <select id="sreachType" onchange="disTypeSubmit()">
          <option value="1" >根据IP查看</option>
          <option value="2" selected = selected>根据ID查看</option>
        </select></td>
    </tr>
</table><br />
  <table width="100%" align="left" style="font-size:12px;" id="logshow">
    <tr  style="color:#4d6b72;font-weight:bold;">
      <td width="36" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">序号</td>
      <td width="70" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">IP</td>
      <td width="70" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">资产编码</td>
      <td width="70" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">查看被更换服务器</td>
      <td width="50%" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">操作内容</td>
      
      <td width="100" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">操作时间</td>
      <td width="79" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" align="center">操作者</td>
    </tr>
    <?php if(is_array($log_data)): foreach($log_data as $key=>$value): ?><div style="display:none"><?php echo ($classid = $key % 2 + 1); ?></div>
    <tr style="height:20px; font-size:12px;" class="tr_<?php echo ($classid); ?>" id="<?php echo ($classid); ?>">
      <td height="20" align="center"><?php echo ($key+1); ?></td>
      <td align="center"><?php echo ($value["serip"]); ?></td>
      <td align="center"><?php echo ($value["hostid"]); ?></td>
      <td align="center"><a href="__URL__/hostInfo/hostid/<?php echo ($value["chghostid"]); ?>"><?php echo ($value["chghostid"]); ?></a></td>
      <td align="left"><?php echo ($value["content"]); ?></td>
      <td align="center"><?php echo ($value["logtime"]); ?></td>
      <td align="center"><?php echo ($value["handler"]); ?></td>
    </tr><?php endforeach; endif; ?>
	<tr><td colspan="7" style="BACKGROUND-COLOR:#FFF;text-align:right;padding-right:30px;"><?php echo ($page); ?></td></tr>
  </table>
  <p>&nbsp;</p><p>&nbsp;</p>


  <table width="100%" align="left" style="font-size: 12px;border:hidden;float:right;">
        <tr>
          <td align="left" width="100%" colspan="10">付费记录列表:
            <input type="button" value="隐藏付费列表" class="common_button" style="width:90px;margin-left:13px;" onclick="payDisAndHidden(this)"/></td>
        </tr>
              <tr style="color:#4d6b72;font-weight:bold">
                <td width="4%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align="left">序号</div></td>
                <td width="10%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>机柜名称</div></td>
                <td width="12%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>开始时间</div></td>
                <td width="12%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>结束时间</div></td>
                <td width="12%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>支付时间</div></td>
                <td width="10%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>支付类型</div></td>
                <td width="8%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>支付金额(￥)</div></td>
                <td width="10%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>操作者</div></td>
                <td width="10%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>备注</div></td>
                <td width="10%" height="20" style="padding-top:3px;BACKGROUND-COLOR: #cae8ea;" ><div align=center>操作</div></td>
              </tr>
              <tr>
                <td height="20" colspan="10" align="center" class="tr_1" id="1">对不起，没有记录</td>
              </tr>
            </table>
  </tr>
  </table>
</body>
</html>