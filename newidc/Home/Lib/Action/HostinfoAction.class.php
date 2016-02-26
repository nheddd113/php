<?php
/*
 * Created on 2013-9-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class HostinfoAction extends CommonAction {
    public function index() {
        $model    = D('Hostlist');
        $hostinfo = $model->relation(true)->where("id=" . $_GET['id'])->find();
        // p($hostinfo);
        // p($model);
    }

    private function getMaxHostId($hostid) {
        $model    = M('Hostlist');
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
    }

    public function hostInfo() {
        $model = D('Hostlist');
        import("@.Common.Salt");
        $salt     = new Salt();
        $where    = $this->_search($model);
        $hostinfo = $model->where($where)->find();
        if (count($hostinfo) == 0) {
            $this->error("服务器不存在!", U(APP_ANME . '/Search/search'));
        }
        $where = array('id' => $hostinfo['houid']);
        // $houseinfo = M('House')->select();
        $hosttype     = C('HOST_TYPE');
        $cupboardinfo = D('Cupboard')->getData('', '', array('houid' => $hostinfo['houid']));
        // p($cupboardinfo);die;
        $seatinfo   = M('Seat')->where(array('cupid' => $hostinfo['cupid'], 'state' => 0))->field('seatid')->select();
        $seatinfo[] = array('seatid' => $hostinfo['seatid']);
        $initData   = $this->getInitData();
        if ($hostinfo['ishost'] == 1) {
            //如果是宿主机.就把该服务器的虚拟机也查询出来
            $virHostInfo = $model->where(array('parentid' => $hostinfo['id']))->order('hostid desc')->select();
            $virHostInfo = $this->serverChange($virHostInfo, $initData);
            // p($virHostInfo);die;
            $this->assign('virHostInfo', $virHostInfo);
        } elseif ($hostinfo['ishost'] == 2) {
            // 如果是虚拟机. 把宿主机的IP查出来.
            $master = $model->field('mainip')->find($hostinfo['parentid']);
            $this->assign('master', $master);
        }
        /*  if(empty($hostinfo['uuid'])){
        $ret = $salt->getUuid($hostinfo['mainip']);
        if($ret['state'] == 0){
        $model->where('id='.$hostinfo['id'])->save(array('uuid'=>$ret['uuid']));
        $hostinfo['uuid'] = $ret['uuid'];
        }
        }*/
        $this->getMaxHostId($hostinfo['id']);
        $logDB   = M('log');
        $logData = $logDB->where(array('hostdbid' => $hostinfo['id']))->order('logtime desc')->select();
        if ($hostinfo['parentid'] != 0) {
            $superIp = $model->where('id=' . $hostinfo['parentid'])->find();
        }
        // p($logData);die;
        $iplist = D('Iplist')->getData('', '', array('houid' => $hostinfo['houid']));
        // sort($seainfo);
        $this->log_data = $logData;
        $this->ops      = $initData['owner'];
        $this->superip  = $superIp;
        $this->assign('gameCode', $initData['gameCode'])->assign('houseInfo', $initData['idcHouse'])->assign('cupBorard', $cupboardinfo)->assign('seatInfo', $seatinfo);
        $this->assign('iplist', $iplist);
        $this->assign('hostinfo', $hostinfo)->assign('OpsArray', $initData['idcOps'])->assign('ishostArr', $hosttype);
        // p($hostinfo);p($cupboardinfo);die;
        $this->display();
    }
    /**
     * 检查修改主机时间参数
     * @param array $data  主机数据
     * @return None
     */
    private function checkChange(&$data) {
        $ishost = array_key_exists('ishost', $data) ? $data['ishost'] : $data['h_ishost'];
        $cupid  = array_key_exists('cupid', $data) ? $data['cupid'] : $data['h_cupid'];
        $seatid = array_key_exists('seatid', $data) ? $data['seatid'] : $data['h_seatid'];
        $owner  = array_key_exists('owner', $data) ? $data['owner'] : $data['h_owner'];
        $gameid = array_key_exists('gameid', $data) ? $data['gameid'] : $data['h_gameid'];
        if ($data['status'] == 1) {
            if (empty($data['mainip'])) {
                $this->error('Ip地址不能为空!');
            }
            if (empty($cupid) && $ishost != 2) {
                $this->error('机柜或机位信息不能为空!');
            }
            if (!empty($data['hostype'])) {
                $data['hostype'] = 0;
            }
            if (!empty($gameid)) {
                $this->error('非运营的机器不能选择所属用途!');
            }
        }
        if ($data['status'] == 2) {
            if (empty($owner)) {
                $this->error('运营商不能为空!');
            }
            if (empty($gameid) && $ishost != 2) {
                $this->error('所属用途不能为空!');
            }
            if (empty($data['hostype'])) {
                $this->error('类型不能为未运营!');
            }
        }
        if ($data['status'] == 2 && $data['hostype'] == 1) {
            if (empty($data['pretime'])) {
                $this->error('预定运营时间不能为空!');
            }
        }
        if ($data['status'] == 2 && $data['hostype'] == 2 && empty($data['starttime'])) {
            $data['starttime'] = date('Y-m-d H:i:s');
        }
        if (!is_numeric($data['mem'])) {
            $this->error('内存大小非法,内存大小只能是数字!');
        }

        if (!is_numeric($data['disk'])) {
            $this->error('硬盘大小非法,硬盘大小只能是数字!');
        }

    }

    public function _before_changeHost() {
        if (!empty($_POST['mainip']) && $_POST['status'] == 0) {
            $_POST['status'] = 1;
        }
        $model              = D('Hostlist');
        $ret                = $model->find((int) $_POST['id']);
        $_POST['ismanager'] = (int) (bool) $_POST['ismanager'];
        if ($ret['ishost'] == 2) {
            $_POST['ishost'] = 2;
        }

    }

    /**
     * [changeHost description]
     * @return [type] [description]
     */
    public function changeHost() {
        // p($_POST);
        if (!IS_POST) {
            $this->error('页面不存在!', U('Search/search'));
        }

        $uuid     = I('uuid');
        $status   = I('status');
        $hander   = D('Hostlist');
        $initData = $this->getInitData();
        $data     = $this->_search($hander,false);
        // echo "<pre>";
        // p($data);die;
        $data['mem']        = str_replace('g', '', str_replace('b', '', strtolower($data['mem'])));
        $data['disk']       = str_replace('g', '', str_replace('b', '', strtolower($data['disk'])));
        $data['changetime'] = time();
        $this->checkChange($data);
        import('@.Common.change');
        import('@.Common.idclog');
        import('@.Common.Salt');
        $change     = new change;
        $startLog   = new idclog;
        $salt       = new Salt();
        // p($data);die;
        $souHostype = $hander->where(array("id" => $data["id"]))->find();
        // if (empty($uuid) && ($souHostype['mainip'] == $data['mainip'] && $souHostype['innip'] == $data['innip'] &&
        //     $souHostype['subip'] == $data['subip'])) {
        //     $this->error("不能修改UUID为空的服务器");
        // }
        if ($hander->save($data)) {
            if (I('status') == 1) {
                //修改为闲置的时候清除自已的计划任务
                $cleanCronState = $salt->serverOp($data['uuid'], $data['houid'],
                    'file.remove', '/var/spool/cron/crontabs/root', $data['mainip']);
            }
            $newData = $hander->where(array("id" => $data["id"]))->find();
            if ($data['ishost'] == 1) {
                //修改宿主机时,并将其下的虚拟机也一起修改 ,所属用途,托管,机位信息
                $isHostData = array(
                    // 'gameid'    => $data['gameid'],
                    'ismanager' => $data['ismanager'],
                    'cupid'     => $data['cupid'],
                    'seatid'    => $data['seatid'],
                );
                $hander->where(array('parentid' => $data['id']))->save($isHostData);
                if ($data['status'] == 0) {
                    $virList = $hander->where(array('parentid' => $data['id']))->select();
                    $xiaJia  = C("xia_jia");
                    $stat    = array('state' => 0);
                    foreach ($virList as $value) {
                        if ($value['status'] == 0) {
                            continue;
                        }

                        $hander->where("id=" . $value['id'])->save($xiaJia);
                        M('seat')->where("cupid=" . $value['cupid'] . " and seatid=" . $value['seatid'])->save($stat);
                        $iplist = M('iplist');
                        $iplist->where(array('mainip' => $value['mainip']))->save($stat);
//                      p($iplist);
                        $startLog->hostLinkDown($value);
                    }
                }
            }
            $this->chgmanagerHost($where);
            $change->hostchange($souHostype, $newData, $initData);
            if (isset($cleanCronState) && $cleanCronState === false) {
                $msg = "修改服务器信息成功,但删除计划任务失败,请上服务器进去确认!";
            } else {
                $msg = "修改成功!";
            }
            $this->success($msg);
        } else {
            $this->error('修改失败!');
        }
    }
    /**
     * [changeHost_bak description]
     * @return [type] [description]
     */
    public function changeHost_bak() {
        $url      = U('/Hostinfo/hostInfo/id/' . I('id'));
        $hostList = M('hostlist');
        if (!IS_POST) {
            $this->error('此页面不存在!', U('/Index/index'));
        }

        if (I('status') >= 1 && I('mainip') == '') {
            $this->error('IP地址不能为空!', $url);
        }

        if (I('hostype') >= 1 && I('gameid') === 0) {
            $this->error('所属游戏不能为空!', $url);
        }

        if (I('hostype') >= 1 && I('owner') == 0) {
            $this->error('运营商不能为空!', $url);
        }

        if (I('status') <= 1 && I('gameid') != 0) {
            $this->error('服务器不为上架状态,不允许修改所属业务', $url);
        }

        $where         = $_POST;
        $where['mem']  = str_replace('g', '', str_replace('b', '', strtolower($where['mem'])));
        $where['disk'] = str_replace('g', '', str_replace('b', '', strtolower($where['disk'])));
//      if(!is_numeric($where['mem'])) $this->error('内存数量非法!');
        //      if(!is_numeric($where['disk'])) $this->error('硬盘数量非法!');
        $where['changetime'] = time();
        $hostye              = I("hostype");
        $souHostype          = M("Hostlist")->where(array("id" => $where["id"]))->find();
        if (($hostye == 2 && $hostye != $souHostype["hostype"]) || ($souHostype["starttime"] == '' && $hostye == 2)) {
            //判断是开始运营
            $where['starttime'] = date('Y-m-d H:i:s');
        }
        $initData = $this->getInitData();
        if ($hostList->save($where)) {
            import('@.Common.change');
            import('@.Common.idclog');
            $change   = new change;
            $startLog = new idclog;
            $newData  = $hostList->where('id=' . $where['id'])->find();
            //虚拟机所属业务,托管,机柜,机位 与宿主机相同
            if (I('ishost') == 1) {
                $data = array('gameid' => $where['gameid'],
                    'ismanager'            => $where['ismanager'],
                    'cupid'                => $where['cupid'],
                    'seatid'               => $where['seatid'],
                );
                $hostList->where(array('parentid' => $where['id']))->save($data);
            }
            //如果宿主机下架时判断虚拟机是否不为闲置
            if (I("ishost") == 1 && I("status") == 0) {
                $virList = $hostList->where(array('parentid' => $where['id']))->select();
                $data    = C("xia_jia");
                $stat    = array('state' => 0);
                foreach ($virList as $value) {
                    $hostList->where("id=" . $value['id'])->save($data);
                    M('seat')->where("cupid=" . $value['cupid'] . " and seatid=" . $value['seatid'])->save($stat);
                    M('iplist')->where('mainip=' . $value['mainip'])->save($stat);
                    $startLog->hostLinkDown($value);
                }

            }

            $where['ishost'] = $where['h_ishost'];
            $this->chgmanagerHost($where);
            $change->hostchange($souHostype, $newData, $initData);
            $this->success('修改成功!');
        } else {
            $this->error('修改失败!');
        }
    }
    /**
     * [deleteHost description]
     * @return [type]
     */
    public function deleteHost() {
        if (!IS_AJAX) {
            $this->error('此页面不存在!', U('/Index/index'));
        }

        $host     = M('hostlist');
        $hostinfo = $host->where('id=' . I('id', 0, 'intval'))->find();
        if ($hostinfo == false) {
            $this->ajaxReturn('通知', '服务器不存在!', 0);
        }
        if ($hostinfo['status'] == 2) {
            $this->ajaxReturn('通知', '服务器已上架,不能删除!', 0);
        } elseif ($hostinfo['ishost'] == 1) {
            if (M('hostlist')->where(array('parentid' => I('id')))->count()) {
                $this->ajaxReturn('通知', '该服务器为宿主机,且宿主机下还有虚拟机,不能删除!', 0);
            }
        }
        // $this->delete_uuid($hostinfo['uuid'],$hostinfo['houid']);
        $this->do_delete($hostinfo, I('id'));

    }
    private function delete_uuid($uuid, $houid) {
        import("@.Common.Salt");
        $salt = new Salt;
        $url  = $salt->getHost($houid);
        $uri  = '/cmd';
        $url .= $uri;
        $data         = array("client" => 'wheel', "fun" => "key.delete", "match" => $uuid);
        $data['sign'] = $this->checkBaseAuth('POST', $uri, $data['fun']);
        $data         = http_build_query($data);
        $ret          = $this->post($url, array(), $data);
        Log::write("删除UUID:" . json_encode($ret));
    }
    /**
     * [删除主机]
     * @param  [type] $hostinfo [description]
     * @param  [type] $id       [description]
     * @return [type]           [description]
     */
    private function do_delete($hostinfo, $id) {
        if (M('hostlist')->where('id=' . $id)->delete()) {
            import('@.Common.idclog');
            $startLog = new idclog;
            $ishost   = $hostinfo['ishost'] == 2 ? false : true;
            $startLog->hostCreateAndDelete(I('id'), $hostinfo['hostid'], false, $ishost);
            if ($hostinfo['seatid']) {
                M('seat')->where(array('cupid' => $hostinfo['cupid'], 'seatid' => $hostinfo['seatid']))->save(array('state' => 0));
            }
            if ($hostinfo['mainip']) {
                M('iplist')->where(array('mainip' => $hostinfo['mainip']))->save(array('state' => 0));
            }
            $this->ajaxReturn('通知', '删除服务器成功!', 1);
        } else {
            $this->ajaxReturn('通知', '删除服务器失败!', 0);
        }
    }
    /**
     * 系统操作
     *
     */
    public function systemOp() {
        if (!IS_POST) {
            $this->error('此页面不存在!', U('/Index/index'));
        }

        $initData      = $this->getInitData();
        $where         = $_POST;
        $hostlist      = D('hostlist');
        $where         = $this->_search($hostlist);
        $where['type'] = I('type', 0, 'intval');
        $hostinfo      = $hostlist->where('id=' . $where['id'])->find();
        if ($hostinfo['status'] != 0 && empty($hostinfo['uuid'])) {
            $this->ajaxReturn('警告', '不能修改UUID为空的服务器', 0);
        }
        import('@.Common.change');
        $change = new change;
        import('@.Common.idclog');
        $startLog = new idclog;
        if ($where['type'] == 1) {
            //清档
            $this->cleanDB($where['id']);
        } elseif ($where['type'] == 2) {
            if ($hostinfo['status'] == 0) {
                $this->ajaxReturn('警告', '服务器目前已是下架状态,请务重复操作!', 0);
            } else {
                $data = C('xia_jia');
                $hostlist->where('id=' . $where['id'])->save($data);
                $startLog->hostSystemOp($where['id'], $where['type']);
                $this->ajaxReturn('通知', '服务器下架成功!', 1);
            }
        } elseif ($where['type'] == 3) {
            if ($hostinfo['status'] != 0 || empty($where['houid'])) {
                $this->ajaxReturn('警告', '服务器不是下架状态或寄到机房为空,不能寄出操作', 0);
            }
            $data          = C('xia_jia');
            $data['houid'] = $where['houid'];
            $hostlist->where('id=' . $where['id'])->save($data);
            $startLog->hostSystemOp($where['id'], $where['type'], $initData['house'][$where['houid']]);
            $this->ajaxReturn('通知', '服务器寄出成功!', 1);
        } elseif ($where['type'] > 3) {
            $this->ajaxReturn('通知', '功能开发中,敬请期待', 0);
        } elseif ($where['type'] == 4) {
            //关闭游戏
            import("@.Common.Salt");
            $salt    = new Salt();
            $logdata = array('fun' => 'uqee_info.shutgame', 'houid' => $hostinfo['houid'],
                'tgt'                  => $hostinfo['mainip'], 'sync'   => 1, 'time' => time());
            $result = $salt->serverOp($hostinfo['uuid'], $hostinfo['houid'],
                'uqee_info.shutgame', null, 29, $hostinfo['ip']);
            $logid = $salt->addlog($logdata);
            $ret   = $result['minions'][0];
            $startLog->hostSystemOp($where['id'], $where['type'], $initData['house'][$where['houid']]);
            // p($result);
            if ($result['success'] > 0) {
                $logdata = array('id' => $logid, 'jid' => $result['jid']);
                $salt->addlog($logdata, false);
                $message = '';
                foreach ($ret['return']['comment'] as $key => $value) {
                    $message .= '程序:' . $key . ":\t";
                    $message .= is_array($value) ? join(',', $value) : $value;
                    $message .= "<br>";
                }
                $this->ajaxReturn('通知', $message, $result['success']);
            } else {
                $this->ajaxReturn('警告', '服务器没有返回,请查看salt日志是否操作成功', 0);
            }
        } elseif ($where['type'] == 5) {
            //开启游戏
            import("@.Common.Salt");
            $salt    = new Salt();
            $logdata = array('fun' => 'uqee_info.startgame', 'houid' => $hostinfo['houid'],
                'tgt'                  => $hostinfo['mainip'], 'sync'    => 1, 'time' => time());
            $logid  = $salt->addlog($logdata);
            $result = $salt->serverOp($hostinfo['uuid'], $hostinfo['houid'],
                'uqee_info.startgame', null, 29, $hostinfo['ip']);
            $ret = $result['minions'][0];
            // p($result);
            $startLog->hostSystemOp($where['id'], $where['type'], $initData['house'][$where['houid']]);
            if ($result['success'] > 0) {
                $logdata = array('id' => $logid, 'jid' => $result['jid']);
                $salt->addlog($logdata, false);
                $message = '';
                foreach ($ret['return']['comment'] as $key => $value) {
                    $message .= '程序:' . $key . ":\t";
                    $message .= is_array($value) ? join(',', $value) : $value;
                    $message .= "<br>";
                }
                $this->ajaxReturn('通知', $message, $result['success']);
            } else {
                $this->ajaxReturn('警告', '服务器没有返回,请查看salt日志是否操作成功', 0);
            }
        } elseif ($where['type'] == 6) {
            //获取服务器信息
            import("@.Common.Salt");
            $salt    = new Salt();
            $logdata = array('fun' => 'uqee_info.getInfo', 'houid' => $hostinfo['houid'],
                'tgt'                  => $hostinfo['mainip'], 'sync'  => 1, 'time' => time());
            $logid  = $salt->addlog($logdata);
            $result = $salt->serverOp($hostinfo['uuid'], $hostinfo['houid'],
                'uqee_info.getInfo', null, $hostinfo['ip']);
            $ret = $result['minions'][0];
            $startLog->hostSystemOp($where['id'], $where['type'], $initData['house'][$where['houid']]);
            if ($result['success'] > 0) {
                $logdata = array('id' => $logid, 'jid' => $result['jid']);
                $salt->addlog($logdata, false);
                $message = '';
                foreach ($ret['return'] as $key => $value) {
                    if ($key == 'result') {
                        continue;
                    }

                    $message .= L($key) . ":\t";
                    $message .= is_array($value) ? join(',', $value) : ($key == 'gamename' ? L($value) : $value);
                    $message .= "<br>";
                }
                // echo $message;
                $this->ajaxReturn('通知', $message, $result['success']);
            } else {
                $this->ajaxReturn('警告', '服务器没有返回,请查看salt日志是否操作成功', 0);
            }
        }
    }
    /**
     * 服务器清档操作
     * Enter description here ...
     * @param int $id
     */
    private function cleanDB($id) {
//      $initData = $this->getInitData();
        $hostlist = M('hostlist');
        $hostinfo = $hostlist->where('id=' . $id)->find();
        if ($hostinfo['hostype'] != 1) {
            $this->ajaxReturn('警告', '服务器不为申请状态,不能清档操作!', 0);
        } else {
            $data = array('hostype' => 2);
            $hostlist->where(array('id' => $id))->save($data);
            $this->ajaxReturn('通知', '清档成功!', 1);
        }
    }
}
?>
