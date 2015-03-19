$(document).ready(function() {
	$("#add :text").attr('class','myinput');
	$("#add :password").attr('class','myinput');
});
function check_submit(){
	if(addHouse.houname.value==''){
		alert('机房名称不能为空');
		addHouse.houname.focus();
		return false;
	}
	if(addHouse.company.value==''){
		alert('公司名称不能为空');
		addHouse.company.focus();
		return false;
	}
	if(addHouse.address.value==''){
		alert('地址称不能为空');
		addHouse.address.focus();
		return false;
	}
	if(addHouse.contact.value==''){
		alert('联系人名称不能为空');
		addHouse.contact.focus();
		return false;
	}
	if(addHouse.telphone.value==''){
		alert('联系电话名称不能为空');
		addHouse.telphone.focus();
		return false;
	}
	if(addHouse.place.value==0){
		alert('请选择机房所在区域');
		addHouse.place.focus();
		return false;
	}
}
function check_game(){
	if(addGame.name.value==''){
		alert('游戏名称不能为空!');
		addGame.name.focus();
		return false;
	}
	if(addGame.alias.value==''){
		alert('游戏别名不能为空!');
		addGame.alias.focus();
		return false;
	}
}
