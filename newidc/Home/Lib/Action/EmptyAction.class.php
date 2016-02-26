<?php
class EmptyAction extends Action{
	public function _empty(){
		$this->error('页面不存在',U('Index/index'));
	}
}
?>
