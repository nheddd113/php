<?php

class UserAction extends CommonAction {
	/**
	 * 用户列表视图
	 */
	
	public function index(){
		$User = M('user');
		$userinfo = $User->select();
		$this->userInfo = $userinfo;
		$this->display();
	}
	
	/**
	 * 管理员修改帐号信息
	 * 
	 */
	public function changeUserInfo(){
		$url = U(APP_NAME . '/User/index');
		if(!IS_POST || !IS_AJAX) $this->error('该页面不存在',$url);
		$where = $_POST;
		if(!$where['password']) {
			unset($where['password']);
		}else{
			$where['password'] = md5($where['password']);
		}
		if(!$where['level']) unset($where['level']);
		$User = M('user');
		if($User->save($where)){
			$this->ajaxReturn('','修改成功!',1);
		}else{
			$this->ajaxReturn('','修改失败!',0);
		}
	}
	
	/**
	 * 增加角色
	 * 
	 */
	public function addRoleHandler(){
		if(!IS_POST) $this->error('该页面不存在');
		$addRole = M('roles');
		$rolename = I('rolename');
		if(!$addRole->where(array('rolename'=>$rolename))->count()){
			if($addRole->add($_POST)){
				$this->success('增加角色成功!');
			}else{
				$this->error('增加角色失败!');
			}
		}else{
			$this->error('该角色已经存在!');
		}
	}
	
	/**
	 * 管理员删除帐号信息
	 * 	 */
	
	public function deleteUser(){
		$url = U(APP_NAME . '/User/index');
		if(!IS_POST || !IS_AJAX) $this->error('该页面不存在',$url);
		$where = $_POST;
		$User = M('user');
		$loginname = $User->where($where)->find();
		if($loginname['loginname'] == 'admin'){
			$this->ajaxReturn('','管理员帐号不能删除!',0);
		}
		if($User->where($where)->delete()){
			$this->ajaxReturn('','删除帐号成功!',1);
		}else{
			$this->ajaxReturn('','删除帐号失败',0);
		}
	}
	
	/**
	 * 修改密码显示视图
	 */
	public function manager(){
		$this->display();
	}
	/**
	 * 修改密码处理
	 */
	public function managerHandle(){
		if(!IS_POST) $this->error("该页面不存!",U(APP_NAME . '/Search/search'));
		$user = M('user');
		$url = U(APP_NAME . '/User/manager');
		$userinfo = $user->where(array('loginname'=>I('loginname')))->find();
		if($userinfo){
			if($userinfo['password'] != md5(I('oldpassword'))){
				$this->error('原密码不正确',$url);
			}
			if($userinfo['password'] == md5(I('password'))){
				$this->error('要修改的密码与原密码相同!',$url);
			}
			$result = $user->where(array('loginname'=>I('loginname')))->save(array('password'=>md5(I('password'))));
			if($result){
				$this->success('修改密码成功!',$url);
			}else{
				$this->error('修改密码失败!',$url);
			}
		}else{
			$this->error('帐号不存在!',$url);
		}
	}
	/**
	 * 增加用户视图
	 */
	 public function adduser(){
	 	$this->display();
	 }
	 
	/**
	 * 增加用户处理
	 */
	public function adduserHandler(){
		if(!IS_POST) $this->error("该页面不存!",U(APP_NAME . '/Search/search'));
		$user = M('user');
		$url = U(APP_NAME . '/User/adduser');
		$where = $_POST;
		$userinfo = $user->where(array('loginname'=>$where['loginname']))->find();
		if($userinfo){
			$this->error('用户已存在!',$url);
		}else{
			$where['password'] = md5($where['password']);
			$insertId = $user->add($where);
			if($insertId){
				$this->success('用户增加成功!',$url);	
			}else{
				$this->error('用户增加失败!',$url);
			}
		}
		
	}
}
?>