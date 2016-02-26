<?php
/*
 * Created on 2013-9-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class AjaxAction extends CommonAction {

    /**
     * 查询该服务器下的虚拟有虚拟机
     */
    public function getVirHost() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $initData = $this->getInitData();
        $id       = I('id', 0, 'intval');
        if ($virHost = M('Hostlist')->where(array('parentid' => $id))->select()) {
            $virHost = $this->serverChange($virHost, $initData);
            $this->ajaxReturn($virHost, '', 1);
        } else {
            $this->ajaxReturn('通知', '该服务器不是宿主机,或没有虚拟机可以查看.', 0);
        }
    }

    public function getHostname() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        import("@.Common.Salt");
        $salt = new Salt();
        $ret  = $salt->serverOp($_POST['uuid'], $_POST['houid'], 'cmd.run', $_POST['cmd']);
        if ($ret['success'] > 0) {
            $return = $ret['minions'][0]['return'];
            $this->ajaxReturn($return, '通知', 1);
        } else {
            $this->ajaxReturn('获取主机名失败', '通知', 0);
        }
    }

    public function getGamename() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        import("@.Common.Salt");
        $salt = new Salt();
        $ret  = $salt->serverOp($_POST['uuid'], $_POST['houid'], 'grains.get', $_POST['cmd']);
        if ($ret['success'] > 0) {
            $return = $ret['minions'][0]['return'];
            if (empty($return)) {
                $return = '无';
            }

            $this->ajaxReturn($return, '通知', 1);
        } else {
            $this->ajaxReturn('获取主机名失败', '通知', 0);
        }
    }
    /**
     * 获取salt执行记录
     * @return [type] [description]
     */
    public function getSaltLog() {
        $id    = I('id');
        $model = M('Hostlist');
        $host  = $model->where(array('id' => $id))->find();
        if ($host) {
            import("@.Common.Salt");
            $salt = new Salt();
            $ret  = $salt->getMission($host['uuid'], $host['houid']);
            // p($ret);
            if ($ret['status'] == 3) {
                $this->ajaxReturn('请求服务器失败.', '通知', 0);
            } elseif ($ret['status'] == 2) {
                $this->ajaxReturn('请求服务器返回空.', '通知', 0);
            } else {
                $this->ajaxReturn($ret['data'], 'SALT记录任务最后10次', $host['houid']);
            }
        } else {
            $this->ajaxReturn('该页面不存在', '警告', 0);
        }
    }
    public function getJidInfo() {
        $jid   = I('jid');
        $houid = I('houid');
        $tgt   = I('tgt');
        if (empty($jid) || empty($houid)) {
            $this->ajaxReturn('请求参数错误', '错误', 0);
        }
        import("@.Common.Salt");
        $salt   = new Salt();
        $ret    = $salt->getReturn($jid, $houid, $tgt);
        $result = array('not_return' => array(), 'jid'                 => $jid,
            'total'                      => count($ret['data']), 'minions' => array(),
            'success'                    => 0, 'houid'                     => $houid);
        // p($ret);
        foreach ($ret['data'] as $k => $v) {
            if ($v['result']) {
                $result['success']++;
            }
            if (is_array($v['returns']['return'])) {
                $v['returns']['return'] = json_encode($v['returns']['return']);
            }
            array_push($result['minions'], $v['returns']);
        }
        $this->result = $result;
        // p($result);die;
        $this->display('Salt:execute_function');

    }
    public function getSinfo() {
        if (!IS_AJAX) {
            $this->error("网页不存在");
        }

        $id      = I('id');
        $handler = M('Hostlist');
        $host    = $handler->where('id=' . $id)->find();
        $ret     = $this->getServerInfo($host['innip'], $host['houid']);
        if ($ret == false) {
            $this->ajaxReturn('获取服务器信息失败!', '', 0);
        }
        $text = "<table>
            <tr>
                <td>test</td>
            </tr>
        </table>";
        $this->ajaxReturn($text, '', 1);

    }

    /**
     * 增加或删除机位
     */
    public function addOrdelSeat() {
        if (!IS_AJAX) {
            $this->error('网页不存在');
        }

        $id = I('id');
        if (!$id) {
            $this->ajaxReturn('', '缺少参数!', 0);
        }

        $cupHandle = M('cupboard');
        $cupInfo   = $cupHandle->where('id=' . $id)->find();
        if (I('type') == 1) {
            $result         = array('seatnum' => $cupInfo['seatnum'] + 1);
            $data['cupid']  = $id;
            $data['seatid'] = $result['seatnum'];
            $res            = M('seat')->add($data);
        } else {
            if ($cupInfo['seatnum'] == 0) {
                $this->ajaxReturn('', '该机房没有机位可以减少!', 0);
            }
            $status = M('seat')->where(array('cupid' => $cupInfo['id'], 'seatid' => $cupInfo['seatnum']))->find();
            if ($status['state'] == 1) {
                $this->ajaxReturn('', '最大机位已使用,不能删除!', 0);
            } else {
                $result         = array('seatnum' => $cupInfo['seatnum'] - 1);
                $data['cupid']  = $id;
                $data['seatid'] = $cupInfo['seatnum'];
                $seatHandle     = M('seat');
                $res            = $seatHandle->where($data)->delete();
            }
        }
        if ($res) {
            $res = $cupHandle->where('id=' . $id)->save($result);
        } else {
            $this->ajaxReturn('', '修改机位失败!', 0);
        }
        if ($res) {
            $this->ajaxReturn('', '', 1);
        } else {
            $this->ajaxReturn('', '修改机位失败!', 0);
        }
    }
    /**
     * 同步salt自定义模块
     * @return [type] [description]
     */
    public function syncAll() {
        if (!IS_AJAX) {
            $this->ajaxReturn('该页面不存在', '', 0);
        }

        $hostAction = D('hostlist');
        $map['id']  = I('id');
        if ($host = $hostAction->where($map)->find()) {
            import("@.Common.Salt");
            $salt = new Salt();
            $ret  = $salt->serverOp($host['uuid'], $host['houid'], 'saltutil.sync_all', null, $host['mainip']);
            // p($ret);
            if (is_array($ret)) {
                $this->ajaxReturn('同步数据成功', '通知', 1);
            } else {
                $this->ajaxReturn('同步数据失败', '警告', 0);
            }

        } else {
            $this->ajaxReturn('该页面不存在', '警告', 0);
        }
    }
    /**
     * 更新服务器的salt uuid
     * @return [type] [description]
     */
    public function updateuuid() {
        if (!IS_AJAX) {
            $this->ajaxReturn('该页面不存在', '', 0);
        }

        $hostAction = M('hostlist');
        $host       = $hostAction->where(array('id' => I('id')))->find();
        import("@.Common.Salt");
        $force = I('force', 0, 'intval');
        $salt  = new Salt();
        $uuid  = $salt->getUuid($host['mainip']);
        // p($uuid);
        if (!empty($uuid['uuid']) && $force == 0) {
            $this->ajaxReturn($uuid['uuid'], '', 1);
        } else {
            $uuid = $salt->getUuid($host['innip'], $force);
            // p($uuid);
            if (!empty($uuid['uuid'])) {
                $this->ajaxReturn($uuid['uuid'], '', 1);
            }
            $this->ajaxReturn('', '', 0);
        }
    }

    /**
     * 获取统计数据
     */
    public function getAmountCount() {
        if (!IS_AJAX) {
            $this->error('网页不存在');
        }

        $initData      = $this->getInitData();
        $hostAmount    = M('hostamount');
        $where['time'] = array(array('egt', I('start')), array('elt', I('end')));
        $returndata    = array();
        $type          = I('type');
        $start         = I("start");
        $costType      = I('costtype');
        $end           = I('end');
        if ($type == 1) {
            $result = $hostAmount->where($where)->select();
            foreach ($result as $value) {
                $value = json_decode($value['data'], true);
                foreach ($value['amount'] as $gameid => $v) {
                    if ($gameid == 0) {
                        if ($costType == 1) {
                            $returndata['data']['闲置']['rmb'] += array_sum($v);
                        } else {
                            $returndata['data']['闲置']['rmb'] += $v['bandwidth'] + $v['cupboard'];
                        }

                        continue;
                    }
                    if ($costType == 1) {
                        $returndata['data'][$initData['game'][$gameid]]['rmb'] += array_sum($v);
                    } else if ($costType == 2) {
                        $returndata['data'][$initData['game'][$gameid]]['rmb'] += $v['bandwidth'] + $v['cupboard'];
                    } else {
                        $returndata['data'][$initData['game'][$gameid]]['rmb'] += $v['hostPrice'];
                    }
                    $returndata['data'][$initData['game'][$gameid]]['带宽费用'] += $v['bandwidth'];
                    $returndata['data'][$initData['game'][$gameid]]['服务器折旧费'] += $v['hostPrice'];
                    $returndata['data'][$initData['game'][$gameid]]['IDC托管费'] += $v['cupboard'];
                }
            }

        } elseif ($type == 2) {
            //按天循环计算当天的费用. 如果当天没有该项目.则此项目值为0
            $time       = $this->getDateRang($start, $end);
            $gameArr    = $initData['game'];
            $gameArr[0] = '闲置';
            foreach ($time as $t) {
                $result = $hostAmount->where(array('time' => $t))->find();
                if (!$result) {
                    continue;
                }

                $returndata['time'][] = $result['time'];
                $result               = json_decode($result['data'], true);
                foreach ($gameArr as $gameid => $name) {
                    $v                                   = $result['amount'][$gameid];
                    $returndata['data'][$gameid]['name'] = $name;
                    if ($costType == 1) {
                        $returndata['data'][$gameid]['data'][] = is_array($v) ? array_sum($v) : 0;
                    } else if ($costType == 2) {
                        $returndata['data'][$gameid]['data'][] = is_array($v) ? $v['bandwidth'] + $v['cupboard'] : 0;
                    } else {
                        $returndata['data'][$gameid]['data'][] = is_array($v) ? $v['hostPrice'] : 0;
                    }
                }
            }
        }
        if ($start == $end) {
            $info = $end;
        } else {
            $info = $start . " 至 " . $end;
        }
        if ($returndata) {
            $this->ajaxReturn($returndata, $info, 1);
        } else {
            $this->ajaxReturn($returndata, $info, 0);
        }

    }
    /**
     * 增加虚拟机时选择模板
     */
    public function getTempInfo() {
        if (!IS_AJAX) {
            $this->error('网页不存在');
        }

        $model  = M('hosttemplate');
        $where  = $this->_search($model);
        $result = $model->where($where)->find();
        if ($result) {
            $this->ajaxReturn($result, '', 1);
        } else {
            $this->ajaxReturn('', '', 0);
        }
    }

    /**
     * 搜索Ip
     *
     */
    public function index() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $where           = $_POST;
        $where['mainip'] = array('like', i('mainip') . '%');
        $result          = M('Iplist')->where($where)->field('mainip')->limit(80)->select();
        $jsonstr         = json_encode($result);
        echo $jsonstr;
    }

    /**
     * 返回第二Ip
     */
    public function subIndex() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $where  = $_POST;
        $result = M('Iplist')->where($where)->field('subip')->find();
        echo $result['subip'];
    }
    /**
     *
     * 返回机位号
     */

    public function seatid() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }
        // seatid
        $model = D('Seat');
        $data  = $this->_search($model);
        if (empty($data)) {
            $this->ajaxReturn('', '', 0);
        }
        $host = D('Hostlist')->find($data['id']);
        $map  = array(
            'state' => 0,
            'cupid' => $data['cupid'],
        );
        // print_r($host);
        $result = $model->where($map)->field('seatid')->select();
        if ($host['cupid'] == $data['cupid']) {
            array_unshift($result, array('seatid' => $host['seatid']));
        }
        $ret = array('hostinfo' => $host, 'seatinfo' => $result);
        $this->ajaxReturn($ret, '', 1);
    }

    /**
     *
     * 返回该机位的状态
     */
    public function seatState() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $where = $_POST;
        $id    = I('id');
        unset($where['id']);
        $result   = M('Seat')->where($where)->field('state')->find();
        $hostStat = M('Hostlist')->where(array('id' => $id))->field('cupid,seatid')->find();
