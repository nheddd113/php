$(document).ready(function(){
	$("#checkAll").click(function(){
		$(".ids").attr('checked',this.checked);

	});
	$("#game").change(function(){
		var url = location.href;
		url = url.replace('.html','');
		if(url.search(/gameid/)<0){
			url += '/gameid/' + $(this).val();
		}else{
			url = url.replace(/\/gameid\/[0-9]{1}/,'/gameid/'+$(this).val());
		}
		location.href = url;
	});
	$("#houseshowhost").change(function(){
		var url = location.href;
		url = url.replace('.html','');
		if($(this).val() == 0){
			location.href = url.replace(/\/houid\/[0-9]{0,9}/,'');
			return;
		}
		if(url.search(/houid/)<0){
			url += '/houid/' + $(this).val();
		}else{
			url = url.replace(/\/houid\/[0-9]{0,9}/,'/houid/'+$(this).val());
		}
		location.href = url;
	});
	$("#sortHost").change(function(){
		var url = location.href;
		url = url.replace('.html','');
		if($(this).val() == -1){
			location.href = url.replace(/\/ishost\/[0-9]{0,9}/,'');
			return;
		}
		if(url.search(/ishost/)<0){
			url += '/ishost/' + $(this).val();
		}else{
			url = url.replace(/\/ishost\/[0-9]{0,9}/,'/ishost/'+$(this).val());
		}
		location.href = url;
	});
	$("#owner").change(function(){
		var url = location.href;
		url = url.replace('.html','');
		if($(this).val() == 0){
			location.href = url.replace(/\/owner\/[0-9]{0,9}/,'');
			return;
		}
		if(url.search(/owner/)<0){
			url += '/owner/' + $(this).val();
		}else{
			url = url.replace(/\/owner\/[0-9]{0,9}/,'/owner/'+$(this).val());
		}
		location.href = url;
	});
	$("#game").val(gameid);
	$("#owner").val(owner);
	$("#houseshowhost").val(houid);
	$("#sortHost").val(sortHost);
	$.extend({
		"parseUrl":function(url){
			url = url.replace('.html','');
		}
	});

	
});
function deleteHost(value){ //删除主机
	art.dialog({
		content:"确认要删除该服务器吗?",
		lock:true,
		ok:function(){
			$.ajax({
				type:"post",
				url:value.url,
				data:{id:value.id},
				success:function(data){
					art.dialog({
						title:data.data,
						content:data.info,
						ok:function(){
							location.href = location.href;
						}
					});
				}
			});
		},
		cancel:function(){}
	})
	return false;
}
