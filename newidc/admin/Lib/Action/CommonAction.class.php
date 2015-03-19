<?php
 class CommonAction extends Action{
 	function _initialize(){
    	if(session('idc_admin') == null){
    		$this->error("没有登陆",U(APP_NAME . "/Public/login"));
    	}
 	}
}
?>