<?php
class idclog extends Think{
	/**
	 * 记录日志
	 * $newData 服务器修改过后的数据
	 * $data 服务器修改的数据
	 * $initData为全局初始化数据
	 */
	function writeLog($newData,$data,$initData){  //修改服务器日志记录.
		if(count($data) == 1) return;
		$status = array('下架','闲置','上架');
		$hostype = array('未运营','申请','运营');
		$ishost = array('服务器','宿主机','','存储器');
		$fieldArray = array('mainip'=>'IP修改为:','cupid'=>'机柜修改为:','seatid'=>'机位修改为:',
							'remark'=>'备注修改为:','mem'=>'内存修改为:','cpu'=>'CPU修改为:',
							'disk'=>'硬盘修改为:','status'=>'状态修改为:',
							'hostype'=>'运营状态修改为:','ishost'=>'服务器类型修改为:','owner'=>'运营商修改为:',
							'gameid'=>'游戏修改为:','ismanager'=>'是否托管:','system'=>'系统修改为:');
		foreach($data as $key=>$value){
			if(array_key_exists($key,$fieldArray)){
				if($key == 'starttime' && $value){
					$where['content'] .= $fieldArray[$key] . ';';
				}else{
					if($key == 'status'){
						$where['content'] .= $fieldArray[$key] . $status[$value] .';';
					}elseif($key == 'gameid'){
						$gamename = $initData['game'][$value]?$initData['game'][$value]:'空';
						$where['content'] .= $fieldArray[$key] . $gamename .';';
					}elseif($key == 'owner'){
						$ownername = $initData['owner'][$value] ?$initData['owner'][$value] :'空';
						$where['content'] .= $fieldArray[$key] . $ownername .';';
					}elseif($key == 'ishost'){
						$where['content'] .= $fieldArray[$key] . $ishost[$value] .';';
					}elseif($key == 'cupid'){
						$cupname = $initData['cup'][$value]?$initData['cup'][$value]:'空';
						$where['content'] .= $fieldArray[$key] . $cupname .';';
					}elseif($key == 'ismanager'){
						$isma = $value?'是':'否';
						$where['content'] .= $fieldArray[$key] . $isma .';';
					}elseif($key == 'mainip'){
						$isma = $value?$value:'空';
						$where['content'] .= $fieldArray[$key] . $isma .';';
					}elseif($key == 'hostype'){
						$where['content'] .= $fieldArray[$key] . $hostype[$value] .';';
					}else{
						$where['content'] .= $fieldArray[$key] . $value .';';
					}
				}
			}
		}
		$where['content'] = substr($where['content'],0,-1).'!';
		$where['hostid'] = $newData['hostid'];
		$where['hostdbid'] = $newData['id'];
		$where['serip'] = $newData['mainip'];
		$where['handler'] = session('realname');
		$wrLog = M('log');
		$wrLog->add($where);
		Log::write('增加日志SQL:'.$wrLog->getLastSql());
	}
	/**
	 * @id 宿主机数据ID
	 * @hostid  虚拟机主机ID
	 * @type  true 创建虚拟机日志 false 删除虚拟机日志
	 * @ishost true 服务器  false 虚拟机
	 */
	function hostCreateAndDelete($id,$hostid,$type,$ishost=false){
		$op = $type?'创建':'删除';
		$ishost = $ishost?'服务器':'虚拟机';
		$wrLog = M('log');
		$hostlist = M('hostlist');
		$hostinfo = $hostlist->where('id='.$id)->find();
		Log::write('该服务器信息如下:'.json_encode($hostinfo));
		$where['serip']=$hostinfo['mainip']?$hostinfo['mainip']:'';
		$where['content'] = $op . $ishost . ":" .$hostid.'!';
		$where['hostdbid'] = $id;
		$where['handler'] = session('realname');
		$where['hostid'] = $hostid;
		$wrLog->add($where);
	}
	function hostSystemOp($id,$type,$houname=''){
		switch($type){
			case 4:$op = '关闭游戏';;break;
			case 5:$op = '开启游戏';;break;
			case 6:$op = '获取服务器详细信息';;break;
			case 1:$op = '服务器清档';;break;
			case 2:$op = '服务器下架';break;
			case 3:$op = '服务器寄出至:'.$houname;break;
		}
		$wrLog = M('log');
		$hostlist = M('hostlist');
		$hostinfo = $hostlist->where('id='.$id)->find();
		$where['serip']= $hostinfo['mainip'];
		$where['content'] = $op .'!';
		$where['hostdbid'] = $id;
		$where['handler'] = session('realname');
		$where['hostid'] = $hostinfo['hostid'];
		$op?$wrLog->add($where):null;
	}
	function hostLinkDown($array){
		$wrLog = M('log');
		$where['serip'] = $array['mainip'];
		$where['content'] = '因宿主机下架,虚拟机被迫下架!';
		$where['hostdbid']  = $array['id'];
		$where['handler'] = session('realname');
		$where['hostid'] = $array['hostid'];
		$wrLog->add($where);
	}
}
?>