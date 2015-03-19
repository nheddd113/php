$(document).ready(function() {
	$(".query_form_table :text ").attr('class','myinput');
	var houid = $("input:hidden").val();
	$('#houid option[value='+houid+']').attr('selected',true)
});
function addPlaceSubmit(type){
	var seatnum = $("#seatnum").val();
	var id = $("#id").val();
	data = {id:id,type:type}
	$.ajax({
		type:"post",
		url:addSeatUrl,
		data:data,
		success:function(data){
			if(data.status == 1){
				location.href = location.href;
			}else{
				art.dialog({
					content:data.info,
					lock:true
				});
			}
		}
	});
}
function checkAmendCup(){
	if(amendCup.cupname.value == ''){
		art.dialog('机柜名称不能为空!');
		amendCup.cupname.focus()
		return false;
	}
	if(amendCup.price.value == ''){
		art.dialog('该机柜单价不能为空!');
		amendCup.cupname.focus()
		return false;
	}
}
function submitValidate(){
	if(amendHouse.houname.value == ''){
		art.dialog('机房名称不能为空!');
		amendHouse.houname.focus()
		return false;
	}
	if(amendHouse.company.value == ''){
		art.dialog('公司名称不能为空!');
		amendHouse.company.focus()
		return false;
	}
	if(amendHouse.address.value == ''){
		art.dialog('公司地址不能为空!');
		amendHouse.address.focus()
		return false;
	}
	if(amendHouse.address.value == ''){
		art.dialog('公司地址不能为空!');
		amendHouse.address.focus()
		return false;
	}
}