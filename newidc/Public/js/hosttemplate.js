$(document).ready(function(){
	$("#add :text ").attr('class','myinput');
	$("form").submit(function(){
		if(addtemplate.name.value == ''){
			addtemplate.name.focus();
			art.dialog({
				content:'模板名称不能为空!'
			});
			return false;
		}
		if(addtemplate.cpu.value == ''){
			addtemplate.cpu.focus();
			art.dialog({
				content:'模板CPU不能为空!'
			});
			return false;
		}
		if(addtemplate.disk.value == '' || addtemplate.disk.value==0){
			addtemplate.disk.focus();
			art.dialog({
				content:'模板硬盘不能为空或值为0!'
			});
			return false;
		}
		if(addtemplate.mem.value == '' || addtemplate.mem.value==0){
			addtemplate.mem.focus();
			art.dialog({
				content:'模板内存不能为空或值为0!'
			});
			return false;
		}
		if(addtemplate.price.value == '' || addtemplate.price.value == 0 ){
			addtemplate.price.focus();
			art.dialog({
				content:'主机价格不能为空或值为0!'
			});
			return false;
		}
	});
});