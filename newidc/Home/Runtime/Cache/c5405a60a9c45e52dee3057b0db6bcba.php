<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title><?php echo ($title); ?></title>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/addVirHost.css" />
<script type="text/javascript" src="__PUBLIC__/js/hostinfo.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/addVirHost.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/artDialog/jquery.artDialog.js?skin=blue"></script>
<script  type="text/javascript">
var getMainUrl = "<?php echo U('Home/Ajax/index');?>"
var ajaxUrl = "<?php echo U('Home/Ajax/subIndex');?>";
var searUrl = "<?php echo U('Home/Ajax/seatid');?>";
var chkStaUrl = "<?php echo U('Home/Ajax/checkState');?>";
var getHostUrl = "<?php echo U('Home/Ajax/getHostInfo');?>";
var switchInfoUrl = "<?php echo U('Home/Ajax/handleHost');?>";
var seatStatUrl = "<?php echo U('Home/Ajax/seatState');?>";
</script>
</head>
<body>
<div class="addVirDiv">
<form id="hostAction" name="virAddAction" action="<?php echo U('Home/Create/addVirHandle');?>" method="post" onsubmit="return addVirHost(this)">
	<table class="addHostTable"  border="1" cellspacing="3" cellpadding="3" width="600" align="left" style="font-size:12px;">
		<tr style="color:#4d6b72;font-weight:bold">
			<td height="24" colspan="2" style="BACKGROUND-COLOR: #cae8ea;text-align:left;padding-left:10px;">  添加虚拟机</td>
			<td colspan="2" style="BACKGROUND-COLOR: #cae8ea;padding-right:10px;text-align:right" >
		    <input type="hidden" name="" value="<?php echo ($hostinfo['id']); ?>" id="id"/>
		    <a id="" href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$hostinfo['id']));?>" target="pageContent">查看宿主机  </a></td>
		</tr>
		<tr style="background-color:#f7f8f9">
                  <th width="50" height="25" align="right">标识码：</th>
                  <td width="200" align="left" style="padding-left:5px">
                  	<input type="hidden" name="parentid" value="<?php echo ($hostinfo['id']); ?>" />
                  	<input type="text" name="hostid" maxlength="20" value="<?php echo ($hostinfo["hostid"]); ?>" readonly="readonly" id="hostid" class="textBack"/>
                  	<SPAN><font color="red">*</font></SPAN>
                  </td>
                   <th align="right">机&nbsp;&nbsp;&nbsp;房：</th>
                  <td align="left" style="padding-left:5px">
                  	<input type="hidden" name="houid" id="houid" value="<?php echo ($hostinfo["houid"]); ?>" />
                  	<input type="text" name="houname" value="<?php echo ($hostinfo["houname"]); ?>" readonly id="houseInfo" class="textBack"/>
                  </td>
               </tr>
                 <tr class="urlHref" style="background-color:#f7f8f9">
                  <th align="right">C&nbsp;P&nbsp;U：</th>
                  <td align="left" style="padding-left:5px">
                  	
                  	<input type="text" name="cpu" value="<?php echo ($hostinfo["cpu"]); ?>" readonly="readonly" id="cpu" class="textBack"/>
                  	<SPAN><font color="red">*</font></SPAN>
                  </td>
                  <th align="right">状&nbsp;&nbsp;&nbsp;态：</th>
                  <td align="left" style="padding-left:5px">
                  			<select  name="status" id="status" onchange="typeChange()" style="width:152px;;">
	                  			<option value="1" >闲置</option>
	                  			<option value="0" selected="selected">下架</option>
	                  		</select>
                  		<SPAN><font color="red">*</font></SPAN>
                  </td>
                </tr>
                <tr class="urlHref" style="background-color:#f7f8f9">
                  <th align="right">硬&nbsp;&nbsp;&nbsp;盘：</th>
                  <td align="left" style="padding-left:5px">
                  	<input type="text" name="disk" value="40GB" id="disk" />
                  	<SPAN><font color="red">*</font></SPAN>
                  </td>
                 <th height="25" align="right">内&nbsp;&nbsp;&nbsp;存：</th>
                  <td align="left" style="padding-left:5px">
                  	<input type="text" name="mem" value="" id="mem"/>
                  	<SPAN><font color="red">*</font></SPAN>
                  </td>
                </tr>
                <tr class="urlHref" style="background-color:#f7f8f9">
                  <th height="25" align="right">备&nbsp;&nbsp;&nbsp;注：</th>
                  <td  align="left" style="padding-left:5px" >
                  	<input type="text" name="remark" value="" id="remark"/>
                  </td>
                	<th align="right" height="25">所属游戏：</th>
                	<td  height="25" style="padding-left:5px;" class="textWhite">
                		  <select name="gameid" readonly id="gameid" style="width:152px;">
        						    <option value="0">=请选择=</option>
        						    <?php if(is_array($gameCode)): foreach($gameCode as $key=>$game): ?><option <?php if(($game["id"]) == $hostinfo["gameid"]): ?>selected<?php endif; ?> value="<?php echo ($game["id"]); ?>"><?php echo ($game["name"]); ?></option><?php endforeach; endif; ?>
            					</select>

                	</td>
                </tr>
                <tr>
                	<th align="right">批量添加：</th>
                	<td style="padding-left:5px;">
                		<input type="radio" name="isMany" id="isMany1" value="1" onclick="manyAddClick()"/>
                		<label for="isMany1">是</label>
        						<input type="radio" name="isMany" id="isMany2" checked="checked" value="0" onclick="manyAddClick()"/>
        						<label for="isMany2">否</label>
                	</td>
                	<th style="text-align:right;" id="show0">输入个数：</th>
                	<td id="show1" style="padding-left:5px;">
                		<input type="text" name="hostMany" value="1" id="hostMany"/> <font color="red">*最多10个</font> 
                	</td>
                </tr>
                <tr>
                	<th align="right">机 柜：</th>
                	<td width="163px" style="padding-left:5px;">
                		<input type="hidden" name="cupid" value="<?php echo ($hostinfo["cupid"]); ?>" id="cupid"/>
						<input type="text" name="cupname" value="<?php echo ($hostinfo["cupname"]); ?>" readonly="readonly" id="cupname" class="textBack"/>
						<SPAN><font color="blue">*</font></SPAN>
					</td>
					<th align="right">机 位：</th>
                	<td width="163px" style="padding-left:5px;">
						<input type="text" name="seatid" value="<?php echo ($hostinfo["seatid"]); ?>" readonly="readonly" id="seatid" class="textBack"/>
						<SPAN><font color="blue">*</font></SPAN>
					</td>
                <tr>
                <tr>
                	<th align="right">电信IP：</th>
                	<td width="163px" style="padding-left:5px;">
						<input autoComplete="off" type="text" name="mainip" value="" id="mainip" class="textWhite"/>
						<div class="style2" id="ajaxShowIp"></div>

						<SPAN><font color="blue">* 自动补全</font></SPAN>
					</td>
					<th align="right">网通IP：</th>
                	<td width="163px" style="padding-left:5px;">
						<input autoComplete="off" type="text" name="subip" value="" id="subip" class="textWhite" />
						<SPAN><font color="blue">* 自动补全</font></SPAN>
					</td>
                <tr>
                <tr>
                	<th align="right">内网IP：</th>
                	<td width="163px" style="padding-left:5px;">
						<input autoComplete="off" type="text" name="innip" value="" id="innip" class="textWhite" />
					</td>
					<th align="right">系统：</th>
                	<td width="163px" style="padding-left:5px;">
						<input autoComplete="off" type="text" name="system" value="debian" id="system" class="textWhite" />
					</td>
					
                <tr>
                <tr height="30">
                	<td align="right" colspan="2"><input type="submit" value="增 加" name='sub' style="height:23px;width:80px;"/></td>
                	<td width="163px" style="padding-left:5px;" colspan="2">
						<input type="reset" name="reset" value="重 置" style="height:23px;width:80px;" />
					</td>
					
                <tr>

</table>
</form>
</div>

</body>
</html>