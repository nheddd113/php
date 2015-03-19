<?php
/*
 * Created on 2013-9-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class PublicAction extends CommonAction{
    public  function idcHouse(){
        $data = M('House')->field('id,houname')->select();
        $this->assign('house',$data);
        $this->display();
    }
    public function changeNotify(){
        $model = M('Notify');
        $state = $model->find();
        if ($state['state'] == I('state')){
            $state['state'] = !I('state');
            if($model->save($state)){
                $this->success('修改报警状态成功!');
            }else{
                $this->error('修改报警状态失败!');
            }
        }
    }
    /**
     * 修改机房信息视图
     */
    public function amendHouse(){
        $houseHandle = M('house');
        $where['id'] = I('id');
        $houseInfo = $houseHandle->where($where)->find();
//      p($houseInfo);
        $this->house = $houseInfo;
        $this->display();
    }
    /**
     * 修改机房数据处理
     */
    public function amendHouseHandle(){
        $houseHandle = M('house');
        $where = $_POST;
        $where['changetime'] = time();
        if($houseHandle->save($where)){
            $this->success('修改机房信息成功!');
        }else{
            $this->error('修改机房信息失败!');
        }
    }
    /**
     *删除机房
     */
    public function deleteHouse(){
        $handle = D("House");
        $list = $handle->relation(true)->find(I('id'));
        if(count($list['cupinfo']) > 0){
            $this->error('该机房下还有机柜,不能删除该机房!');
        }else{
            if($handle->where('id='.I('id'))->delete()){
                $this->success('删除机房成功!');
            }else{
                $this->error('删除机房失败!');
            }
        }

    }

    /**
     * 查看机房视图
     */
    public function showHouse(){
        $initData = $this->getInitData();
        $this->houseList = $initData['idcHouse'];
        $this->display();
    }
    /**
     * 修改机柜信息视图
     *
     */
    public function amendCup(){
        $initData = $this->getInitData();
        $where = $_GET;
        unset($where['_URL_']);
        $cupHandle = M('cupboard');
        $cupInfo = $cupHandle->where($where)->find();
        $this->houseList = $initData['house'];
        $this->cupInfo = $cupInfo;
        $this->display();
    }
    public function amendCupHandle(){
        $data = $_POST;
        $data['changetime'] = time();
        if(M('cupboard')->save($data)){
            $this->success('修改成功!');
        }else{
            $this->error('修改失败!');
        }
    }
    public function showlog(){
        $getLog = M('log');
        $count = $getLog->count();
        $page = $this->startPage($count, 30);
        $limit = $page->firstRow .','.$page->listRows;
        $this->page = $page->show();
        $logData = $getLog->limit($limit)->order('logtime desc')->select();
        $this->logdata = $logData;
        $this->display();
    }
    public function showip(){
        $where = $_GET;
        unset($where['_URL_']);
        $house = M('house')->select();
        if(isset($where['mainip'])){
            $where['mainip'] = array('like',$where['mainip']);
        }
        $idcHouse = array();
        foreach($house as $hou){
            $idcHouse[$hou['id']] = $hou['houname'];   //把机房的二维数组转换成一维数组
        }
        $this->status = $where['state'];
        $this->houid = $where['houid'];
        $used = M('iplist')->where('state=1')->count();
        $unused = M('iplist')->where('state=0')->count();
        $this->used=$used;
        $this->unused=$unused;
        $this->house = $idcHouse;
        $this->search = substr(I('mainip'),0,-1);
        $count = M('Iplist')->where($where)->count();
        $page = $this->startPage($count, 30);
        $limit = $page->firstRow .','.$page->listRows;
        $iplist = M('Iplist')->where($where)->limit($limit)->select();
        $this->iplist = $iplist;
        $this->page = $page->show();
        $this->display();
    }
    public function deleteip(){
        $where = $_GET;
        unset($where['_URL_']);
        $stateRes = M('Iplist')->where($where)->field('state')->find();
        $state = $stateRes['state'];
        if($state == 1){
            $this->error("该IP已被服务器使用,不能删除!",U(APP_NAME . '/Public/showip'));
        }else{
            if(M('Iplist')->where($where)->delete()){
                $this->success("IP删除成功!",U(APP_NAME . '/Public/showip'));
            }
        }
    }
    public function changeState(){
        if(!IS_AJAX) halt('该网页不存在!');
        $mainip = M('iplist')->where('id='.I('id'))->find();
        $hostinfo = M('hostlist')->where(array('mainip'=>$mainip['mainip']))->find();
        if($hostinfo){
            $str = '该IP被服务器<'.$hostinfo['hostid'].">使用!";
            $this->ajaxReturn('',$str,0);
        }else{
            if(I('state') == $mainip['state']){
                $this->ajaxReturn('',"要修改的状态与原状态相同,不必修改!",0);
            }else{
                $data = array('state'=>I('state'));
                if(M('iplist')->where('id='.I('id'))->save($data)){
                    $this->ajaxReturn('',"修改成功!",1);
                }else{
                    $this->ajaxReturn('',"修改失败!",0);
                }
            }
        }
    }
    public function getUrl(){
        if(!IS_AJAX) halt();
        $data = $_POST;
        $url = U(APP_NAME . '/Public/showip',$data);
        $this->ajaxReturn('',$url,1);
    }
    /**
     * CDN
     *
     */
    public function cdn(){
        $this->display();
    }
    public function sendContent(){
        if(!IS_POST) halt('该网页不存在!');
        $method = $_POST['ops'] == 'lx'?'lx':'ws';
        $result = $this->$method($_POST['content']);
        $this->ajaxReturn('',$result,1);
    }
    private function lx($content){
        $host = "http://push.dnion.com/cdnUrlPush.do?";
        $params = array(
            'captcha'=>'436bd460',
            'type' => 1,
            'decode' => 'n',
            );
        $urls = explode("\n",$content);
        $urls = join(",",$urls);
        $params['url'] = $urls;
        $query = http_build_query($params);
        // echo $host . $query;
        $ch = curl_init($host . $query);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        // p($info);
        return $info['http_code'] != 200?'推送失败':'推送成功';

    }
    private function ws($content){
        $host = "http://wscp.lxdns.com:8080/wsCP/servlet/contReceiver?";
        $content = explode("\n",$content);
        $url = '';
        foreach($content as $k=>$v){
            $v = str_replace('http://','',$v);
            $url .= $v;
            if(isset($content[$k])){
                $url .= ';';
            }
        }
        $data = array('username'=>'snsfun','passwd'=>'soidc..123','url'=>$url);
        $str = $data['username'] . $data['passwd'] . $url;
        $data['passwd'] = md5($str);
        $data = http_build_query($data);
        $url = $host . $data;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        return strpos($ret,'success') === false?'推送失败':'推送成功';
    }
}
?>