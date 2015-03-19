<?php

class MonitorAction extends CommonAction {
	/**
	 * 显示监控视图
	 * 
	 */
	public function index(){
		$monitorConn = M('monitor');
		$monitorList = $monitorConn->select();
		$this->monitorList = $monitorList;
		$this->display();
	}
	/**
	 * 增加监控处理
	 */
	public function addMonitorHandle(){
		$where = I('post.');
		$monitorConn = M('monitor');
		if($monitorConn->where("name='".$where['name']."'")->count()>0){
			$this->error('该监控已经存在!');
		}
		$rep = "http://";
		if(strpos($where['nagios'],$rep) !== 0){
			$this->error('nagios地址应该以'.$rep."开始!");
		}
		if(strpos($where['cacti'],$rep) !== 0){
			$this->error('cacti地址应该以'.$rep."开始!");
		}
		$where['createtime'] = time();
		if($monitorConn->add($where)){
			$this->success('增加成功!');
		}else{
			$this->error("增加失败!");
		}
	}
	/**
	 * 删除监控
	 */
	public function deleteMonitor(){
		$where = $_POST;
		$monitorConn = M('monitor');
		if(!$monitorConn->where($where)->count()){
			$this->ajaxReturn('警告','该监控不存在,不能删除,请联系管理员!',0);
		}
		if($monitorConn->where($where)->delete()){
			$this->ajaxReturn('通知','删除成功!',1);
		}else{
			$this->ajaxReturn('警告','删除失败!',0);
		}
	}
	/**
	 * 修改监控视图
	 */
	public function modify(){
		$where['id'] = I('id');
		$monitorConn = M('monitor');
		$result = $monitorConn->where($where)->find();
		$this->monitor = $result;
		$this->display();
	}
	public function modityMonitorHandle(){
		$where = I('post.');
		unset($where['sub']);
//		p($where);return;
		$monitorConn = M('monitor');
		$name['name'] = $where['name'];
		$id['id'] = $where['id'];
		$result = $monitorConn->where($id)->find();
		unset($result['createtime']);
		$diff = array_diff_assoc($where,$result);
		if(count($diff) == 0){
			$this->error('内容没有被修改,不需要修改操作!');
		}
		if($monitorConn->where($name)->count() > 0 && $result['name'] != $where['name']){
			$this->error('该监控已经存在!');
		}
		$rep = "http://";
		if(strpos($where['nagios'],$rep) !== 0){
			$this->error('nagios地址应该以'.$rep."开始!");
		}
		if(strpos($where['cacti'],$rep) !== 0){
			$this->error('cacti地址应该以'.$rep."开始!");
		}
		if($monitorConn->save($where)){
			$this->success('修改成功!',U('/Monitor/'));
		}else{
			$this->error('修改失败!');
		}
	}
}
?>