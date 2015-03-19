<?php

class TempAction {
	public function index(){
		$hostList = M('hostlist');
		$hostInfo = $hostList->field('id,mem,disk')->select();
		foreach($hostInfo as $value){
			$tmpArr = array();
			$tmpArr['mem'] = str_replace('g','',str_replace('b','',strtolower($value['mem'])));
			$tmpArr['disk'] = str_replace('g','',str_replace('b','',strtolower($value['disk'])));
			$hostList->where(array('id'=>$value['id']))->save($tmpArr);
		}
	}
}
?>