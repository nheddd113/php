<?php

class PublicAction extends Action{

    public function login() {

    	$this->display();
    }
    public function checklogin() {
		$user = $_POST['username'];
		$password = md5($_POST['password']);
		if (md5($_POST['code']) !== $_SESSION['verify']) {
			$this->error('验证码错误!');
		} else {
			$admin = D('admin')->where("username='" . $user . "' and password='" . $password . "'")->find();
			if ($admin) {
				session("idc_admin", $admin);
				$this->success('登陆成功!',U(APP_NAME . '/Index/index'));
			}else
				$this->error("登陆失败,请检查用户名或密码是否正确!");
		}
	}
	public function logout() {
		session("idc_admin", null);
		$this->error("没有登陆",U(APP_NAME . "/Public/login"));
	}
	public function code() {
		import("ORG.Util.Image");
		Image :: buildImageVerify();
	}
}
?>