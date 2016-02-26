<?php
/*
 * Created on 2013-9-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class PublicAction extends CommonAction {
    public function idcHouse() {
        $data = M('House')->field('id,houname')->select();
        $this->assign('house', $data);
        $this->display();
    }
    public function changeNotify() {
        $model = M('Notify');
        $state = $model->find();
        if ($state['state'] == I('state')) {
            $state['state'] = !I('state');
            if ($model->save($state)) {
                $this->success('修改报警状态成功!');
            } else {
                $this->error('修改报警状态失败!');
            }
        }
    }
    /**
     * 删除机柜
     */
    public function deletecup() {
        $handle = D("Cupboard");
        $where  = $this->_search($handle);
        $list   = $handle->relation(true)->where($where)->find();
        if (count($list['hostlist']) > 0) {
            $this->ajaxReturn('该机柜下还有在架服务器,不能删除!', '错误', 0);
        } else {
            unset($list['hostlist']);
            if ($handle->relation('seatinfo')->where($where)->delete()) {
                $this->ajaxReturn('删除机柜成功!', '成功', 1);
            } else {
                $this->ajaxReturn('删除机柜失败!', '失败', 0);
            }
        }
    }

    /**
     * 查看机房信息
     * @return [type] [description]
     */
    public function getHouseInfo() {
        $model       = M('House');
        $where['id'] = I('id', 0, 'intval');
        if ($where['id'] == 0) {
            $this->ajaxReturn('警告', '参数错误', 0);
        }
        if ($houseInfo = $model->where($where)->find()) {
            $this->ajaxReturn($houseInfo, '', 1);
        } else {
            $this->ajaxReturn('警告', '未找到该机房', 0);
        }

    }
    /**
     * 修改机房数据处理
     */
    public function amendHouseHandle() {
        $houseHandle         = M('house');
        $_POST['changetime'] = time();
        $mdata               = $houseHandle->create();
        if ($mdata == false) {
            $this->error('修改机房信息失败,提交数据有误!');
        }
        if ($houseHandle->save()) {
            $this->success('修改机房信息成功!');
        } else {
            $this->error('修改机房信息失败!');
        }
    }
    /**
     *删除机房
     */
    public function deleteHouse() {
        $handle = D("House");
        $list   = $handle->relation(true)->find(I('id'));
        if (count($list['cupinfo']) > 0) {
            $this->ajaxReturn('失败', '该机房下还有机柜,不能删除该机房!', 0);
        } else {
            if ($handle->where('id=' . I('id'))->delete()) {
                $this->ajaxReturn('成功', '删除机房成功!', 1);
            } else {
                $this->ajaxReturn('失败', '删除机房失败!', 0);
            }
        }

    }

    /**
     * 查看机房视图
     */
    public function showHouse() {
        $this->placeList = C('place');
        $initData        = $this->getInitData();
        $this->houseList = $initData['idcHouse'];
        $this->display();
    }
    /**
     * 修改机柜信息视图
     *
     */
    public function amendCup() {
        $initData = $this->getInitData();
        $model    = D('Cupboard');
        $map      = $this->_search($model);
        $ret      = $model->relation('house')->where($map)->select();
        $this->assign('data', $ret);
        $this->display();
    }
    public function getCupInfo() {
        $model = D('Cupboard');
        $map   = $this->_search($model);
        $pk    = $model->getPk();
        $ret   = $model->relation('house')->find($map[$pk]);
        if (empty($ret)) {
            $this->ajaxReturn('数据不存在', '错误', 0);
        } else {
            $this->ajaxReturn($ret, '', 1);
        }
    }
    public function amendCupHandle() {
        $model = D('cupboard');
        $data  = $model->create();
        if (empty($data)) {
            $this->ajaxReturn('数据不完整', '修改失败', 0);
        }
        $data['changetime'] = time();
        if ($model->save($data)) {
            $this->ajaxReturn('修改成功!', '成功', 1);
        } else {
            $this->ajaxReturn('修改失败!', '失败', 0);
        }
    }
    public function showlog() {
        $initData = $this->getInitData();
        $this->display();
    }
    public function getLogList() {
        $model             = D("Log");
        $map               = $this->_search($model);
        list($total, $ret) = $this->getList($model, $map);
        $data              = array('aaData' => $ret, 'iTotalRecords' => count($ret), 'iTotalDisplayRecords' => $total);
        echo json_encode($data);
    }
    public function showip() {
        $map      = $this->_search(D('Iplist'));
        $keyworld = I('keyworld', '', 'mysql_real_escape_string');
        if (!empty($keyworld)) {
            $map['mainip'] = array('like', $keyworld . '%');
            $this->assign('keyworld', $keyworld);
        }
        $this->assign('map', $map);
        $initData = $this->getInitData();
        $this->display();

    }
    public function getIpList() {
        $model = D('Iplist');
        $map   = $this->_search($model);
        if (!empty($_GET['search']['value'])) {
            $map['mainip'] = array('like', '%' . $_GET['search']['value'] . '%');
        }
        list($total, $ret) = $this->getList($model, $map);
        $house             = D('House')->getData();
        foreach ($ret as $k => $v) {
            $v['houname'] = $house[$v['houid']]['houname'];
            $v['mainipurl'] = U('Hostinfo/hostinfo',array('mainip'=>$v['mainip']));
            $ret[$k]      = $v;
        }
        $data = array('aaData' => $ret, 'iTotalRecords' => count($ret), 'iTotalDisplayRecords' => $total);
        echo json_encode($data);
    }
    public function deleteip() {
        $model = D('Iplist');
        $where = $this->_search($model);
        if (is_array($where['id'])) {
            $where['id'] = array('in', $where['id']);
        }
        $where['state'] = 0;
        $stateRes       = $model->where($where)->field('state')->find();
        $state          = $stateRes['state'];
        if ($state == 1) {
            $this->ajaxReturn("该IP已被服务器使用,不能删除!", '删除失败', 0);
        } else {
            if ($model->where($where)->delete()) {
                $this->ajaxReturn("IP删除成功!", '删除成功', 1);
            } else {
                $this->ajaxReturn($model->getError(), '删除失败', 0);
            }
        }
    }
    public function modifyip() {
        if (!IS_AJAX) {
            halt('该网页不存在!');
        }

        $model = D('Iplist');
        $mdata = $model->create();

        $mainip   = $model->where('id=' . I('id', 0, 'intval'))->find();
        $hostinfo = M('hostlist')->where(array('mainip' => $mainip['mainip']))->find();
        if ($hostinfo) {
            $str = '该IP被服务器<span class="red">' . $hostinfo['hostid'] . "</span>使用!";
            $this->ajaxReturn('', $str, 0);
        } else {
            if ($model->where($mdata)->count() == 1) {
                $this->ajaxReturn('', "修改成功!", 1);
            }
            if ($model->save($mdata)) {
                $this->ajaxReturn('', "修改成功!", 1);
            } else {
                $this->ajaxReturn($model->_sql(), '修改失败', 0);
            }
        }
    }
    public function getUrl() {
        if (!IS_AJAX) {
            halt();
        }

        $data = $_POST;
        $url  = U(APP_NAME . '/Public/showip', $data);
        $this->ajaxReturn('', $url, 1);
    }
    /**
     * CDN
     *
     */
    public function cdn() {
        $this->display();
    }
    public function sendContent() {
        if (!IS_POST) {
            halt('该网页不存在!');
        }
        $method  = I('ops', 'ws', 'mysql_real_escape_string');
        $ctype   = I('ctype', 'url', 'mysql_real_escape_string');
        $content = I('content', '');
        $method  = $method == 'lx' ? 'lx' : 'ws';
        $result  = $this->$method($content, $ctype);
        $this->ajaxReturn('', $result, 1);
    }
    private function lx($content, $ctype = null) {
        $host   = "http://push.dnion.com/cdnUrlPush.do?";
        $params = array(
            'captcha' => '436bd460',
            'type'    => $ctype == 'dir' ? 0 : 1,
            'decode'  => 'n',
        );
        $urls          = explode("\n", $content);
        $urls          = join(",", $urls);
        $params['url'] = $urls;
        $query         = http_build_query($params);
        // echo $host . $query;
        $ch = curl_init($host . $query);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $ret  = curl_exec($ch);
        $info = curl_getinfo($ch);
        return $info['http_code'] != 200 ? '推送失败' : '推送成功';

    }
    private function ws($content, $ctype = null) {
        $host    = "http://wscp.lxdns.com:8080/wsCP/servlet/contReceiver?";
        $content = explode("\n", $content);
        $url     = '';
        foreach ($content as $k => $v) {
            $url .= $v;
            if (isset($content[$k])) {
                $url .= ';';
            }
        }
        $data = array('username' => 'snsfun', 'passwd' => 'soidc..123');
        if ($ctype == 'url') {
            $data['url'] = $url;
        } else if ($ctype == 'dir') {
            $data['dir'] = $url;
        }
        $str            = $data['username'] . $data['passwd'] . $url;
        $data['passwd'] = md5($str);
        $data           = http_build_query($data);
        $url            = $host . $data;
        $ch             = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $ret  = curl_exec($ch);
        $info = curl_getinfo($ch);
        // print_r($ret);
        return strpos($ret, 'success') === false ? '推送失败' : '推送成功';
    }

    public function sshlog() {
        $model = D('SshloginLog');
        $this->getInitData();
        $map  = $this->_search($model);
        $time = I('time', '', 'mysql_real_escape_string');
        if (!empty($time)) {
            $map['login_time'] = array('like', $time . '%');
        }
        $this->assign('time', $time);
        $this->assign('map', $map);
        $this->display();
    }
    public function delsshlog() {
        $model = D('SshloginLog');
        $map   = $this->_search($model);
        $pk    = $model->getPk();
        if (!array_key_exists($pk, $map)) {
            $this->ajaxReturn('错误参数', '失败', 0);
        }
        if (is_array($map[$pk])) {
            $map[$pk] = array('in', $map[$pk]);
        }
        if ($model->where($map)->delete()) {
            $this->ajaxReturn('删除成功', '成功', 1);
        } else {
            $this->ajaxReturn('删除失败', '失败', 0);
        }
    }
    public function getSshlogList() {
        $model = D('SshloginLog');
        $map   = $this->_search($model);
        // print_r($map);
        list($total, $ret) = $this->getList($model, $map);
        $data              = array('aaData' => $ret, 'iTotalRecords' => count($ret), 'iTotalDisplayRecords' => $total);
        echo json_encode($data);
    }
    private function parseLog($arr) {
        foreach ($arr as $k => $v) {
            $v['content'] = explode(' ', $v['content']);
            // p($v);die;
            $v['content'][10] = substr($v['content'][10] . ' ' . $v['content'][11], 0, -1);
            $arr[$k]          = $v;
        }
        return $arr;
    }
    public function showGame() {
        $model = D('Game');
        $map   = $this->_search($model);
        $pk    = $model->getPk();
        if (!array_key_exists($pk, $map)) {
            $this->ajaxReturn('数据错误', '错误', 0);
        }
        $ret = $model->where($map)->find();
        if ($ret) {
            $this->ajaxReturn($ret, '', 1);
        } else {
            $this->ajaxReturn('游戏不存在', '错误', 0);
        }
    }
    public function amendGame() {
        $model = D('Game');
        $mdata = $model->create();
        if ($mdata === false) {
            $this->ajaxReturn('提交数据有误', '失败', 0);
        }
        $pk = $model->getPk();
        if (!array_key_exists($pk, $mdata)) {
            $this->ajaxReturn('修改游戏失败', '错误', 0);
        }
        if ($model->save()) {
            $this->ajaxReturn('修改游戏成功', '成功', 1);
        } else {
            $this->ajaxReturn('修改游戏失败', '失败', 0);
        }
    }
    public function deleteGame() {
        $model = D('Game');
        $map   = $this->_search($model);
        $pk    = $model->getPk();
        if (is_array($map[$pk])) {
            $map[$pk] = array('in', $map[$pk]);
        }
        if ($model->where($map)->delete()) {
            $this->ajaxReturn('删除游戏成功', '成功', 1);
        } else {
            $this->ajaxReturn('删除游戏失败', '失败', 0);
        }
    }
}
?>
