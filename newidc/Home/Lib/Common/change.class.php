<?php
class change extends Think{
	function hostchange($oldData,$newData,$initData){
		$data = array_diff_assoc($oldData,$newData);
		Log::write('两个数据的差集'.json_encode($data));
		import('@.Common.idclog');
		$startLog = new idclog;
		//如果mainip修改的话就把原来的IP登记未使用
		if(in_array('mainip',array_keys($data))){
			M('iplist')->where(array('mainip'=>$data['mainip']))->save(array('state'=>0));
		}
		if(in_array('seatid',array_keys($data))){
			$where = array('seatid'=>$data['seatid']);
			if(in_array('cupid',array_keys($data))){
				$where['cupid'] = $data['cupid'];
			}else{
				$where['cupid'] = $oldData['cupid'];
			}
			M('seat')->where($where)->save(array('state'=>0));
		}
		if(in_array('cupid',array_keys($data))){
			$where = array('cupid'=>$data['cupid']);
			if(in_array('seatid',array_keys($data))){
				$where['seatid'] = $data['seatid'];
			}else{
				$where['seatid'] = $oldData['seatid'];
			}
			M('seat')->where($where)->save(array('state'=>0));
		}
		$data = array_diff_assoc($newData,$oldData);
		if(in_array('mainip',array_keys($data))){
			M('iplist')->where(array('mainip'=>$data['mainip']))->save(array('state'=>1));
		}
		if(in_array('seatid',array_keys($data))){
			$where = array('seatid'=>$data['seatid']);
			if(in_array('cupid',array_keys($data))){
				$where['cupid'] = $data['cupid'];
			}else{
				$where['cupid'] = $newData['cupid'];
			}
			M('seat')->where($where)->save(array('state'=>1));
		}
		if(in_array('cupid',array_keys($data))){
			$where = array('cupid'=>$data['cupid']);
			if(in_array('seatid',array_keys($data))){
				$where['seatid'] = $data['seatid'];
			}else{
				$where['seatid'] = $oldData['seatid'];
			}
			M('seat')->where($where)->save(array('state'=>1));
		}
		$startLog->writeLog($newData,$data,$initData);
	}
	
}
?>