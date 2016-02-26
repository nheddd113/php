<?php
/*
 * Created on 2013-9-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class SearchAction extends CommonAction {

    public function index() {
        $this->search();
    }

    /**
     * 显示指定的机房下的所有机柜
     * Enter description here ...
     */
    public function house() {
        $initData = $this->getInitData();
        $this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
        $this->assign('gameCode', $initData['gameCode']);
        $houid    = I('houid');
        $m        = M();
        $cupBoard = $m->table('uqee_cupboard,uqee_house')->where('uqee_house.id=houid and houid=' . $houid)->field('uqee_cupboard.id,cupname,houname,seatnum,uqee_cupboard.houid,uqee_cupboard.createtime,uqee_cupboard.remark')->select();
//      p($cupBoard);die;
        $this->assign('cupboard', $cupBoard);
        $this->display();
    }

    /**
     * 显示指定机柜里的所有服务器
     * Enter description here ...
     */
    public function cupBoard() {
        $initData = $this->getInitData();
        $this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
        $this->assign('gameCode', $initData['gameCode']);
        $where     = array('cupid' => I('cupid'));
        $count     = M('Hostlist')->where($where)->where('ishost!=2')->count();
        $page      = $this->startPage($count, 30);
        $limit     = $page->firstRow . ',' . $page->listRows;
        $hostinfo  = M('Hostlist')->where($where)->where('ishost!=2')->limit($limit)->select();
        $hostinfo  = $this->serverChange($hostinfo, $initData);
        $cup       = M('cupboard')->where(array('id' => $where['cupid']))->find();
        $this->cup = $cup;
        $this->assign('hostinfo', $hostinfo);
        $this->assign('page', $page->show());
        $this->display();

    }
    /**
     * 统计查询处理.
     * Enter description here ...
     */
    public function search() {
        $initData = $this->getInitData();
        $map      = $this->_search(D('Hostlist'));
        if (!isset($map['ishost'])) {
            $map['ishost'] = array('neq', 2);
        }

        // $map['ishost'] = array('neq',2);
        // $hostinfo = M('Hostlist')->where($map)->select();
        // $hostinfo = $this->serverChange($hostinfo,$initData);
        // p($hostinfo);die;
        // $this->assign('hostinfo',$hostinfo);
        $this->assign("map", $map);
        $this->assign('housename', $initData['idcHouse'][I('houid')]['houname']);
        $this->assign('cupname', $initData['idcPlace'][I('houid')][I('cupid')]['cupname']);
        $this->display('search');
    }

    public function getHost() {
        if (!IS_AJAX) {
            $this->error('页面不存在!');
        }
        $initData = $this->getInitData();
        $start    = I('start', 0, 'intval');
        $length   = I('length', 20, 'intval');
        $table    = I('table', 'Hostlist');
        $model    = D($table);
        $map      = $this->_search($model);
        if (!empty($_GET['search']['value'])) {
            $map['mainip'] = array('like', '%' . $_GET['search']['value'] . '%');
        }
        $ordername = I('ordername');
        $orderby   = I('orderby');
        if (!empty($ordername) && !empty($orderby)) {
            $order = "{$ordername} $orderby";
        } else {
            $order = "id asc";
        }
        // print_r($map);
        if ($start == 0 && $length == -1) {
            $ret = $model->where($map)->order($order)->select();
        } else {
            $ret = $model->where($map)->order($order)->limit($start, $length)->select();
        }
        // echo $model->_sql();
        $hostinfo = $this->serverChange($ret, $initData);
        $data     = array('aaData' => $hostinfo, 'iTotalRecords' => count($hostinfo), 'iTotalDisplayRecords' => $model->where($map)->count());
        echo json_encode($data);
    }

    /**
     * POST查询处理
     * Enter description here ...
     */
    public function postHandle() {
        if (!IS_PSOT) {
            $this->search();
        }

        $initData = $this->getInitData();
        $queryStr = trim(I('query'));
        switch (I('query_type')) {
        case 'remark':
        case 'mainip':
        case 'sertag':
        case 'hostid':$table = 'Hostlist';
            $type                = I('query_type');
            break;
        case 'log':$table = 'Log';
            $type             = 'serip';
            break;
        }
        $like         = urlencode('%');
        $where[$type] = array('like', $like . $queryStr);
        if ($this->_get('houid', '', '')) {
            $where['houid'] = $this->_get('houid');
        }
        $where['table'] = $table;
        $this->assign('map', $where);
        $this->query_type = I('query_type');
        $this->query      = $queryStr;
        $this->display('search');

    }
    /**
     * 按机房显示所属机房的所有服务器
     * Enter description here ...
     */
    public function houseList() {
        $where = $_GET;
        unset($where['_URL_']);
        $result         = M('House')->where($where)->select();
        $where['houid'] = $result[0]['id'];
        $count          = M('Hostlist')->where($where)->count();
        $page           = $this->startPage($count, 30);
        $limit          = $page->firstRow . ',' . $page->listRows;
        $hostinfo       = M('Hostlist')->where($where)->limit($limit)->select();
        $initData       = $this->getInitData();
        $hostinfo       = $this->serverChange($hostinfo, $initData);
        $this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
        $this->assign('gameCode', $initData['gameCode']);
        $this->assign('hostinfo', $hostinfo);
        $this->assign('page', $page->show());
        $this->display('search');
    }
    public function ownList() {
        $where = $_GET;
        unset($where['_URL_']);
        $count    = M('Hostlist')->where($where)->count();
        $page     = $this->startPage($count, 30);
        $limit    = $page->firstRow . ',' . $page->listRows;
        $hostinfo = M('Hostlist')->where($where)->limit($limit)->select();
        $initData = $this->getInitData();
        $hostinfo = $this->serverChange($hostinfo, $initData);
        $this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
        $this->assign('gameCode', $initData['gameCode']);
        $this->assign('hostinfo', $hostinfo);
        $this->assign('page', $page->show());
        $this->display('search');
    }

}
?>
