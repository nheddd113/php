<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title><?php echo ($title); ?></title>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/style.css" />
<script src="__PUBLIC__/js/artDialog/jquery.artDialog.js?skin=blue"></script>
<script type="text/javascript" src="__PUBLIC__/js/search.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/cupBoard.css" />
<script type="text/javascript" src="__PUBLIC__/js/cupBoard.js"></script>
<script type="text/javascript">
var houid = '<?php echo ($_REQUEST['houid']); ?>';
var gameid = '<?php echo ($_REQUEST['gameid']); ?>';
var owner = '<?php echo ($_REQUEST['owner']); ?>';
var sortHost = '<?php echo ($_REQUEST['ishost']); ?>';
houid = houid?houid:0;
gameid = gameid?gameid:0;
sortHost = sortHost?sortHost:-1;
owner = owner?owner:0;
var getVirHostUrl = "<?php echo U('Home/Ajax/getVirHost');?>";
</script>
</head>
<body>
<div class="content">
<table id="show" border="0">
    <tr style="text-align:right">
      <td style="text-align:left">服务器列表:
        <button onclick="openShutManager(this,'box',false,'显示列表','展开列表')">展开列表</button></td>
      <td >游戏类型:
        <select name="game" size="1" id="game">
          <option value="0" style=" height:20px;" selected>=显示所有=</option>
          <?php if(is_array($gameCode)): foreach($gameCode as $key=>$game): ?><option value="<?php echo ($game["id"]); ?>" style=" height:20px;"><?php echo ($game["name"]); ?></option><?php endforeach; endif; ?>
        </select></td>
      <td >运营商:
        <select name="ops" id="owner">
          <option value="0" style="height:20px;" selected>=请选择=</option>
          <?php if(is_array($OpsArray)): foreach($OpsArray as $key=>$opsvalue): ?><option value="<?php echo ($opsvalue["seq"]); ?>" style=" height:20px;"><?php echo ($opsvalue["name"]); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
      <td >服务器类型:
        <select name="sortHost" id="sortHost" size="1" style="height:20px;">
          <option value="-1" style=" height:20px;" selected>请选择</option>
          <option value="0" style=" height:20px;">服务器</option>
          <option value="1" style=" height:20px;">宿主机</option>
          <option value="2" style=" height:20px;">虚拟机</option>
          <option value="3" style=" height:20px;">储存器</option>
        </select>
      </td>
      <td>机房分类 :
        <select name="jf" size="1" id='houseshowhost'>
          <option value="0" style=" height:20px;" selected>=显示所有=</option>
          <?php if(is_array($house)): foreach($house as $key=>$housevalue): ?><option value="<?php echo ($housevalue["id"]); ?>" style="height:20px;"><?php echo ($housevalue["houname"]); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr>
  </table>
  
  <table border="0" id="conttable">
    <tr id="firstline">
      <th style="width:3%;">序号</th>
      <th style="width:8%;">资产编码</th>
      <th style="width:8%;">服务器IP</th>
      <th style="width:8%;">运营商</th>
      <th style="width:8%;">所属机房</th>
      <th style="width:8%;">所属用途</th>
      <th style="width:6%;">服务器状态</th>
      <th style="width:5%;">运营状态</th>
      <th style="width:5%;">服务器类型</th>
      <th style="width:5%;">操作系统</th>
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
      <td style="width:8%;"><a href="<?php echo U('Home/Search/search',array('gameid'=>$value['gameid']));?>"><?php echo ($value["gamename"]); ?></a></td>
      <td style="width:6%;"><?php echo ($value["status"]); ?></td>
      <td style="width:5%;"><?php echo ($value["hostype"]); ?></td>
      <td style="width:5%;"><?php echo ($value["ishost"]); ?></td>
      <td style="width:5%;"><?php echo ($value["system"]); ?></td>
      <td style="width:8%;"><?php echo ($value["remark"]); ?></td>
      <td style="width:8%;">
      	<?php if(($value["ishost"]) == "宿主机"): ?>[<a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$value['id']));?>">详情</a>]
      		[<a href='' onclick="return showVireHost({id:<?php echo ($value['id']); ?>,url:'<?php echo U(APP_NAME . '/Hostinfo/hostInfo','','');?>'});">虚拟机</a>]
      	<?php else: ?>
      		[<a href="<?php echo U('Home/Hostinfo/hostInfo',array('id'=>$value['id']));?>">详情</a>]<?php endif; ?>
      	[<a href='' onclick="return deleteHost({id:<?php echo ($value['id']); ?>,url:'<?php echo U(APP_NAME . '/Hostinfo/deleteHost','','');?>'});">删除</a>]	
        <?php if(($_SESSION['level']) == "100"): endif; ?>
      </td>
    </tr><?php endforeach; endif; ?>
    <tr style="background-color:fff">
      <td colspan="12" align="right" id="td" style="padding-right:15px;"><?php echo ($page); ?>
      </td>
    </tr>
  </table>
</div>

</body>
</html>