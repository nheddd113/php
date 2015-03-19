$(document).ready(function(){
	$("tr").hover(function() { // 隔行换色
		$(this).attr({"class" : "tr_3"});
	}, function() {
		if ($(this).attr("name") == 'tr_1') {
			$(this).attr({"class" : "tr_1"});
		} else {
			$(this).attr({"class" : "tr_2"});
		}
	});
	
});

function deleteMonitor(url,id){
	art.dialog({
		content:"确定要删除该监控吗?",
		button:[
		{
			name:"删除",
			focus:true,
			callback:function(){
				var data = {id:id}
				$.ajax({
					type:"post",
					url:url,
					data:data,
					success:function(data){
						art.dialog({
							content:data.info,
							title:data.data,
							ok:function(){
								_url = location.href;
								location.href = _url;
							}
						});
					}
				});
			}
		},{
			name:"取消",
			callback:function(){
				return true;
			}
		}
		]
	});
	
	return false;
}