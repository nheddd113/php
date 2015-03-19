$(document).ready(function(){
	$("#add :text ").attr('class','myinput');
	var range  = $(":radio[name='where']:checked").val();
	if(range == 0){
		$("#range").attr({'readonly':true,class:'textBack'});
	}
	var duline = $(":radio[name='duline']:checked").val();
	if(duline == 0){
		$("#subip").attr({'readonly':true,class:'textBack'});
		$("#subgw").attr({'readonly':true,class:'textBack'});
		$("#submask").attr({'readonly':true,class:'textBack'});
	}
	$(":radio[name='duline']").change(function(){
		var duline = $(":radio[name='duline']:checked").val();
		if(duline == 0){
			$("#subip").attr({'readonly':true,class:'textBack'});
			$("#subgw").attr({'readonly':true,class:'textBack'});
			$("#submask").attr({'readonly':true,class:'textBack'});
		}else{
			$("#subip").attr({'readonly':false,class:'myinput'});
			$("#subgw").attr({'readonly':false,class:'myinput'});
			$("#submask").attr({'readonly':false,class:'myinput'});
		}
	});
	$(":radio[name='where']").change(function(){
		var range  = $(":radio[name='where']:checked").val();
		if(range == 0){
			$("#range").attr({'readonly':true,class:'textBack'});
		}else{
			$("#range").attr({'readonly':false,class:'myinput'});
		}
	});
});
function checkAdd(){
	if(this.mainip.value==''){
		art.dialog({lock:true,content:'电信IP不能为空!'});
		this.mainip.focus();
		return false;
	}
	if(!checkIpFormat(this.mainip.value,'电信IP')){
		return false;
	}
	if(this.mainmask.value==''){
		art.dialog({lock:true,content:'电信掩码不能为空!'});
		this.mainmask.focus();
		return false;
	}
	if(!checkIpFormat(this.mainmask.value,'电信掩码')){
		return false;
	}
	if(this.maingw.value==''){
		art.dialog({lock:true,content:'电信网关不能为空!'});
		this.maingw.focus();
		return false;
	}
	if(!checkIpFormat(this.maingw.value,'电信网关')){
		return false;
	}
	if(this.houid.value == 0){
		art.dialog({lock:true,content:'请选择机房!'});
		return false;
	}
	var duline = $(":radio[name='duline']:checked").val();
	if(duline == 1){
		if(this.subip.value==''){
			art.dialog({lock:true,content:'网通IP不能为空!'});
			this.subip.focus();
			return false;
		}
		if(!checkIpFormat(this.subip.value,'网通IP')){
			return false;
		}
		if(this.submask.value==''){
			art.dialog({lock:true,content:'网通网关不能为空!'});
			this.submask.focus();
			return false;	
		}
		if(!checkIpFormat(this.submask.value,'网通网关')){
			return false;
		}
	}
	var range = $(":radio[name='where']:checked").val();
	if(range == 1){
		if(this.range.value == ''){
			art.dialog({lock:true,content:'结束IP不能为空!'});
			return false;
		}
	}
	
}

function checkIpFormat(ip,desc){
	if(ip.indexOf('.') == -1){
		art.dialog({lock:true,title:'通知',content:desc+'格式不正确,请确认!'});
		return false;
	}
	var ipArr = ip.split('.');
	if(ipArr.length != 4){
		art.dialog({lock:true,title:'通知',content:desc+'格式不正确,请确认!'});
		return false;
	}
	for(var i=0;i<ipArr.length;i++){
		if(isNaN(ipArr[i]) || ipArr[i]>255 || !ipArr[i]){
			art.dialog({lock:true,title:'通知',content:desc+'格式不正确,请确认!'});
			return false;
		}
	}
	return true;
}