<?php

class probjectAmount extends Think {
//项目服务器使用情况统计
/**
 * oldData 修改前数据
 * newData 修改后数据
 * type: switch,交换主机,
 */
	function addRecord($oldData,$newData,$type=false){
		$addAmount = M('hostamount');
		if($oldData['gameid'] == $newData['gameid']){
			//交换主机,修改主机的开始时间为对方开始时间
			if($type && $oldData['gameid'] != 0){  
				$onewhere = array('objid'=>$oldData['gameid'],'hostid'=>$oldData['id']);
				$twowhere = array('objid'=>$newData['gameid'],'hostid'=>$newData['id']);
				$one = $addAmount->where($onewhere)->find();
				$two = $addAmount->where($twowhere)->find();
				
				$addAmount->where($onewhere)->save(array('starttime'=>$two['starttime']));
				$addAmount->where($twowhere)->save(array('starttime'=>$one['starttime']));
			}
			return false;
		}else{
			$where = array('objid'=>$oldData['gameid']);
			$where['hostid'] = $oldData['id'];
			if($addAmount->where($where)->count()){  //如果修改前的数据在hostamount表时存在,就设置当前时间为结果时间
				$result = array('endtime'=>time());
				$addAmount->where($where)->save($result);
			}
			//如果修改后的gameid等于0就直接返回,说明服务器没有被项目组使用
			if($newData['gameid'] == 0) return false;  
			$result = array();
			$result['objid'] = $newData['gameid'];
			$result['hostid'] = $newData['id'];
			$result['starttime'] = time();
			$addAmount->add($result);
//			Log::write("插入SQL:" . $addAmount->getLastSql());
		}
	}
	function shutdownRecord($oldData){
		$addAmount = M('hostamount');
		//如果修改后的gameid等于0就直接返回,说明服务器没有被项目组使用
		if($oldData['gameid'] == 0) return false;
		$where['objid'] = $oldData['gameid'];
		$where['hostid'] = $oldData['id'];
		if($addAmount->where($where)->count()){
			$result = array('endtime'=>time());
			$addAmount->where($where)->save($result);
		}
	}
}
?>