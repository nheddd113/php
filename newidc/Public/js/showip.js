$(document).ready(function(){
	ipState = $("input:hidden[name='ipStatus']").val();
	ipHouid = $("input:hidden[name='ipHouse']").val();
	if(ipState!=''){
		$("#status option[value="+ipState+"]").attr('selected',true);
	}
	if(ipHouid!=''){
		$("#houid option[value="+ipHouid+"]").attr('selected',true);
	}
	$("#showip tr").hover(function() { // 隔行换色
		$(this).attr({"class" : "tr_3"});
	}, function() {
		if ($(this).attr("id") == 1) {
			$(this).attr({"class" : "tr_1"});
		} else {
			$(this).attr({"class" : "tr_2"});
		}
	});
	$("#search").click(function(){
		var state = $("#status").val();
		var houid = $("#houid").val();
		var mainip = $("#keyworld").val() + '%';
		data = {};
		if(houid != 0){
			data.houid = houid;
		}
		if(state != 2){
			data.state = state;
		}
		data['mainip'] = mainip;
		$.ajax({
			type:"post",
			data:data,
			url:getUrl,
			success:function(returndata){
				location.href = returndata.info;
				
			}
		});
		
	});
	$("#status").change(function(){
		var state = $(this).val();
		var houid = $("#houid").val();
		data = {};
		if(houid != 0){
			data.houid = houid;
		}
		if(state != 2){
			data.state = state;
		}
		$.ajax({
			type:"post",
			data:data,
			url:getUrl,
			success:function(returndata){
				location.href = returndata.info;
			}
		});
	});
	$("#houid").change(function(){
		var houid = $(this).val();
		var state = $("#status").val();
		data = {};
		if(houid != 0){
			data.houid = houid;
		}
		if(state != 2){
			data.state = state;
		}
		$.ajax({
			type:"post",
			data:data,
			url:getUrl,
			success:function(returndata){
				location.href = returndata.info;
			}
		});
	});

});
function deleteip(ip){
	return confirm("确认要删除IP:"+ip+"吗?");
}

//删除机房确认

function deletehouse(){
	return confirm("确定要删除该机房吗?");
}

function changeState(posturl,id,state){
	if(state == 1){
		var html="<select id='state'><option value='0'>未使用</option><option value='1' selected>已使用</option></select>";
	}else{
		var html="<select id='state'><option value='0' selected>未使用</option><option value='1'>已使用</option></select>";
	}
	art.dialog({
		title:"修改IP状态",
		lock:true,
		content:html,
		button:[{
			name:"修改",
			callback:function(){
				state = $("#state").val();
				postdata = {id:id,state:state};
				$.ajax({
					type:"post",
					url:posturl,
					data:postdata,
					success:function(returndata){
						art.dialog({
							content:returndata.info,
							title:'修改通知',
							ok:function(){
								location.href = location.href ;
							}
							});
					}
				});
			}
		},{
			name:"取消"
		}]

	});
	return false;
}