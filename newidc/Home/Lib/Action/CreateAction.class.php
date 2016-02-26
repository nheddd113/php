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

    public function hostTempLate() {

        $this->display();
    }

    public function addTempLateHandle() {
        if (!IS_POST) {
            $this->error('该页面不存在!');
        }

        $where = $_POST;
        if (!is_numeric($where['mem'])) {
            $this->error('内存大小只能为数字!');
        }

        if (!is_numeric($where['disk'])) {
            $this->error('硬盘大小只能为数字!');
        }

        if (!is_numeric($where['price'])) {
            $this->error('价格只能为数字!');
        }

        $tempLate = M('hosttemplate');
        if ($tempLate->where(array('name' => $where['name']))->count()) {
            $this->error('该模板已经存在,不能重复增加!');
        }
        if ($tempLate->add($where, '', true)) {
            $this->success('模板:' . $where['name'] . ' 增加成功!');
        }
    }

    /**
     * 增加IP视图
     */
    public function addIp() {
        $initData    = $this->getInitData();
        $this->house = $initData['idcHouse'];
        $this->display();
    }

    /**
     * 增加IP处理
     */

    public function addIpHandle() {
        $data  = $_POST;
        $mdata = M('Iplist')->create();
        if (empty($data['duline'])) {
            unset($mdata['subip']);
            unset($mdata['submask']);
            unset($mdata['subgw']);
        }
        if ($data['where'] == 1) {
            //批量增加IP时处理方法.
            $adddata = array();
            $ipArr   = explode('.', $data['mainip']);
            $range   = $ipArr[3];
            $deff    = explode('.', $data['range']);
            $manyIp  = $deff[count($deff) - 1];
            if ($manyIp > 254) {
                $this->error('结束IP有误');
            }
            $count = $manyIp - $range;
            if ($data['duline'] == 1) {
                $subIpArr  = explode('.', $data['subip']);
                $subipsuff = $subIpArr[3];
            }
            $state = false;
            for ($i = 0; $i <= $count; $i++) {
                $ipArr[3] = $range + $i;
                $ip       = join('.', $ipArr);
                if ($data['duline'] == 1) {
                    $subIpArr[3] = $subipsuff + $i;
                    $subip       = join('.', $subIpArr);
                }
                if (M('Iplist')->where(array('mainip' => $ip))->count() != 0) {
                    $state = true; //ip存在
                    break;
                } else {
                    $mdata['subip']  = isset($subip) ? $subip : '';
                    $mdata['mainip'] = $ip;
                    $adddata[]       = $mdata;
                }
            }
            if ($state) {
                $this->error('增加IP失败!');
                return;
            } else {
                if (M('Iplist')->addAll($adddata)) {
                    $this->success('增加IP成功!');
                } else {
                    $this->error('增加IP失败!');
                }

            }
            return;
        }
        //先检查Ip是不是已经存在.
        if (M('Iplist')->where(array('mainip' => $mdata['mainip']))->count() != 0) {
            $this->error('增加的IP已经存在!');
        }

        //增加的IP写入数据库
        if (M('Iplist')->add($mdata)) {
            $this->success('增加IP成功!');
        } else {
            $this->error('增加IP失败!');
        }

    }

    /**
     * 增加虚拟机
     *
     */
    public function addVirHost() {
        $model    = M('Hostlist');
        $hostid   = I('id');
        $hostinfo = $model->where(array(
            'id' => $hostid,
        ))->find();
        if ($virHostList = $model->where(array('parentid' => $hostid))->field('hostid')->select()) {
            $i = 101;
            foreach ($virHostList as $value) {
                $tmpHostid = explode('-', $value['hostid']);
                while ($i <= $tmpHostid[count($tmpHostid) - 1]) {
                    $i++;
                }
            }
            $hostinfo['hostid'] .= '-' . $i;
        } else {
            $hostinfo['hostid'] .= '-101';
        }
        $this->assign('max_hostid', $hostinfo['hostid']);
        return;
        $houseinfo = M('House')->where(array(
            'id' => $hostinfo['houid'],
        ))->find();
        $hostinfo['houname'] = $houseinfo['houname'];
        $initData            = $this->getInitData();
        $cupidArr            = M('Cupboard')->where(array(
            'id' => $hostinfo['cupid'],
        ))->find();
        $hostinfo['cupname'] = $cupidArr['cupname'];
        $this->assign('hostinfo', $hostinfo);
        $this->assign('gameCode', $initData['gameCode']);
        // $this->display();
    }

    /**
     * 增加虚拟时处理表单
     */
    public function addVirHandle() {
        if (!IS_POST) {
            $this->error("访问错误", U('Search/search'));
        }

        $where = $_POST;
        // 如果数量为空或0时 默认数据为1
        if ((int) $where['number'] == 0) {
            $where['number'] == 1;
        }

        import('@.Common.idclog');
        $startLog        = new idclog;
        $where['ishost'] = 2;
        $hostadd         = M('hostlist');
        $url             = U(APP_NAME . '/Create/addVirHost', array('id' => I('parentid')));
        $managerHost     = $hostadd->where('id=' . I('parentid'))->find();
        if ($managerHost['ishost'] != 1) {
            $this->error('此服务器不是宿主机. 不能增加虚拟机!');
        }
        $virCount = $hostadd->where('parentid=' . I('parentid'))->count();
        $virCount += $where['number'];
        if ((int) $where['number'] >= 1) {
            if ($where['number'] && $virCount <= 10) {
                $hostid = $where['hostid'];
                $idArr  = explode('-', $hostid);
                for ($i = 0; $i < $where['number']; $i++) {
                    // p($idArr);
                    $where['hostid'] = join('-', $idArr);
                    $where['gameid'] = $managerHost['gameid'];
                    $where['owner']  = $managerHost['owner'];
                    $allWhere[]      = $where;
                    $idArr[3]++;
                }
            } else {
                $this->error('该宿主机上虚拟机已达到上限!');
            }
            // p($allWhere);die;
            if ($hostadd->addAll($allWhere)) {
                $hostidList = '';
                foreach ($allWhere as $value) {
                    $hostidList .= $value['hostid'] . '、';
                }
                $hostidList = substr($hostidList, 0, -1);
                $startLog->hostCreateAndDelete($where['parentid'], $hostidList, true, false);
                $this->success('增加成功!');
            } else {
                $this->success('部份虚拟机增加成功!');
            }
        } else {
            $this->error('参数有误!');
        }
    }

    /**
     * 更新运营商
     * Enter description here ...
     */
    public function addOps() {
        $url = 'http://gamemanager.uqee.com/game/idc/operator';
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $ret = curl_exec($ch);
        if (strlen($ret)) {
            $opsArr     = array();
            $ret        = substr($ret, 1, -1);
            $retArr     = explode(',', str_ireplace('"', '', $ret));
            $i          = 0;
            $errorArray = array();
            foreach ($retArr as $value) {
                $tmpArr         = explode(':', $value);
                $opsArr['seq']  = $tmpArr[0];
                $opsArr['name'] = $tmpArr[1];
                $i++;

                if (M('Ops')->where(array(
                    'seq' => $opsArr['seq'],
                ))->count() > 0) {
                    $result = M('Ops')->where(array(
                        'seq' => $opsArr['seq'],
                    ))->find();
                    if ($result['name'] == $opsArr['name']) {
                        continue;
                    }

                    if (!M('Ops')->where(array(
                        'seq' => $opsArr['seq'],
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
                $this->error('更新运营商失败!', U('Search/search'));
            } else {
                $this->success('更新运营成功!', U('Search/search'));
            }
        } else {
            $this->error('更新运营商失败!', U('Search/search'));
        }
    }

    public function addOpsHandle() {

    }

    public function addGameHandle() {
        if (!IS_POST) {
            $this->error("访问错误", U('Index/index'));
        }
        $model = D('Game');
        $mdata = $model->create();
        if ($mdata === false) {
            $this->ajaxReturn($model->getError(), '错误', 0);
        }
        $map = array('name' => $mdata['name']);
        if ($model->where($map)->count()) {
            $this->ajaxReturn($map['name'] . ' 已经存在!', '错误', 0);
        }
        if ($model->add()) {
            $this->ajaxReturn('增加游戏成功', '成功', 1);
        } else {
            $this->ajaxReturn('增加游戏失败', '失败', 0);
        }
    }

    /**
     * 增加主机
     * Enter description here ...
     */
    public function addHost() {
        $initData = $this->getInitData();
        // p($initData);die;
        $template = M('hosttemplate')->select();
        // p($template)
        $this->template = $template;
        $this->assign('house', $initData['idcHouse'])->assign('cupborad', $initData['idcPlace'])->assign('count', $initData['count']);
        $this->display();

    }

    public function _before_addHostHandle() {
        $_POST['ishost']    = (int) (bool) $_POST['ishost'];
        $_POST['ismanager'] = (int) (bool) $_POST['ismanager'];
        $_POST['mem']       = str_replace('g', '', str_replace('b', '', strtolower($_POST['mem'])));
        $_POST['disk']      = str_replace('g', '', str_replace('b', '', strtolower($_POST['disk'])));
    }

    /**
     * 处理增加主机
     */
    public function addHostHandle() {
        if (!IS_POST) {
            $this->error("访问错误", U('Index/index'));
        }
        import('@.Common.idclog');
        $startLog = new idclog;
        $hostid   = I('hostid');
        $hostlist = D('Hostlist');
        $mdata    = $hostlist->create();
        if ($hostlist->where(array(
            'hostid' => $hostid,
        ))->count() > 0) {
            $this->ajaxReturn('此主机已存在,请检查后重增加!', '警告', 0);
            // $this->error('此主机已存在,请检查后重增加!', U(APP_NAME . '/Create/addHost'));
        }
        if ($insertId = $hostlist->add()) {
            $startLog->hostCreateAndDelete($insertId, I('hostid'), true, true);
            $this->ajaxReturn("增加成功", '成功', 1);
        } else {
            $this->ajaxReturn('增加失败', '失败', 0);
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
        $model = D('House');
        if (!IS_POST) {
            $this->error("访问错误", U('Index/index'));
        }

        $houname = I('houname');
        if (!is_numeric(I('bandwidth'))) {
            $this->error('机房带宽填写有误,请重新填写!');
        }

        if (!is_numeric(I('price'))) {
            $this->error('带宽价格填写有误,请重新填写!');
        }

        if ($model->where(array(
            'houname' => $houname,
        ))->count() > 0) {
            $this->error('此机房已存在,请检查后重增加!');
        }
        if ($model->add($_POST)) {
            $this->success("增加成功");
        } else {
            $this->error($model->getError());
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
        if (!IS_POST) {
            $this->error("访问错误", U('Index/index'));
        }

        $model = D('Cupboard');
        $mdata = $model->create();
        if ($mdata === false) {
            $this->ajaxReturn($model->getError(), '错误', 0);
        }
        $map          = array('cupname' => $mdata['cupname']);
        $map['houid'] = $mdata['houid'];
        if ($model->where($map)->count()) {
            $this->ajaxReturn('此机房在该机房已经存在,请确认后得新添加!', '失败', 0);
        }
        if ($model->add()) {
            $cupid = $model->getLastInsID();
            for ($i = 1; $i <= $mdata['seatnum']; $i++) {
                $tmpArr = array(
                    'cupid'  => $cupid,
                    'seatid' => $i,
                );
                M('Seat')->add($tmpArr);
            }
            $this->ajaxReturn('增加机柜成功', '成功', 1);
        } else {
            $this->ajaxReturn('增加机柜失败', '失败', 0);
        }
    }
}
?>
