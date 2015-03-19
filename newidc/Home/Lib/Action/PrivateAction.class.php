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
}

?>