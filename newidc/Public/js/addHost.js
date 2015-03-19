$(document).ready(function() {
	$("#add :text").attr('class','myinput');
});
function writeinfo(name){
	data = {};
	data.name = name;
	$.ajax({
		type:"post",
		data:data,
		url:getTempIdUrl,
		success:function(data){
			if(data.status == 1){
				addHost.cpu.value = data.data.cpu;
				addHost.disk.value = data.data.disk;
				addHost.mem.value = data.data.mem;
				addHost.templateid.value = data.data.id;
			}
		}
	});
}
function check_submit(){
	if(addHost.hostid.value==''){
		alert('资产编码不能为空');
		addHost.hostid.focus();
		return false;
	}
	if(addHost.houid.value==0){
		alert('请选择机房!');
		addHost.houid.focus();
		return false;
	}
	if(addHost.sertag.value==''){
		alert('服务编码不能为空!');
		addHost.sertag.focus();
		return false;
	}
	if(addHost.cpu.value==''){
		alert('CPU不能为空!');
		addHost.cpu.focus();
		return false;
	}
	if(addHost.disk.value==''){
		alert('硬盘不能为空!');
		addHost.disk.focus();
		return false;
	}
	if(addHost.mem.value==''){
		alert('内存不能这空!');
		addHost.mem.focus();
		return false;
	}
}