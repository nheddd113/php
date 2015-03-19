<?php

class LogAction extends CommonAction {
	public function index(){
		$logData = M('Log')->select();
		$this->logdata = $logData;
		$this->display();
	}
}
?>