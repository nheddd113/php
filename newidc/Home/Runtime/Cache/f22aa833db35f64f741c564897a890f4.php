<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/style.css" />
<script src="__PUBLIC__/js/artDialog/jquery.artDialog.js?skin=blue"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/addCupboard.css" />
<script type="text/javascript" src="__PUBLIC__/js/amendCup.js"></script>
<script>
	var addSeatUrl = '<?php echo U(APP_NAME . '/Ajax/addOrdelSeat');;?>';
</script>
</head>
<div class="content">
	<form action="__URL__/amendCupHandle" method="post" name='amendCup' onsubmit="return checkAmendCup();">
		<table class="query_form_table" border="1" cellspacing="3" cellpadding="3" width="599" align="left" style="font-size:12px;">
			<tr  style="color:#4d6b72;font-weight:bold">
				<td height="24" colspan="4" style="BACKGROUND-COLOR: #cae8ea;" align="left">修改机柜</td>
			</tr>
			<tr  style="background-color:#f7f8f9">
				<th width="100" height="25" align="right">编&nbsp;&nbsp;&nbsp;&nbsp;号：</th>
				<td width="160" align="left" style="padding-left:5px">
					<input type="text" name="id" maxlength="32" value="<?php echo ($cupInfo["id"]); ?>" readonly="readonly" id="id" style="background-color:#D3D3D3"/>
				</td>
				<th height="25" align="right">创建时间：</th>
				<td align="left" style="padding-left:5px">
					<input type="text" name="timeDate" value="<?php echo ($cupInfo["createtime"]); ?>" readonly="readonly" id="timeDate"/>
					&nbsp;&nbsp;<font color="blue">示例: 2014-01-12 14:10:12   </font>
				</td>
			</tr>
			<tr  style="background-color:#f7f8f9">
				<th width="100" height="25" align="right">机&nbsp;&nbsp;&nbsp;&nbsp;柜：</th>
				<td width="160" align="left" style="padding-left:5px">
					<input type="text" name="cupname" maxlength="32" value="<?php echo ($cupInfo["cupname"]); ?>" id="cupname" />
				</td>
				<th height="25" align="right">机&nbsp;&nbsp;&nbsp;&nbsp;位：</th>
				<td align="left" style="padding-left:5px">
					<input type="text" name="seatnum" maxlength="3" value="<?php echo ($cupInfo["seatnum"]); ?>" readonly="readonly" id="seatnum" style="width:40px"/>&nbsp;
					<input  value="加"  type="button"  onclick="addPlaceSubmit(1)"/> 
					<input type="button" value="减"  onclick="addPlaceSubmit(0)" />
				</td>
			</tr>
			<tr  style="background-color:#f7f8f9">
				<th width="100" height="25" align="right">单&nbsp;&nbsp;&nbsp;&nbsp;价：</th>
				<td width="160" align="left" style="padding-left:5px">
					<input type="text" name="price" maxlength="32" value="<?php echo ($cupInfo["price"]); ?>" id="price"/>
				</td>
				<th height="25" align="right">所属机房：</th>
				<input type="hidden" value='<?php echo ($cupInfo["houid"]); ?>' />
				<td align="left" style="padding-left:5px">
					<select name="houid" id="houid" disabled="true">
						<?php if(is_array($houseList)): foreach($houseList as $key=>$name): ?><option value="<?php echo ($key); ?>"><?php echo ($name); ?></option><?php endforeach; endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th align="right">备&nbsp;&nbsp;&nbsp;&nbsp;注：</th>
				<td align="left" style="padding-left:5px" colspan="3">
					<input type="text" name="remark" maxlength="200" value="" id="remark"/>
				</td>
			</tr>            
			<tr class="urlHref" style="background-color:#f7f8f9">
				<td height="25" colspan="4" align="center">
					<input type="submit" value="保存" style="width:60px;" />
				</td>
			</tr>
		</table>
	</form>
</div>