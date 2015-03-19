<?php
/*
 * Created on 2013-9-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class AjaxAction extends CommonAction{
    /**
     * 查询该服务器下的虚拟有虚拟机
     */
    public function getVirHost(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $initData = $this->getInitData();
        $id = I('id');
        if($houid = M('Hostlist')->where($id)->where(array('ishost'=>1))->field('houid')->find()){
            $virHost = M('Hostlist')->where(array('parentid'=>$id))->select();
            $virHost = $this->serverChange($virHost,$initData);
            $this->ajaxReturn($virHost,'',1);
        }else{
            $this->ajaxReturn('','',0);
        }
    }
    
    public function getSinfo(){
        if(!IS_AJAX)$this->error("网页不存在");
        $id = I('id');
        $handler = M('Hostlist');
        $host = $handler->where('id='.$id)->find();
        p($host);
        $ret = $this->getServerInfo($host['innip'], $host['houid']);
        if($ret == false){
            $this->ajaxReturn('获取服务器信息失败!','',0);
        }
        $text = "<table>
            <tr>
                <td>test</td>
            </tr>
        </table>";
        $this->ajaxReturn($text,'',1);
        
    }
    
    /**
     * 增加或删除机位
     */
    public function addOrdelSeat(){
        if(!IS_AJAX) $this->error('网页不存在');
        $id = I('id');
        if(!$id) $this->ajaxReturn('','缺少参数!',0);
        $cupHandle = M('cupboard');
        $cupInfo = $cupHandle->where('id='.$id)->find();
        if(I('type') == 1){
            $result = array('seatnum'=>$cupInfo['seatnum'] + 1);
            $data['cupid'] = $id;
            $data['seatid'] = $result['seatnum'];
            $res = M('seat')->add($data);
        }else{
            if($cupInfo['seatnum'] == 0){
                $this->ajaxReturn('','该机房没有机位可以减少!',0);
            }
            $status = M('seat')->where(array('cupid'=>$cupInfo['id'],'seatid'=>$cupInfo['seatnum']))->find();
            if($status['state'] == 1){
                $this->ajaxReturn('','最大机位已使用,不能删除!',0);
            }else{
                $result = array('seatnum'=>$cupInfo['seatnum'] - 1);
                $data['cupid'] = $id;
                $data['seatid'] = $cupInfo['seatnum'];
                $seatHandle = M('seat');
                $res = $seatHandle->where($data)->delete();
            }
        }
        if($res){
            $res = $cupHandle->where('id='.$id)->save($result);
        }else{
            $this->ajaxReturn('','修改机位失败!',0);
        }
        if($res){
            $this->ajaxReturn('','',1);
        }else{
            $this->ajaxReturn('','修改机位失败!',0);
        }
    }
    public function syncAll(){
        if(!IS_AJAX) $this->ajaxReturn('该页面不存在','',0);
        $hostAction = D('hostlist');
        $map['id'] = I('id');
        if($host = $hostAction->where($map)->find()){
            import("@.Common.Salt");
            $salt = new Salt();
            $ret= $salt->serverOp($host['uuid'],$host['houid'],'uqee_info.sync_all','wly',$host['mainip']);
            // p($ret);
            if(array_key_exists('result', $ret)){
                $this->ajaxReturn($ret['comment']['msg'],'通知',$ret['result']);
            }else{
                $this->ajaxReturn('同步数据失败','警告',0);
            }
            
        }else{
            $this->ajaxReturn('该页面不存在','警告',0);
        }
    }
    public function updateuuid(){
        if(!IS_AJAX) $this->ajaxReturn('该页面不存在','',0);
        $hostAction = M('hostlist');
        $host = $hostAction->where('id='.I('id'))->find();
        import("@.Common.Salt");
        $salt = new Salt();
        $uuid = $salt->getUuid($host['mainip']);
        // p($uuid);
        if(!empty($uuid['uuid'])){
            $this->ajaxReturn($uuid['uuid'],'',1);
        }else{
            $this->ajaxReturn('','',0);
        }
    }
    
    /**
     * 获取统计数据
     */
    public function getAmountCount(){
        if(!IS_AJAX) $this->error('网页不存在');
        $initData = $this->getInitData();
        $hostAmount = M('hostamount');
        $where['time'] = array(array('egt',I('start')),array('elt',I('end')));
        $returndata = array();
        $type = I('type') ;
        $start = I("start") ;
        $costType = I('costtype');
        $end = I('end');
        if($type == 1){
            $result = $hostAmount->where($where)->select();
            foreach($result as $value){
                $value = json_decode($value['data'],true);
                foreach($value['amount'] as $gameid=>$v){
                    if($gameid == 0){
                        if($costType == 1){
                            $returndata['data']['闲置']['rmb'] += array_sum($v);
                        }else{
                            $returndata['data']['闲置']['rmb'] += $v['bandwidth'] + $v['cupboard'];
                        }
                        
                        continue;
                    }
                    if($costType == 1){
                        $returndata['data'][$initData['game'][$gameid]]['rmb'] += array_sum($v);
                    }else if($costType == 2){
                        $returndata['data'][$initData['game'][$gameid]]['rmb'] += $v['bandwidth'] + $v['cupboard'];
                    }else{
                        $returndata['data'][$initData['game'][$gameid]]['rmb'] += $v['hostPrice'] ;
                    }               
                    $returndata['data'][$initData['game'][$gameid]]['带宽费用'] += $v['bandwidth'];
                    $returndata['data'][$initData['game'][$gameid]]['服务器折旧费'] += $v['hostPrice'];
                    $returndata['data'][$initData['game'][$gameid]]['IDC托管费'] += $v['cupboard'];
                }
            }
            
        }elseif($type == 2){  //按天循环计算当天的费用. 如果当天没有该项目.则此项目值为0
            $time = $this->getDateRang($start,$end);
            $gameArr = $initData['game'];
            $gameArr[0] = '闲置';
            foreach($time as $t){
                $result = $hostAmount->where(array('time'=>$t))->find();
                if(!$result) continue;
                $returndata['time'][] = $result['time'];
                $result = json_decode($result['data'],true);
                foreach($gameArr as $gameid=>$name){
                    $v = $result['amount'][$gameid];
                    $returndata['data'][$gameid]['name'] = $name;
                    if($costType == 1){
                        $returndata['data'][$gameid]['data'][] = is_array($v)?array_sum($v):0;
                    }else if($costType == 2){
                        $returndata['data'][$gameid]['data'][] = is_array($v)?$v['bandwidth'] + $v['cupboard']:0;
                    }else{
                        $returndata['data'][$gameid]['data'][] = is_array($v)?$v['hostPrice']:0;
                    }
                }
            }
        }
        if($start == $end){
            $info = $end;
        }else{
            $info = $start . " 至 " . $end;
        }
        if($returndata){
            $this->ajaxReturn($returndata,$info,1);
        }else{
            $this->ajaxReturn($returndata,$info,0);
        }
        
    }
    /**
     * 增加虚拟机时选择模板
     */
    public function getTempInfo(){
        if(!IS_AJAX) $this->error('网页不存在');
        $where=$_POST;
        $result = M('hosttemplate')->where($where)->find();
        if($result){
            $this->ajaxReturn($result,'',1);
        }else{
            $this->ajaxReturn('','',0);
        }
    }
    
    /**
     * 搜索Ip
     * 
     */
    public function index(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $where = $_POST;
        $where['mainip'] = array('like',i('mainip').'%');
        $result = M('Iplist')->where($where)->field('mainip')->limit(80)->select();
        $jsonstr = json_encode($result);
        echo $jsonstr;
    }
    
    /**
     * 返回第二Ip
     */
    public function subIndex(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $where = $_POST;
        $result = M('Iplist')->where($where)->field('subip')->find();
        echo $result['subip'];
    }
    /**
     * 
     * 返回机位号
     */
    
    public function seatid(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $where = $_POST;
        $where['state'] = 0;
        $result = M('Seat')->where($where)->field('seatid')->select();
        $this->ajaxReturn($result,'',1);
    }
    
    /**
     * 
     * 返回该机位的状态
     */
    public function seatState(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $where = $_POST;
        $id = I('id');
        unset($where['id']);
        $result = M('Seat')->where($where)->field('state')->find();
        $hostStat = M('Hostlist')->where(array('id'=>$id))->field('cupid,seatid')->find();
//      p($hostStat);p($where);
//      return;
        if($where == $hostStat || $result['state'] == 0){
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 返回IP状态
     */
    public function checkState(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $where = $_POST;
        $id = I('id');
        unset($where['id']);
        $result = M('Iplist')->field('state')->where($where)->find();
        if(count($result)>0){
            echo $result['state'];
        }else{
            echo 3;
        }
    }
    
    /**
     * 返回服务器ID
     * 
     */
    public function getHostInfo(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $where = $_POST;
        $result = M('Hostlist')->where($where)->find();
        echo $result['hostid'];
    }
    
    /**
     * 交换服务器信息.除物理信息以外
     * 
     */
    public function handleHost(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $where = $_POST;
        if($where['method'] == 'switch'){
            $tmpArray = array('mainip','subip','innip','status','hostype','owner','gameid','pretime','starttime','remark'); 
            $result = $this->switchHost($where,$tmpArray);
        }elseif($where['method'] == 'chgip'){
            $tmpArray = array('mainip','subip','innip');
            $result = $this->switchHost($where,$tmpArray,false);
        }
        elseif($where['method'] == 'chgdown'){
            $result = $this->hostShutdown($where);
        }
        
        if($result == 0){
            echo U("Home/Hostinfo/hostInfo",array('hostid'=>$where['currid']));
        }
    }
    public function getVirhostCount(){
        if(!IS_AJAX) $this->error('网页不存在',U("Home/Index/index"));
        $hander = M('hostlist');
        $where['parentid'] = I('id');
        $where['status'] = array('gt',1);
        $virHostCount = $hander->where($where)->count();
        if($virHostCount){
            $this->ajaxReturn('','该服务器下还有上架的虚拟机,不能修改该服务器!',0);
        }else{
            $this->ajaxReturn('','',1);
        }
    }
    
}
?>