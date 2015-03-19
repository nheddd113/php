<?php
/*
 * Created on 2013-9-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class SearchAction extends CommonAction{

	/**
	 * 显示指定的机房下的所有机柜
	 * Enter description here ...
	 */
	public function house(){
		$initData = $this->getInitData();
		$this->assign('house',$initData['idcHouse'])->assign('cupborad',$initData['idcPlace'])->assign('count',$initData['count']);
		$this->assign('gameCode',$initData['gameCode']);
		$houid = I('houid');
		$m = M();
		$cupBoard = $m->table('uqee_cupboard,uqee_house')->where('uqee_house.id=houid and houid='.$houid)->field('uqee_cupboard.id,cupname,houname,seatnum,uqee_cupboard.houid,uqee_cupboard.createtime,uqee_cupboard.remark')->select();
//		p($cupBoard);die;
		$this->assign('cupboard',$cupBoard);
		$this->display();
	}
	/**
	 * 删除机柜
	 */
	public function deletecup(){
		$where['id'] = I('id');
		$handle = D("Cupboard");
		$list = $handle->relation(true)->find($where['id']);
		if(count($list['hostlist'])>0){
			$this->error('该机柜下还有在架服务器,不能删除!');
		}else{
			unset($list['hostlist']);
			if($handle->relation('seatinfo')->delete($where['id'])){
				$this->success('删除机柜成功!');
			}else{
				$this->error('删除机柜失败!');
			}
		}
	}

	/**
	 * 显示指定机柜里的所有服务器
	 * Enter description here ...
	 */
	public function cupBoard(){
		$initData = $this->getInitData();
		$this->assign('house',$initData['idcHouse'])->assign('cupborad',$initData['idcPlace'])->assign('count',$initData['count']);
		$this->assign('gameCode',$initData['gameCode']);
		$where = array('cupid'=>I('cupid'));
		$count = M('Hostlist')->where($where)->where('ishost!=2')->count();
		$page = $this->startPage($count, 30);
		$limit = $page->firstRow .','.$page->listRows;
		$hostinfo = M('Hostlist')->where($where)->where('ishost!=2')->limit($limit)->select();
		$hostinfo = $this->serverChange($hostinfo,$initData);
		$cup = M('cupboard')->where(array('id'=>$where['cupid']))->find();
		$this->cup = $cup;
		$this->assign('hostinfo',$hostinfo);
		$this->assign('page',$page->show());
		$this->display();

	}
	/**
	 * 统计查询处理.
	 * Enter description here ...
	 */
	public function search(){
		$initData = $this->getInitData();
		$this->assign('house',$initData['idcHouse'])->assign('cupborad',$initData['idcPlace'])->assign('count',$initData['count']);
		$this->assign('gameCode',$initData['gameCode'])->assign('OpsArray',$initData['idcOps']);
		$where = $_GET;
		unset($where['_URL_']);
		$count = M('Hostlist')->where($where)->count();
		$page = $this->startPage($count, 30);
		$limit = $page->firstRow .','.$page->listRows;
		$hostinfo = M('Hostlist')->where($where)->limit($limit)->select();
//		$hostinfo = M('Hostlist')->limit('0,50')->select();
		$hostinfo = $this->serverChange($hostinfo,$initData);
		$this->assign('houid',$where['houid'])->assign('gameid',$where['gameid']);
		$this->owner = i('owner');
		$this->assign('hostinfo',$hostinfo);
		$this->assign('page',$page->show());
		$this->display();
	}

	/**
	 * POST查询处理
	 * Enter description here ...
	 */
	public function postHandle(){
		$initData = $this->getInitData();
		$queryStr = trim(I('query'));
		switch(I('query_type')){
			case 'remark':
			case 'mainip':
			case 'sertag':
			case 'hostid':$table = 'Hostlist';$type = I('query_type');break;
			case 'log' : $table = 'Log';$type = 'serip';break;
		}
		$where[$type] = array('like','%'.$queryStr);
		if($this->_get('houid','','')){
			$where['houid'] = $this->_get('houid');
		}
		$count = M($table)->where($where)->count();
		$page = $this->startPage($count, 30);
		$limit = $page->firstRow .','.$page->listRows;
		$hostinfo = M($table)->where($where)->limit($limit)->select();
		$hostinfo = $this->serverChange($hostinfo,$initData);
		$this->assign('house',$initData['idcHouse'])->assign('cupborad',$initData['idcPlace'])->assign('count',$initData['count']);
		$this->assign('gameCode',$initData['gameCode']);
		$this->assign('hostinfo',$hostinfo);
		$this->assign('page',$page->show());
		$this->display('search');

	}
	/**
	 * 按机房显示所属机房的所有服务器
	 * Enter description here ...
	 */
	public function houseList(){
		$where = $_GET;
		unset($where['_URL_']);
		$result = M('House')->where($where)->select();
		$where['houid'] = $result[0]['id'];
		$count = M('Hostlist')->where($where)->count();
		$page = $this->startPage($count, 30);
		$limit = $page->firstRow .','.$page->listRows;
		$hostinfo = M('Hostlist')->where($where)->limit($limit)->select();
		$initData = $this->getInitData();
		$hostinfo = $this->serverChange($hostinfo,$initData);
		$this->assign('house',$initData['idcHouse'])->assign('cupborad',$initData['idcPlace'])->assign('count',$initData['count']);
		$this->assign('gameCode',$initData['gameCode']);
		$this->assign('hostinfo',$hostinfo);
		$this->assign('page',$page->show());
		$this->display('search');
	}
	public function ownList(){
		$where = $_GET;
		unset($where['_URL_']);
		$count = M('Hostlist')->where($where)->count();
		$page = $this->startPage($count, 30);
		$limit = $page->firstRow .','.$page->listRows;
		$hostinfo = M('Hostlist')->where($where)->limit($limit)->select();
		$initData = $this->getInitData();
		$hostinfo = $this->serverChange($hostinfo,$initData);
		$this->assign('house',$initData['idcHouse'])->assign('cupborad',$initData['idcPlace'])->assign('count',$initData['count']);
		$this->assign('gameCode',$initData['gameCode']);
		$this->assign('hostinfo',$hostinfo);
		$this->assign('page',$page->show());
		$this->display('search');
	}

}
?>