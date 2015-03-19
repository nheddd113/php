$(document).ready(function(){
	$("#modify").click(function(){
		location.href = $(this).attr('url');
	});
	
});
function check_item(){
	if(addmonitor.name.value == ''){
		art.dialog({
			content:"监控名称不能为空!",
			lock:true
		}).time(2);
		addmonitor.name.focus();
		return false;
	}
	if(addmonitor.nagios.value == ''){
		art.dialog({
			content:"nagios地址不能为空!",
			lock:true
		}).time(2);
		addmonitor.nagios.focus();
		return false;
	}
	if(addmonitor.cacti.value == ''){
		art.dialog({
			content:"cacti地址不能为空!",
			lock:true
		}).time(2);
		addmonitor.nagios.focus();
		return false;
	}
}