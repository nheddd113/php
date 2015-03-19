<?php
class EmptyAction extends Action{
	public function _empty(){
		$this->error('页面不存在1111',U('Search'));
	}
}
?>