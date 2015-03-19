<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title><?php echo ($title); ?></title>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<script src="__PUBLIC__/js/artDialog/jquery.artDialog.js?skin=blue"></script>
<script type="text/javascript" src="__PUBLIC__/js/cupBoard.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/cupBoard.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/style.css" />
<script>
var getVirHostUrl = "<?php echo U('Home/Ajax/getVirHost');?>";
</script>
</head>
<body>
<div class="content">
  <table border="0" id="conttable">
  	<tr>
  		<td style="background-color:#6EC2FD;height:30px;text-align:left;font-weight: bold;" colspan="11">
  			当前机柜：<?php echo ($cup["cupname"]); ?>，共<?php echo ($cup["seatnum"]); ?>机位，创建时间：<?php echo ($cup["createtime"]); ?>，备注：<?php echo ($cup["remark"]); ?>；<a href="<?php echo U(APP_NAME . '/Public/amendCup',array('id'=>$cup['id']));?>">修改</a>
  		</td>
  	</tr>
    <tr id="firstline">
      <th style="width:3%;">序号</th>
      <th style="width:8%;">资产编码</th>
      <th style="width:8%;">服务器IP</th>
      <th style="width:8%;">运营商</th>
      <th style="width:8%;">所属机房</th>
      <th style="width:8%;">所属用途</th>
      <th style="width:8%;">服务器状态</th>
      <th style="width:8%;">运营状态</th>
      <th style="width:8%;">服务器类型</th>
      <th style="width:8%;">备注</th>
      <th style="width:10%;">操作</th>
    </tr>
    <?php if(is_array($hostinfo)): foreach($hostinfo as $key=>$value): ?>
    <div style="display:none"><?php echo ($classid = $key % 2==0?1:2); ?></div>
    <tr id="<?php echo ($classid); ?>" class="tr_<?php echo ($classid); ?>" name="tr_<?php echo ($classid); ?>"> 
      <td style="width:3%;"><?php echo ($key+1); ?></td>
      <td style="width:8%;"><a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$value['id']));?>"><?php echo ($value["hostid"]); ?></a></td>
      <td style="width:8%;"><a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$value['id']));?>"><?php echo ($value["mainip"]); ?></a></td>
      <td style="width:8%;"><a href="<?php echo U('Home/Search/search',array('owner'=>$value['owner']));?>"><?php echo ($value[ownername]); ?></a></td>
      <td style="width:8%;"><a href="<?php echo U('Home/Search/search',array('houid'=>$value['houid']));?>"><?php echo ($value["housename"]); ?></a></td>
      <td style="width:8%;"><?php echo ($value["gamename"]); ?></td>
      <td style="width:8%;"><?php echo ($value["status"]); ?></td>
      <td style="width:8%;"><?php echo ($value["hostype"]); ?></td>
      <td style="width:5%;" id="ishost" value="<?php echo ($key); ?>"><?php echo ($value["ishost"]); ?></td>
      <td style="width:8%;"><?php echo ($value["remark"]); ?></td>
      <td style="width:8%;" id="info<?php echo ($key); ?>">
      	<?php if(($value["ishost"]) == "宿主机"): ?>[<a href="<?php echo U(APP_NAME . '/Hostinfo/hostInfo',array('id'=>$value['id']));?>">详情</a>]
      		[<a href='' onclick="return showVireHost({id:<?php echo ($value['id']); ?>,url:'<?php echo U(APP_NAME . '/Hostinfo/hostInfo','','');?>'});">虚拟机</a>]
      	<?php else: ?>
      		[<a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$value['id']));?>">详情</a>]<?php endif; ?>	
      	[<a href='' onclick="return deleteHost({id:<?php echo ($value['id']); ?>,url:'<?php echo U(APP_NAME . '/Hostinfo/deleteHost','','');?>'});">删除</a>]
      </td>
    </tr><?php endforeach; endif; ?>
    <tr style="background-color:fff">
      <td colspan="11" align="right" id="td" style="padding-right:15px;"><?php echo ($page); ?></td>
    </tr>
  </table>
</div>

</body>
</html>