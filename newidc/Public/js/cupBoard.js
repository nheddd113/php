$(document).ready(function (){
	
	
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

function showVireHost(obja){
	$.ajax({
		type:"POST",
		url:getVirHostUrl,
		data:{id:obja.id},
		success:function(item){
			obj = item.data;
			var html ="<div id='virHostBox'><table width='800'><tr id='firstline' style='height:23px;'> " +
					"<th style='width:5%;'>序号</th>" +
					"<th style='width:20%;'>标识码</th>" +
					"<th style='width:20%;'>电信IP</th>" +
					"<th style='width:15%;'>所在机房</th>" +
					"<th style='width:15%;'>所属业务</th>" +
					"<th style='width:10%;'>状态</th>" +
					"<th style='width:15%;'>备注</th>" +
					"<th style='width:10%;'>操作</th></tr>";
			for(index in obj){
				var id = Number(index) + 1;
				var yu = index % 2 ==0?1:2;
				html += "<tr class='tr_"+yu+"'><td>"+id
						+"</td><td><a href='" + obja.url+ "/id/"+obj[index].id 
						+".html'>"+obj[index].hostid + "</a>"
						+"</td><td><a href='" + obja.url+ "/id/"+obj[index].id 
						+".html'>"+obj[index].mainip + "</a>"
						+"</td><td>"+obj[index].housename
						+"</td><td>"+obj[index].gamename
						+"</td><td>"+obj[index].status
						+"</td><td>"+obj[index].remark
						+"</td><td><a href='" +obja.url+ "/id/"+obj[index].id 
								+"'>详细</a>" +
								"</td></tr>";
			}
			html += "</table></div>";
			art.dialog({
				title:"虚拟机列表",
				esc:true,
				content:html
			});
		}
	});
	return false;
}