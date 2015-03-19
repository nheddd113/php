<?php
/*
 * Created on 2013-12-3
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class CommonAction extends Action{
	public $header = array("Accept: application/json");
 	function _initialize(){
 		header("Content-Type: text/html; charset=UTF-8");
 		$where = $_GET;
 		if($where['_URL_'][0] == 'Amount' && $where['_URL_'][1] == 'start'){
 			return;
 		}
    	if(session('username') == ''){
    		$this->redirect(APP_NAME . '/Login/index','',1,'用户尚未登陆,请登陆!');
    		return;
    	}
 	}
 	public function getCountData($initData){
 		define('NOEYEAR',365*24*60*60);
 		$hostInHouse = array();  //按机房查统计主机带宽单价
 		$where = array();
 		$where['ismanager'] = 0;
 		$where['ishost'] = array('neq',2);
 		foreach($initData['idcHouse'] as $value){
 			$where['houid'] = $value['id'];
 			$count = M('hostlist')->where($where)->count();  //一个机房有多少台机器
 			$priceOfUnit = ceil($value['price'] * $value['bandwidth'] / $count / 30);   //每天每台服务器的带宽钱
// 			$hostInHouse[$value['id']]['count'] = $count;
 			$hostInHouse[$value['id']] = $priceOfUnit;
 		}
 		unset($where['houid']);
 		$hostInCupboard = array() ; //按机柜统计主机单价
 		foreach($initData['idcPlace'] as $value){
 			$where['cupid'] = $value['id'];
 			$count = M('hostlist')->where($where)->count();
 			$priceOfUnit = ceil($value['price'] / 30 / $count);
 			$hostInCupboard[$value['id']] = $priceOfUnit;
 		}
 		//查取服务器价格
 		unset($where['cupid']);
 		$hostCount = M('hostlist')->where($where)->select();
// 		p($hostCount);
 		$hostAmount = array();
 		foreach($initData['game'] as $key=>$value){
 			$hostAmount[$key] = array();  //初始化为每个业务存放格式为数组
 		}
 		foreach($hostCount as $value){
 			$createtime = strtotime($value['createtime']);
 			$currtime = time();
 			if($currtime - $createtime > NOEYEAR*3){
 				$hostAmount[$value['gameid']]['hostPrice'] += 0;
 			}elseif($currtime - $createtime > NOEYEAR*2){
 				$hostAmount[$value['gameid']]['hostPrice'] += $initData['priceOfhost'][$value['templateid']][3];
 			}elseif($currtime - $createtime > NOEYEAR*1){
 				$hostAmount[$value['gameid']]['hostPrice'] += $initData['priceOfhost'][$value['templateid']][2];
 			}else{
 				$hostAmount[$value['gameid']]['hostPrice'] += $initData['priceOfhost'][$value['templateid']][1];
 			}
 			$hostAmount[$value['gameid']]['bandwidth'] += $hostInHouse[$value['houid']];
 			$hostAmount[$value['gameid']]['cupboard'] += $hostInHouse[$value['cupid']];

 		}
 		return $hostAmount;

 	}

 	public function getDateRang($start,$end){
 		define('ONEDAY',24*60*60);
 		$startTime = strtotime($start);
 		$endTime = strtotime($end);
 		$returnDate = array();
 		do{
 			$returnDate[] = date('Y-m-d',$startTime);
 			$startTime += ONEDAY;
 		}while($startTime <= $endTime);
 		return $returnDate;
 	}

 /**
 * 初始化时候的所有数据
 * Enter description here ...
 */
 	public function getInitData(){
		$returnData = array();
		$returnData['gameCode'] = M('Game')->select();
		$returnData['hostCount'] = M('Hostlist')->count();
		$returnData['idcHouse'] = M('House')->order('place')->select();
		$returnData['idcPlace'] = M('Cupboard')->select();
		$returnData['idcOps'] = M('Ops')->select();
		$returnData['template'] = M('hosttemplate')->select();
		foreach($returnData['template'] as $value){
			$returnData['priceOfhost'][$value['id']][1] = ceil($value['price'] * $value['one'] / 100 / 365);
			$returnData['priceOfhost'][$value['id']][2] = ceil($value['price'] * $value['two'] / 100 / 365);
			$returnData['priceOfhost'][$value['id']][3] = ceil($value['price'] * $value['three'] / 100 / 365);
			$returnData['priceOfhost'][$value['id']][4] = ceil($value['price'] * $value['other'] / 100 / 365);
		}
		foreach($returnData['idcOps'] as $value){
			$returnData['owner'][$value['seq']] = $value['name'];
		}
		foreach($returnData['gameCode'] as $value){
			$returnData['game'][$value['id']] = $value['name'];
		}
		foreach($returnData['idcPlace'] as $value){
			$returnData['cup'][$value['id']] = $value['cupname'];
		}
		foreach($returnData['idcHouse'] as $value){
			$returnData['house'][$value['id']] = $value['houname'];
		}
		//统计物理机器非托管
		$returnData['count']['notWXZ'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>0,'status'=>1))->count();
		$returnData['count']['notWXJ'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>0,'status'=>0))->count();
		$returnData['count']['notWSQ'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>0,'hostype'=>1))->count();
		$returnData['count']['notWYY'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>0,'hostype'=>2))->count();
		$returnData['count']['notWHOST'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>1,))->count();
		//统计物理机器托管
		$returnData['count']['WXZ'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>0,'status'=>1))->count();
		$returnData['count']['WXJ'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>0,'status'=>0))->count();
		$returnData['count']['WSQ'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>0,'hostype'=>1))->count();
		$returnData['count']['WYY'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>0,'hostype'=>2))->count();
		$returnData['count']['WHOST'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>1,))->count();

		//统计虚拟机器非托管
		$returnData['count']['notXXZ'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>2,'status'=>1))->count();
		$returnData['count']['notXXJ'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>2,'status'=>0))->count();
		$returnData['count']['notXSQ'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>2,'hostype'=>1))->count();
		$returnData['count']['notXYY'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>2,'hostype'=>2))->count();
		//统计虚拟机器托管
		$returnData['count']['XXZ'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>2,'status'=>1))->count();
		$returnData['count']['XXJ'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>2,'status'=>0))->count();
		$returnData['count']['XSQ'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>2,'hostype'=>1))->count();
		$returnData['count']['XYY'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>2,'hostype'=>2))->count();

		$returnData['count']['notWCOUNT'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>0))->count();
		$returnData['count']['WCOUNT'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>0))->count();

		$returnData['count']['notXCOUNT'] = M('Hostlist')->where(array('ismanager'=>0,'ishost'=>2))->count();
		$returnData['count']['XCOUNT'] = M('Hostlist')->where(array('ismanager'=>1,'ishost'=>2))->count();

		return $returnData;
	}
	public function startPage($count,$eachRow){
		import("ORG.Util.Page");
		$objPage = new Page($count,$eachRow);
		return $objPage;

	}

	/**
	 * 搜索页主机信息整理
	 * Enter description here ...
	 * @param unknown_type $hostinfo
	 * @param unknown_type $initData
	 */
	public function serverChange($hostinfo,$initData){
		foreach($hostinfo as $key => $row){
			if(array_key_exists('hostdbid',$row)){
				$hostinfo[$key]['id'] = $hostinfo[$key]['hostdbid'];
			}
			foreach($initData['idcHouse'] as $house){
				if ($row['houid'] == $house['id']){
					$hostinfo[$key]['housename'] = $house['houname'];
				}
			}
			foreach($initData['gameCode'] as $game){
				if ($row['gameid'] == 0){
					$hostinfo[$key]['gamename']='暂无';
					continue;
				}
				if ($row['gameid'] == $game['id']){
					$hostinfo[$key]['gamename'] = $game['name'];
				}

			}
			foreach($initData['idcOps'] as $owner){
				if ($hostinfo[$key]['owner'] == $owner['seq']){
					$hostinfo[$key]['ownername'] = $owner['name'];
					continue;
				}

			}
			switch($row['ishost']){
				case 0:$hostinfo[$key]['ishost'] = '服务器';break;
				case 1:$hostinfo[$key]['ishost'] = '宿主机';break;
				case 2:$hostinfo[$key]['ishost'] = '虚拟机';break;
				case 3:$hostinfo[$key]['ishost'] = '数据中心';break;
			}
			switch($row['status']){
				case 0:$hostinfo[$key]['status'] = '下架';break;
				case 1:$hostinfo[$key]['status'] = '闲置';break;
				case 2:$hostinfo[$key]['status'] = '上架';break;
			}
			switch($row['hostype']){
				case 0:$hostinfo[$key]['hostype'] = '未运营';break;
				case 1:$hostinfo[$key]['hostype'] = '申&nbsp;&nbsp;请';break;
				case 2:$hostinfo[$key]['hostype'] = '运&nbsp;&nbsp;营';break;
			}
		}
		return $hostinfo;
	}
	/**
	 *
	 * 修改虚拟机状态时. 查看该宿主机下的虚拟机器是不是都处理闲置
	 * 如果都闲置状态,就修改该宿主机的信息
	 *
	 */
	public function chgmanagerHost($where){
		$hostList =  M("Hostlist");
		if($where['ishost'] == 2){
				$tmpWhere['parentid'] = $where['parentid'];
				$tmpWhere['status'] = array('gt',1);
				$virState = $hostList->where($tmpWhere)->count();
				if(!$virState){
					$tmpArr =  array('owner'=>0,'status'=>1,'hostype'=>0,'gameid'=>0);
					$hostList->where(array('id'=>$where['parentid']))->save($tmpArr);
					$hostList->where('parentid='.$where['parentid'])->save($tmpArr);
				}
		}
	}
	/**
	 * 交换服务器信息
	 * @param array
	 * type 为true交换信息 为false交换IP
	 *
	 *  */
	public function switchHost($arr,$tmpArray,$type=true){
		$hostList = M("Hostlist");
		$value = $type?'互换信息!':'互换IP!';
		$souHost = $hostList->field($tmpArray)->where(array('hostid'=>$arr['currid']))->find();
		$descHost = $hostList->field($tmpArray)->where(array('hostid'=>$arr['souid']))->find();
		$beforHost = $hostList->where(array('hostid'=>$arr['currid']))->find();
		$afterHost = $hostList->where(array('hostid'=>$arr['souid']))->find();
		$wrLog = M('log');
		$logData = array();
		if(!$hostList->where(array('hostid'=>$arr['currid']))->save($descHost)) return 1;   //更新失败返回1
		if(!$hostList->where(array('hostid'=>$arr['souid']))->save($souHost)) {   //如果第二台服务器更失败 就还原回第一台服务器
			$hostList->where(array('hostid'=>$arr['currid']))->save($souHost);
			return 1;
		}else{
			if($type){
				Log::write('查看宿主机!');
				$where['ishost'] = $beforHost['ishost'];
				$where['parentid'] = $beforHost['parentid'];
				$this->chgmanagerHost($where);
				$where['ishost'] = $afterHost['ishost'];
				$where['parentid'] = $afterHost['parentid'];
				$this->chgmanagerHost($where);
			}
			$logData['chghostid'] = $beforHost['hostid'];
			$logData['serip'] = $beforHost['mainip'];
			$logData['handler'] = session('realname');
			$logData['hostid'] = $afterHost['hostid'];
			$logData['hostdbid'] = $afterHost['id'];
			$logData['content'] = '该服务器与'.$beforHost['hostid'].$value;
			$wrLog->add($logData);
			$logData['chghostid'] = $afterHost['hostid'];
			$logData['serip'] = $afterHost['mainip'];
			$logData['handler'] = session('realname');
			$logData['hostdbid'] = $beforHost['id'];
			$logData['hostid'] = $beforHost['hostid'];
			$logData['content'] = '该服务器与'.$afterHost['hostid'].$value;
			$wrLog->add($logData);
			return 0;
		}
	}
	/**
	 * 下架服务器
	 * @param array
	 */
	public function hostShutdown($arr){
		$tmpArray = array('mainip'=>'','subip'=>'','innip'=>'','status'=>0,'cupid'=>0,'seatid'=>0,'hostype'=>0,'owner'=>0,'gameid'=>0,'pretime'=>'','starttime'=>'');
		$descHost = M("Hostlist")->where(array('hostid'=>$arr['souid']))->find();
		if(M("Hostlist")->where(array('hostid'=>$arr['souid']))->save($tmpArray)) {
			$tmpState = array('state'=>0);
			M('Iplist')->where(array('mainip'=>$descHost['mainip']))->save($tmpState);
			M('Seat')->where(array('cupid'=>$descHost['cupid'],'seatid'=>$descHost['seatid']))->save($tmpState);
			$wrLog = M('log');
			$logData = array();
			$logData['content'] = '服务器下架!';
			$logData['serip'] = '';
			$logData['hostid'] = $descHost['hostid'];
			$logData['hostdbid'] = $descHost['id'];
			$logData['handler'] = session('realname');
			$wrLog->add($logData);
			return 0;
		}else{
			return 1;
		}
	}
	/**
	 * 更新状态
	 * @param array updateArray
	 */
	public function updateState($updateArray){
		foreach($updateArray as $tableName=>$arr){
			M($tableName)->where($arr['where'])->save($arr['result']);
		}
	}
    public function post($url,$header,$data=null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 29);
		curl_setopt($ch, CURLOPT_TIMEOUT, 29);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$ret = curl_exec($ch);
		$info = curl_getinfo($ch);
		if($info['http_code'] == 200){
			return json_decode($ret,true);
		}
		return false;
    }

 }
?>
