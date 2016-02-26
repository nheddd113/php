<?php
class PrivateAction extends Action{
	public function index(){
		$model = M('Notify');
		$state = $model->field('state')->find();
		echo $state['state'];
	}
	public function test(){
		echo "客户端IP: ". get_client_ip();
	}
    public function log(){
        echo "<pre>";
        $model = M('Private');
        echo $model->getPK();
        $model->where('id=%d',1)->setInc('uid',10);
        echo $model->getLastSql();

    }
    public function import(){
        $bak = D('SshloginLogBak')->order('id desc')->select();
        foreach($bak as $v){
            $c = explode(' ', $v['content']);
            $c[10] = substr($c[10] . ' ' . $c[11],0,-1);
            $data = array('login_name'=>$c[1],'dest_ip'=>$c[8],
                'dest_hostname'=>$c[13],'request_ip'=>$c[4],
                'login_time'=>$c[10]);
            // M('SshloginLog')->add($data);
        }
    }
    public function test1(){
        p($_SERVER);
    }

}

?>
