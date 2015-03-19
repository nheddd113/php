<?php


/*
 * Created on 2013-9-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class CreateAction extends CommonAction {
	public function index() {
		$this->display();
	}
	/**
	 * 服务器模板增加视图
	 * 
	 */
	 
	public function hostTempLate(){
		
		$this->display();
	}
	
	public function addTempLateHandle(){
		if(!IS_POST) $this->error('该页面不存在!');
		$where = $_POST;
		if(!is_numeric($where['mem'])) $this->error('内存大小只能为数字!');
		if(!is_numeric($where['disk'])) $this->error('硬盘大小只能为数字!');
		if(!is_numeric($where['price'])) $this->error('价格只能为数字!');
		$tempLate = M('hosttemplate');
		if($tempLate->where(array('name'=>$where['name']))->count()){
			$this->error('该模板已经存在,不能重复增加!');
		}
		if($tempLate->add($where,'',true)){
			$this->success('模板:'.$where['name'] . ' 增加成功!');
		}
	}
	
	/**
	 * 增加IP视图
	 */
	public function addIp(){
		$initData = $this->getInitData();
		$this->house = $initData['idcHouse'];
		$this->display();
	}

	/**
	 * 增加IP处理
	 */

	public function addIpHandle(){
		$data = $_POST;
		if($data['where'] == 1){  //批量增加IP时处理方法.
			$adddata = array();
			$ipArr = explode('.',$data['mainip']);
			$range = $ipArr[3];
			$deff = explode('.',$data['range']);
			$manyIp = $deff[count($deff)-1];
			$count = $manyIp - $range;
			if($data['duline'] == 1){
				$subIpArr = explode('.',$data['subip']);
				$subipsuff = $subIpArr[3];
			}
			$state = false;
			for($i = 0;$i<=$count;$i++){
				$ipArr[3] = $range + $i;
				$ip = join('.',$ipArr);
				if($data['duline'] == 1) {
					$subIpArr[3] = $subipsuff + $i;
					$subip = join('.',$subIpArr);
				}
				if(M('Iplist')->where(array('mainip'=>$ip))->count()!=0){
					$state = true;  //ip存在
					break;
				}else{
					$data['subip'] = isset($subip)?$subip:'';
					$data['mainip'] = $ip;
					$adddata[] = $data;
				}
			}
			if($state){
				$this->error('增加IP失败!',U(APP_NAME . '/Create/addIp'));
				return;
			}else{
				if(M('Iplist')->addAll($adddata)){
					$this->success('增加IP成功!',U(APP_NAME . '/Create/addIp'));
				}else{
					$this->error('增加IP失败!',U(APP_NAME . '/Create/addIp'));
				}
				
			}
			return;
		}
		//先检查Ip是不是已经存在.
		if(M('Iplist')->where(array('mainip'=>$data['mainip']))->count()!=0){
			$this->error('增加的IP已经存在!',U(APP_NAME . '/Create/addIp'));
		}
		
		//增加的IP写入数据库
		if(M('Iplist')->add($data)){
			$this->success('增加IP成功!',U(APP_NAME . '/Create/addIp'));
		}else{
			$this->error('增加IP失败!',U(APP_NAME . '/Create/addIp'));
		}
	
	}

	/**
	 * 增加虚拟机
	 * 
	 */
	public function addVirHost() {
		$model = M('Hostlist');
		$hostid = I('id');
		$hostinfo = $model->where(array (
			'id' => $hostid
		))->find();
		if($virHostList = $model->where(array('parentid'=>$hostid))->field('hostid')->select()){
			$i=101;
			foreach($virHostList as $value){
				$tmpHostid = explode('-',$value['hostid']);
				while($i<=$tmpHostid[count($tmpHostid)-1]){
					$i++;
				}
			}
			$hostinfo['hostid'] .= '-'.$i;
		}else{
			$hostinfo['hostid'] .= '-101';
		}
		
		$houseinfo = M('House')->where(array (
			'id' => $hostinfo['houid']
		))->find();
		$hostinfo['houname'] = $houseinfo['houname'];
		$initData = $this->getInitData();
		$cupidArr = M('Cupboard')->where(array (
			'id' => $hostinfo['cupid']
		))->find();
		$hostinfo['cupname'] = $cupidArr['cupname'];
		$this->assign('hostinfo', $hostinfo);
		$this->assign('gameCode', $initData['gameCode']);
		$this->display();
	}
	
	/**
	 * 增加虚拟时处理表单 
	 */
	public function addVirHandle(){
		if (!IS_POST)
			$this->error("访问错误", U('Search/search'));
		$where = $_POST;
		import('@.Common.idclog');
		$startLog = new idclog;
		$where['ishost'] = 2;
		$hostadd = M('hostlist');
		$url = U(APP_NAME . '/Create/addVirHost',array('id'=>I('parentid')));
		$managerHost = $hostadd->where('id='.I('parentid'))->field('gameid')->find();
		$virCount = $hostadd->where('parentid='.I('parentid'))->count();
		$virCount+=I('hostMany');
		if($where['isMany'] == 1){
			if(I('hostMany') && $virCount <=10){
				$hostid = $where['hostid'];
				$idArr = explode('-',$hostid);
				for($i=0;$i<I('hostMany');$i++){
					// p($idArr);
					$where['hostid'] = join('-',$idArr);
					$where['gameid'] = $managerHost['gameid'];
					$allWhere[] = $where;
					$idArr[3]++;
				}
			}else{
				$this->error('该宿主机上虚拟机已达到上限!',$url);
			}
			// p($allWhere);die;
			if($hostadd->addAll($allWhere)){
				$hostidList = '';
				foreach($allWhere as $value){
					$hostidList.= $value['hostid'].'、';
				}
				$hostidList = substr($hostidList,0,-1);
				$startLog->hostCreateAndDelete($where['parentid'],$hostidList,true,false);
				$this->success('增加成功!',$url);
			}else{
				$this->success('部份虚拟机增加成功!',$url);
			}
		}else{
			if(++$virCount >10){
				$this->error('该宿主机上虚拟机已达到上限!',$url);
			}
			$where['gameid'] = $managerHost['gameid'];
			if($hostadd->add($where)){
				if($where['mainip']){
					$stateArr = array('state'=>1);
					$newwhere = array('mainip'=>$where['mainip']);
					$ipHandle = M('iplist');
					$ipHandle->where($newwhere)->save($stateArr);
				}
				$startLog->hostCreateAndDelete($where['parentid'],$where['hostid'],true);
				$this->success('增加成功!',$url);
			}else{
				$this->error('增加失败!',$url);
			}
		}
	}

	/**
	 * 更新运营商
	 * Enter description here ...
	 */
	public function addOps() {
		$url = 'http://gamemanager.uqee.com/game/idc/operator';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		$ret = curl_exec($ch);
		if (strlen($ret)) {
			$opsArr = array ();
			$ret = substr($ret, 1, -1);
			$retArr = explode(',', str_ireplace('"', '', $ret));
			$i = 0;
			$errorArray = array ();
			foreach ($retArr as $value) {
				$tmpArr = explode(':', $value);
				$opsArr['seq'] = $tmpArr[0];
				$opsArr['name'] = $tmpArr[1];
				$i++;

				if (M('Ops')->where(array (
						'seq' => $opsArr['seq']
					))->count() > 0) {
					$result = M('Ops')->where(array (
						'seq' => $opsArr['seq']
					))->find();
					if ($result['name'] == $opsArr['name'])
						continue;
					if (!M('Ops')->where(array (
							'seq' => $opsArr['seq']
						))->save($opsArr)) {
						$errorArray[] = $opsArr['name'];
					}
				} else {
					if (!M('Ops')->add($opsArr)) {
						$errorArray[] = $opsArr['name'];
					}
				}
			}
			if (count($errorArray) > 0) {
				$this->error('更新运营商失败!', U('/Search/search'));
			} else {
				$this->success('更新运营成功!', U('/Search/search'));
			}
		} else {
			$this->error('更新运营商失败!', U('/Search/search'));
		}
	}

	public function addOpsHandle() {
	
	}

	/**
	 * 增加游戏
	 * Enter description here ...
	 */
	public function addGame() {
		$initData = $this->getInitData();
		$this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
		$this->display();

	}

	public function addGameHandle() {
		if (!IS_POST)
			$this->error("访问错误", U('Index/index'));
		$name = I('name');
		if (M('Game')->where(array (
				'name' => $name
			))->count() > 0) {
			$this->error('此游戏已存在,请检查后重增加!', U(APP_NAME . '/Create/addGame'));
		}
		if (M('Game')->add($_POST)) {
			$this->success("增加成功", U(APP_NAME . "/Create/addGame"));
		} else {
			$this->error("增加失败", U(APP_NAME . "/Create/addGame"));
		}

	}

	/**
	 * 增加主机
	 * Enter description here ...
	 */
	public function addHost() {
		$initData = $this->getInitData();
		$template = M('hosttemplate')->select();
		$this->template = $template;
		$this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
		$this->display();

	}

	/**
	 * 处理增加主机
	 */
	public function addHostHandle() {
		if (!IS_POST)
			$this->error("访问错误", U('Index/index'));
		import('@.Common.idclog');
		$startLog = new idclog;
		$hostid = I('hostid');
		$hostlist = M('Hostlist');
		if ($hostlist->where(array (
				'hostid' => $hostid
			))->count() > 0) {
			$this->error('此主机已存在,请检查后重增加!', U(APP_NAME . '/Create/addHost'));
		}
		if ($insertId = $hostlist->add($_POST)) {
			$startLog->hostCreateAndDelete($insertId,I('hostid'),true,true);
			$this->success("增加成功", U(APP_NAME . "/Create/addHost"));
		} else {
			$this->error("增加失败", U(APP_NAME . "/Create/addHost"));
		}

	}

	/**
	 * 显示增加机房模板
	 * Enter description here ...
	 */
	public function addHouse() {
		$initData = $this->getInitData();
		$this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
		$this->placeList = C('place');
		$this->display();
	}

	/**
	 * 添加机房POST处理
	 * Enter description here ...
	 */
	public function addHouseHandle() {
		if (!IS_POST)
			$this->error("访问错误", U('Index/index'));
		$houname = I('houname');
		if(!is_numeric(I('bandwidth'))) $this->error('机房带宽填写有误,请重新填写!');
		if(!is_numeric(I('price'))) $this->error('带宽价格填写有误,请重新填写!');
		if (M('House')->where(array (
				'houname' => $houname
			))->count() > 0) {
			$this->error('此机房已存在,请检查后重增加!', U(APP_NAME . '/Create/addHouse'));
		}
		if (M('House')->add($_POST)) {
			$this->success("增加成功", U(APP_NAME . "/Create/addHouse"));
		} else {
			$this->error("增加失败", U(APP_NAME . "/Create/addHouse"));
		}
	}
	/**
	 * 显示添加机柜模板
	 * Enter description here ...
	 */
	public function addCupboard() {
		$initData = $this->getInitData();
		$this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
		$this->display();
	}

	public function addCupboardHandle() {
		if (!IS_POST)
			$this->error("访问错误", U('Index/index'));
		$cupname = I('cupname');
		$houid = I('houid');
		$price = I('price');
		if(!is_numeric($price)) $this->error('机柜单价应该是纯数字!');
		if (M('Cupboard')->where(array (
				'cupname' => $cupname,
				'houid' => $houid
			))->count() > 0) {
			$this->error('此机房在该机房已经存在,请确认后得新添加!', U(APP_NAME . '/Create/addCupboard'));
		}
		if (M('Cupboard')->add($_POST)) {
			$result = M('Cupboard')->where(array (
				'cupname' => $cupname,
				'houid' => $houid
			))->find();
			for ($i = 1; $i <= I('seatnum'); $i++) {
				$tmpArr = array (
					'cupid' => $result['id'],
					'seatid' => $i
				);
				M('Seat')->add($tmpArr);
			}
			$this->success('添加成功!', U(APP_NAME . '/Create/addCupboard'));
		} else {
			$this->error('添加失败!', U(APP_NAME . '/Create/addCupboard'));
		}
	}
}
?>