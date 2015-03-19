<?php
/*
 * Created on 2013-9-22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class LoginAction extends Action{
	public function index(){
		if(session('username')){
			$this->redirect('Index/index');
		}
		$this->display();
	}
	public function verify(){
		if(!IS_POST) $this->error("网页不存在!");
		$username = I('userName');
		$password = md5(I('userPass'));
		if(M('user')->where(array('loginname'=>$username))->count() == 0){
			$this->error('登陆帐号不存在!');
			return;
		}
		$result = M('user')->where(array('loginname'=>$username))->field('id,password,realname,level')->find();
		if ($password != $result['password']){
			$this->error('登陆帐号与密码不匹配!');
			return;
		}
		session('username',$username);
		session('realname',$result['realname']);
		session('level',$result['level']);
		$data = array(
			'id' => $result['id'],
			'loginname'=>$username,
			'password' => $password,
			'lastloginip'=>get_client_ip(),
			'lastlogintime' => time()
		);
//		p(C('SESSION_EXPIRE'));die;
		if(M('User')->save($data)){
			$this->redirect(APP_NAME.'/Index/index');
		}else{
			$this->redirect(APP_NAME.'/Login/index');
		}

	}
	public function logout(){
		session(null);
		session('[destroy]');
		$this->display('Login/index');
	}
}
?>