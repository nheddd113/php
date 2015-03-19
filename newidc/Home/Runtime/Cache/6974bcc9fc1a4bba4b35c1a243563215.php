<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title><?php echo ($title); ?></title>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/style/style.css" />
</head>
<body>

<div class="content">
	<table border="0" id="conttable">
		<tr id="firstline">
		  <th style="width:3%;">序号</th>
		  <th style="width:8%;">机柜名称</th>
		  <th style="width:8%;">所属机房</th>
		  <th style="width:8%;">机位总数</th>
		  <th style="width:8%;">创建时间</th>
		  <th style="width:18%;">备注</th>
		  <th style="width:5%;">操作</th>
		</tr>
			<?php if(is_array($cupboard)): foreach($cupboard as $key=>$cupboardvalue): ?>
				<div style="display:none"><?php echo ($classid = $key % 2==0?1:2); ?></div>
				<tr id="<?php echo ($classid); ?>" class="tr_<?php echo ($classid); ?>" name="tr_<?php echo ($classid); ?>">
				  <td style="width:3%;"><?php echo ($key+1); ?></td>
				  <td style="width:8%;"><a href="<?php echo U('Home/Search/cupBoard',array('cupid'=>$cupboardvalue[id]));?>"><?php echo ($cupboardvalue["cupname"]); ?></a></td>
				  <td style="width:8%;"><?php echo ($cupboardvalue["houname"]); ?></td>
				  <td style="width:8%;"><?php echo ($cupboardvalue["seatnum"]); ?></td>
				  <td style="width:8%;"><?php echo ($cupboardvalue["createtime"]); ?></td>
				  <td style="width:18%;"><?php echo ($cupboardvalue["remark"]); ?></td>
				  <td style="width:5%;">
				  	  <?php if(($_SESSION['level']) > "99"): ?><a href="<?php echo U(APP_NAME . '/Public/amendCup',array('id'=>$cupboardvalue['id']));?>">[详情]</a>
					      	<a href="__URL__/deletecup/id/<?php echo ($cupboardvalue[id]); ?>" onclick="return deletecup()">[删除]</a>
				      <?php else: ?>
				      		<a href="<?php echo U(APP_NAME . '/Public/amendCup',array('id'=>$cupboardvalue['id']));?>">[详情]</a><?php endif; ?>
				  </td>
				</tr><?php endforeach; endif; ?>
		<tr style="background-color:fff">
		  <td colspan="7" align="right" id="td" style="padding-right:15px;"><?php echo ($page); ?></td>
		</tr>
	</table>
</div>
</body>
</html>