//      p($hostStat);p($where);
        //      return;
        if ($where == $hostStat || $result['state'] == 0) {
            echo 0;
        } else {
            echo 1;
        }
    }

    /**
     * 返回IP状态
     */
    public function checkState() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $where = $_POST;
        $id    = I('id');
        unset($where['id']);
        $result = M('Iplist')->field('state')->where($where)->find();
        if (count($result) > 0) {
            echo $result['state'];
        } else {
            echo 3;
        }
    }

    /**
     * 返回服务器ID
     *
     */
    public function getHostInfo() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $model = D('Hostlist');
        $map   = $this->_search($model);
        if ($result = $model->where($map)->find()) {
            $info = '该Ip已被服务器: ' . $result['hostid'] . "使用,根据你的需要选择以下操作!";
            $this->ajaxReturn($result, $info, 1);
        } else {
            $this->ajaxReturn('', '', 0);
        }
    }

    /**
     * 交换服务器信息.除物理信息以外
     *
     */
    public function handleHost() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $where = $_POST;
        if ($where['method'] == 'switch') {
            $tmpArray = array('mainip', 'subip', 'innip', 'status', 'hostype', 'owner', 'gameid', 'pretime', 'starttime', 'remark');
            $result   = $this->switchHost($where, $tmpArray);
        } elseif ($where['method'] == 'chgip') {
            $tmpArray = array('mainip', 'subip', 'innip');
            $result   = $this->switchHost($where, $tmpArray, false);
        } elseif ($where['method'] == 'chgdown') {
            $result = $this->hostShutdown($where);
        }

        if ($result == 0) {
            $url = U("Home/Hostinfo/hostinfo", array('id' => $where['currid']));
            $this->ajaxReturn($url, '修改服务器成功', 1);
        } else {
            $this->ajaxReturn($url, '修改服务器失败', 0);
        }
    }
    public function getVirhostCount() {
        if (!IS_AJAX) {
            $this->error('网页不存在', U("Home/Index/index"));
        }

        $hander            = M('hostlist');
        $where['parentid'] = I('id');
        $where['status']   = array('gt', 1);
        $virHostCount      = $hander->where($where)->count();
        if ($virHostCount) {
            $this->ajaxReturn('', '该服务器下还有上架的虚拟机,不能修改该服务器!', 0);
        } else {
            $this->ajaxReturn('', '', 1);
        }
    }

}
?>
