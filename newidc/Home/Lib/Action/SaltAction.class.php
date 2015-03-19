<?php
class SaltAction extends Action{
	//允许该问的IP
	protected $ips = array("101.95.5.22,10.0.3.6");
	protected $api;
	public function __construct(){
    	$retip = get_client_ip();
    	if(!in_array($retip, $this->ips)){
    		$this->ajaxReturn('该地址不允许访问','该地址不允许访问',1);
    	}
    	import("@.Common.Salt");
    	$this->api = new Salt();
	}
	public function getUuid(){
    	$ip = I('ip');
    	$ret = $this->api->getUuid($ip);
    	if($ret['state'] == 0){
    		$this->ajaxReturn($ret,'查询成功',$ret['state']);
    	}else{
    		$this->ajaxReturn(array(),'查询失败',$ret['state']);
    	}
	}
}

?>









