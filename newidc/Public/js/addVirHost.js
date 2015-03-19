$(document).ready(function(){
	var manyHost = $(":radio[name='isMany']:checked").val();
	var status = $("#status").val();
	if(manyHost == 0 || status == 1){
		virAddAction.mainip.className = "";
		virAddAction.subip.className = "";
		virAddAction.innip.className = "";
	}else{
		virAddAction.mainip.className = "textBack";
		virAddAction.subip.className = "textBack";
		virAddAction.innip.className = "textBack";
		$("#status").val(0);
		$("#status").attr("disabled",true);
	}
//	alert(manyHost);
});
function addVirHost(){
	if(this.mem.value == ''){
		art.dialog("服务器内存不能为空!");
		this.mem.focus();
		return false;
	}
	if(this.isMany.value == 1 && this.hostMany.value < 1){
		art.dialog("虚拟机数量不能为0");
		this.hostMany.focus();
		return false;
	}
}
function manyAddClick(){
	var manyValue =$('input:radio[name="isMany"]:checked').val();
	if (manyValue != 1){
		$("#show0").html('');
		$("#show1").html('');
		$("#mainip").attr({'class':''});
		virAddAction.subip.className = "";
		virAddAction.innip.className = "";
		$("#status").attr("disabled",false);
	}else{
		$("#show0").html('输入个数：');
		$("#show1").html('<input type="text" name="hostMany" value="1" id="hostMany"/> <font color="red">*最多10个</font> ');
		$("#mainip").attr({'class':'textBack'});
		virAddAction.subip.className = "textBack";
		virAddAction.innip.className = "textBack";
		$("#status").val(0);
		$("#status").attr("disabled",true);
	}
}
