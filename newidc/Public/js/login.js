$(document).ready(function(){
	
	
});



function CheckData() {
	var name = document.LoginForm.userName.value;
	var pwd = document.LoginForm.userPass.value;

	if (name == '') {
		alert('用户名不能为空!');
		document.LoginForm.userName.focus();
		return false;
	}
	if (pwd == '') {
		alert('密码不能为空!')
		document.LoginForm.userPass.focus();
		return false;
	}
//	$.ajax({
//		type:"POST",
//		url:verifyUrl,
//		data:{name:name,passwd:pwd},
//		success:function(data){
//			if(data == 1){
//				alert('帐号不存!');
//				concelCli();
//				document.LoginForm.userName.focus();
//			}else if(data == 2){
//				alert('帐号或密码不正确!');
//				concelCli();
//				document.LoginForm.userName.focus();
//			}else{
//				location.href = data;
//			}
//		},
//		error:function(){
//			alert('登陆失败!');
//		}
//	});
//	return false;
}
//取消方法
function concelCli() {
	document.getElementById("userName").value = "";
	document.getElementById("userPass").value = "";
}