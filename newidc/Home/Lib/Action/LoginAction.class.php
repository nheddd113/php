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
        if(!IS_POST) $this->error("网页不存在!",U('Index/index'));
        $username = I('userName');
        $password = md5(I('userPass'));
        $code = md5(I('verify','','strtoupper'));
        if($code != session('verify')){
            session('verify',null);
            $this->error('验证码错误',U('index'));
        }
        if(M('user')->where(array('loginname'=>$username))->count() == 0){
            $this->error('登陆帐号不存在!',U('index'));
            return;
        }
        $result = M('user')->where(array('loginname'=>$username))->field('id,password,realname,level,allowip')->find();
        if ($password != $result['password']){
            $this->error('登陆帐号与密码不匹配!',U('index'));
            return;
        }
        if(!empty($result['allowip'])){
            if($result['allowip'] != "*"){
                $allowip = explode(',', $result['allowip']);
                $clientip = get_client_ip();
                if(!in_array($clientip,$allowip)){
                    $this->error('服务器内部错误!1',U('index'));
                }
            }
        }else{
            $this->error('服务器内部错误!',U('index'));
        }
        session('username',$username);
        session('realname',$result['realname']);
        session('level',$result['level']);
        session('userid',$result['id']);
        $data = array(
            'id' => $result['id'],
            'loginname'=>$username,
            'password' => $password,
            'lastloginip'=>get_client_ip(),
            'lastlogintime' => time()
        );
     // p(C('SESSION_EXPIRE'));die;
        if(M('User')->save($data)){
            $this->redirect(APP_NAME.'/Index/index');
        }else{
            $this->redirect(APP_NAME.'/Login/index');
        }

    }
    public function logout(){
        session(null);
        session('[destroy]');
        $this->display('index');
    }
    Public function createCode(){
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }

}
?>
