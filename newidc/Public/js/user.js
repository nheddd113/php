$(document).ready(function() {
	$("#add :text").attr('class','myinput');
	$("#add :password").attr('class','myinput');
});
function changepassword(){
	if(chgpassword.oldpassword.value==''){
		art.dialog("原密码不能为空!");
		chgpassword.password.focus();
		return false;
	}
	if(chgpassword.password.value==''){
		art.dialog("密码不能为空!");
		chgpassword.password.focus();
		return false;
	}
	if(chgpassword.password.length < 6){
		art.dialog("密码长度不能少于6位!");
		chgpassword.password.focus();
		return false;
	}
	if(chgpassword.password.value != chgpassword.password1.value){
		art.dialog("两次密码不相等!");
		chgpassword.password.focus();
		$("input[name='password']").val('')
		$("input[name='password1']").val('')
		return false;
	}
}
function adduser(){
	if(chgpassword.password.value==''){
		art.dialog("密码不能为空!");
		chgpassword.password.focus();
		return false;
	}
	if(chgpassword.realname.value==''){
		art.dialog("用户名称不能为空!");
		chgpassword.password.focus();
		return false;
	}
	if(chgpassword.password.length < 6){
		art.dialog("密码长度不能少于6位!");
		chgpassword.password.focus();
		return false;
	}
	if(chgpassword.password.value != chgpassword.password1.value){
		art.dialog("两次密码不相等!");
		chgpassword.password.focus();
		$("input[name='password']").val('')
		$("input[name='password1']").val('')
		return false;
	}
}
function chgUserInfo(url,realname,userid,level){
	var html = "名称:<input style='height:25px;' name='realname' value='"+realname+"'/><br>" +
			"密码:<input style='height:25px;' type='password' name='password' /><br>" +
			"等级:<input style='height:25px;' type='text' name='level' value='"+level+"' />";
	art.dialog({
		title:'修改用户信息',
		content:html,
		lock:true,
		ok:function(){
			var realname = $(":input[name='realname']").val();
			var password = $(":input[name='password']").val();
			var level = $(":input[name='level']").val();
			var data = {realname:realname,password:password,id:userid,level:level}
			$.ajax({
				type:"post",
				url:url,
				data:data,
				success:function(data){
					art.dialog({
						content:data.info,
						ok:function(){
							location.href = location.href;
						}
					});
				}
			});
		},
		cancel:function(){}
	});
	return false;
}
function deleteUser(url,realname,userid){
	art.dialog({
		content:"确认删除帐号<" + realname + ">吗?",
		cancel:function(){},
		ok:function(){
			$.ajax({
				type:"post",
				url:url,
				data:{id:userid},
				success:function(data){
					art.dialog({
						content:data.info,
						ok:function(){
							location.href = location.href
						}
					})
				}
			});
		}
	});
	return false;
}


