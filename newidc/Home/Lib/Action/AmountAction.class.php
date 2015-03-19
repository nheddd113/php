<?php

class AmountAction extends CommonAction{
	public function index(){
//		$addAmount = M('hostamount');
//		$result = $addAmount->select();
//		foreach($result as $k=>$value){
//			$result[$k]['data'] = json_decode($value['data'],true);
//		}
//		p($result);
		$this->display();
		
	}
	/**
	 * 
	 * 费用明细
	 * 
	 */
	public function info(){
		$initData = $this->getInitData();
		$where['time'] = array(array('ge',I('start')),array('le',I('end')),'and');
		$amountInfo = M('hostamount');
		$result = $amountInfo->where($where)->select();
		foreach($result as $k=>$r){
			$newdata = json_decode($r['data'],true);;
			$newArr[$r['time']] = $newdata['amount'];
		}
		$length = count($initData['game']);
		$this->length = $length;
		$this->amountInfo = $newArr;
		$this->game = $initData['game'];
		$this->display();
	}
	public function start(){
		$initData = $this->getInitData();
		$countData['amount'] = $this->getCountData($initData);
		$addAmount = M('hostamount');
		$data = array();
		$data['time'] = date('Y-m-d');
		$data['data'] = json_encode($countData);
		$addAmount->add($data,'',true);
	}
}
?>