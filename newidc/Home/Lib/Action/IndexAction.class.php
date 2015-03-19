<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {
    public function index(){
		$initData = $this->getInitData();
		$model = M('Notify');
		$state = $model->field('state')->find();
		$this->notify = $state['state'];
		$this->house = $initData['idcHouse'];
		$this->cupborad = $initData['idcPlace'];
		$this->count = $initData['count'];
		$this->display();
    }
}