<?php if (!defined('THINK_PATH')) exit();?><div  style="text-align:center">
	<div><h1>CDN自助推送</h1></div>
	<form action="__URL__/sendContent" method="post" name="cdn">
		<input type="radio" name="ops" value="lx" id="lx" checked><label for="lx">S2</label>
		<input type="radio" name="ops" value="ws" id="ws"><label for="ws">S1</label><br>
		<textarea cols="80" rows="10" name="content"></textarea><br>
		<input type="button" value="推送" onclick="sub();">
	</form>
</div>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/subjquery.js"></script>
<script>
function sub(){
	var url = $("form").attr('action');
	var ops = $(":radio:checked").val();
	var content = $("textarea").val();
	var data = {ops:ops,content:content}
	$.ajax({
		type:"post",
		url:url,
		data:data,
		success:function(data){
			alert(data.info);
		}
	});
}
</script>