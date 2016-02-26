<?php

class MonitorAction extends CommonAction {
    /**
     * 显示监控视图
     *
     */
    public function index() {
        $map = $this->_search(D('Monitor'));
        $this->assign('map', $map);
        $initData = $this->getInitData();
        $this->display();
    }
    public function getMonitorList() {
        $model             = D('Monitor');
        $map               = $this->_search($model);
        list($total, $ret) = $this->getList($model, $map);
        $data              = array('aaData' => $ret, 'iTotalRecords' => count($ret), 'iTotalDisplayRecords' => $total);
        echo json_encode($data);
    }
    /**
     * 增加监控处理
     */
    public function addMonitorHandle() {
        $monitorConn = M('monitor');
        $mdata       = $monitorConn->create();
        if ($mdata === false) {
            $this->ajaxReturn('提交了非法数据', '错误', 0);
        }
        if ($monitorConn->where("name='" . $mdata['name'] . "'")->count() > 0) {
            $this->ajaxReturn('该监控已经存在', '错误', 0);
        }
        $rep = "http://";
        if (strpos($mdata['nagios'], $rep) !== 0) {
            $this->ajaxReturn('nagios地址应该以' . $rep . "开始!", '错误', 0);
        }
        if (strpos($mdata['cacti'], $rep) !== 0) {
            $this->ajaxReturn('cacti地址应该以' . $rep . "开始!", '错误', 0);
        }
        $mdata['createtime'] = time();
        if ($monitorConn->add($mdata)) {
            $this->ajaxReturn('该监控已经存在', '成功', 0);
        } else {
            $this->error("增加失败!");
        }
    }
    /**
     * 删除监控
     */
    public function deleteMonitor() {
        $monitorConn = M('monitor');
        $where       = $this->_search($monitorConn);
        if (is_array($where['id'])) {
            $where['id'] = array('in', $where['id']);
        }
        if (!$monitorConn->where($where)->count()) {
            $this->ajaxReturn('警告', '该监控不存在,不能删除,请联系管理员!', 0);
        }
        if ($monitorConn->where($where)->delete()) {
            $this->ajaxReturn('删除成功!', '通知', 1);
        } else {
            $this->ajaxReturn('删除失败!', '警告', 0);
        }
    }
    public function modityMonitorHandle() {
        $monitorConn  = M('monitor');
        $where        = $monitorConn->create();
        $name['name'] = $where['name'];
        $id['id']     = $where['id'];
        $result       = $monitorConn->where($id)->find();
        unset($result['createtime']);
        $diff = array_diff_assoc($where, $result);
        if (count($diff) == 0) {
            $this->success('修改成功');
            die;
        }
        if ($monitorConn->where($name)->count() > 0 && $result['name'] != $where['name']) {
            $this->error('该监控已经存在!');
        }
        $rep = "http://";
        if (strpos($where['nagios'], $rep) !== 0) {
            $this->error('nagios地址应该以' . $rep . "开始!");
        }
        if (strpos($where['cacti'], $rep) !== 0) {
            $this->error('cacti地址应该以' . $rep . "开始!");
        }
        if ($monitorConn->save($where)) {
            $this->success('修改成功!');
        } else {
            $this->error('修改失败!');
        }
    }
    public function cactilogin() {
        $id      = (int) I('id');
        $monitor = D($this->getActionName())->where(array('id' => $id))->find();
        $url     = $monitor['cacti'];
        if (substr($url, -10) != "/index.php") {
            if (substr($url, -1) == '/') {
                $url .= "index.php";
            } else {
                $url .= '/index.php';
            }

        }
        $user   = 'pYQwd';
        $passwd = '8u*$y1.';
        echo "<form method='POST' action='$url' id='login'>";
        echo "<input type='hidden' name='action' value='login'/>";
        echo "<input type='hidden' name='login_username' value='$user' />";
        echo "<input type='hidden' name='login_password' value='$passwd' />";
        echo "</form>";
        echo "<script>document.getElementById('login').submit();</script>";
    }
    public function nagioslogin() {
        $id      = (int) I('id');
        $monitor = D($this->getActionName())->where(array('id' => $id))->find();
        $url     = $monitor['nagios'];
        $user    = 'pYQwd';
        $passwd  = '8u*$y1.';
        if (substr($url, -10) != "/index.php") {
            if (substr($url, -1) == '/') {
                $url .= "index.php";
            } else {
                $url .= '/index.php';
            }

        }
        header('Location:' . $url);
        // echo $url;die;
        // $this->nagios = $url;
        // $this->display();
    }
}
?>
