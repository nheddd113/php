$(document).ready(function() {
	$("#add :text ").attr('class','myinput');
});
function check_submit(){
	if(addCupboard.cupname.value==''){
		alert('机柜名称不能为空');
		addCupboard.cupname.focus();
		return false;
	}
	if(addCupboard.houid.value==0){
		alert('请选择机房!');
		return false;
	}
	if(addCupboard.seatnum.value==''){
		alert('地址称不能为空');
		addCupboard.seatnum.focus();
		return false;
	}
}