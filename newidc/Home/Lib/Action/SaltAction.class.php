<?php
class SaltAction extends CommonAction{
    public function _initialize(){
        import("@.Common.Salt");
        $this->saltapi = new Salt();
    }
    public function minion(){
        $model = D($this->getActionName());
        $masters = $model->select();
        $this->masters = $masters;
        $this->display();
    }
    public function submaster(){
        $model = D($this->getActionName());
        $masters = $model->select();
        $this->masters = $masters;
        $this->display();
    }
    /**
     * 删除子Master配置
     * @return [type] [description]
     */
    public function delsubmaster(){
        if(!IS_AJAX) $this->error('页面不存在');
        $id = I('id');
        $model = D($this->getActionName());
        if($model->where(array('id'=>$id))->delete()){
            $this->ajaxReturn('删除成功','成功',1);
        }else{
            $this->ajaxReturn('删除失败','失败',0);
        }
    }
    public function addmaster(){
        $houseModel = D('house');
        $houselist = $houseModel->select();
        $this->house = $houselist;
        $this->method = 'add';
        $this->display();
    }
    public function editmaster(){
        $houseModel = D('house');
        $houselist = $houseModel->select();
        $this->house = $houselist;
        $model = D($this->getActionName());
        $id = I('id');
        $map = array('id'=>$id);
        $master = $model->where($map)->find();
        if(!$master){
            $this->error('要修改的服务器不存在');
        }
        $this->master = $master;
        $this->method = 'update';
        $this->display('addmaster');
    }
    public function key(){
        $id = I('id');
        $model = D($this->getActionName());
        $proxy = $model->where(array('id'=>$id))->find();
        if(!$proxy)
            $this->error('该子Master服务器不存在!');
        $ret = S('keys'.$id);
        if(!$ret){
            $ret = $this->saltapi->getkey($proxy['houseid']);
            S('keys'.$id,$ret,300);
        }

        $this->keys = $ret;
        $this->display();
    }
    public function single(){
        $funs = C('SALT_FUN');
        if(isset($_GET['id'])){
            $model = D('Hostlist');
            $host = $model->where(array('id'=>I('id')))->find();
            $this->tgt = $host['uuid'];
            $this->expr_form = 'glob';
        }
        $this->functions = $funs;
        $this->method = 'execute_function';
        $this->display();
    }

    /**
     * 执行salt之前对数据进行检查
     * @return [type] [description]
     */
    private function _before_execute_function(){
        $model = D('hostlist');
        $map =array();
        if(empty($_POST['expr_form'])){
            $this->error('请选择正常执行方式');
        }
        if(empty($_POST['tgt'])){
            $this->error('目标服务器不能为空');
        }
        if(empty($_POST['fun'])){
            $this->error('请选择正确的执方法');
        }
        if($_POST['expr_form'] == 'ipcidr'){
            $map['mainip|innip'] = I('tgt') ;
            if(!$model->where($map)->count()){
                $this->error('该服务器不存在!');
            }
        }else if($_POST['expr_form'] == 'glob'){
            $map['uuid'] = I('tgt') ;
            if(!$model->where($map)->count()){
                $this->error('该服务器不存在!');
            }
        }
        function checkArg($var){
            if(!empty($var)) return $var;
        }
        $_POST['arg'] = join('||',array_filter($_POST['arg'],'checkArg'));
        return $map;
    }

    public function range(){
        $funs = C('SALT_FUN');
        $model = D('SaltLog');
        import('ORG.Util.Page');
        $map = array('id'=>I('GET.id'));
        $sa = D('Salt')->where($map)->find();
        $map = array('houid'=>$sa['houseid']);
        $count = $model->count();
        $page = new Page($count,10);
        $this->page = $page->show();
        $limit = $page->firstRow .','. $page->listRows;
        $logs = $model->where($map)->order('time desc')->limit($limit)->select();
        $this->logs = $logs;
        $this->functions = $funs;
        $this->method = "multiple";
        $this->houseid = $sa['houseid'];
        $this->display('single');
    }


    public function _before_multiple(){
        if(empty($_POST['expr_form'])){
            $this->error('请选择正常执行方式');
        }
        if(empty($_POST['tgt'])){
            $this->error('目标服务器不能为空');
        }
        if(empty($_POST['fun'])){
            $this->error('请选择正确的执方法');
        }
        function checkArg($var){
            if(!empty($var)) return $var;
        }
        $_POST['arg'] = join('||',array_filter($_POST['arg'],'checkArg'));
    }
    /**
     * 执行salt方法操作
     * 单机操作
     * @return [type] [description]
     */
    public function execute_function(){
        $map = $this->_before_execute_function();
        $model = D('Hostlist');
        $host = $model->where($map)->find();
        if($host['mainip'] == $_POST['tgt'] || $host['innip'] == $_POST['tgt'] ||
         $host['uuid'] == $_POST['tgt']){
            $ret = $this->saltapi->serverOp($host['uuid'],$host['houid'],
                $_POST['fun'],$_POST['arg'],$_POST['timeout'],$host['mainip']);
            $logdata = array('fun'=>$_POST['fun'],'houid'=>$host['houid'],
                'tgt'=>$_POST['tgt'],'arg'=>$_POST['arg'],
                'sync'=>1,'time'=>time());
            $logid = $this->saltapi->addlog($logdata);
            Log::write(print_r($ret,true));
            if(!array_key_exists('status', $ret)){
                foreach($ret['minions'] as $key=>$value){
                    if(is_array($value['return'])){
                        $value['return'] = json_encode($value['return'],JSON_UNESCAPED_UNICODE);
                        $value['return'] = str_replace(',', ",\n", $value['return']);
                        $ret['minions'][$key] = $value;
                    }
                }
                $this->result = $ret;
                // p($ret);die;
                $logdata = array('id'=>$logid,'jid'=>$ret['jid']);
                $this->saltapi->addlog($logdata,false);
                Log::write(print_r($ret,true));
                // p($ret);die;
                $this->display('execute_function');
            }
        }else{
            $this->error($ret['msg']);
        }
    }
    /**
     * 批量执行任务
     *
     * @return [type] [description]
     */
    public function multiple(){
        $ret = $this->saltapi->rangeServerOp($_POST['tgt'],$_POST['fun'],$_POST['arg'],
            $_POST['expr_form'],$_POST['met'],$_POST['houseid']);
        $logdata = array('fun'=>$_POST['fun'],'houid'=>$_POST['houseid'],
                'tgt'=>$_POST['tgt'],'arg'=>$_POST['arg'],
                'sync'=>$_POST['met'],'time'=>time());
        $logid = $this->saltapi->addlog($logdata);
        if(!array_key_exists('status', $ret) && $_POST['met'] == 1){
            $logdata = array('id'=>$logid,'jid'=>$ret['jid']);
            foreach($ret['minions'] as $key=>$value){
                if(is_array($value['return'])){
                    $value['return'] = json_encode($value['return'],JSON_UNESCAPED_UNICODE);
                    $ret['minions'][$key] = $value;
                }
            }
            $this->saltapi->addlog($logdata,false);
            $this->result = $ret;
            $this->display('execute_function');
        }elseif (!array_key_exists('status', $ret) && $_POST['met'] == 0) {
            $logdata = array('id'=>$logid,'jid'=>$ret);
            $this->saltapi->addlog($logdata,false);
            $this->result = $ret;
            $this->ajaxReturn($ret,'任务ID',1);
        }else{
            $this->error($ret['msg']);
        }
    }


    public function mission(){
        // p($_POST);die;
        $houid = I('GET.id');
        if(empty($houid)){
            $this->error('非法请求1');
        }
        $model = D('Salt');
        if(!$model->where(array('houseid'=>$houid))->count()){
            $this->error('非法请求2');
        }
        $glob = I('glob');
        $ipcidr = I('ipcidr');
        $jid = I('jid');
        $params = array();
        if(!empty($glob)){
            $params['glob'] = $glob;
        }
        if(!empty($ipcidr)){
            $params['ip'] = $ipcidr;
        }
        if(!empty($jid)){
            $params['jid'] = $jid;
        }
        // p($params);
        if(empty($params) ){
            if(IS_POST)
                $this->error('缺少必要参数!');
            else
                $this->display();
        }else{
            if(isset($params['glob']) && isset($params['ip'])){
                unnset($params);
            }elseif(isset($params['ip'])){
                $map = array();
                $map['mainip|innip'] = $params['ip'];
                $host = D('hostlist')->where($map)->find();
                if(!$host){
                    $this->error('该服务器不存在1!');
                }
                if(empty($host['uuid'])){
                    $uuid = $this->saltapi->getUuid($params['ip']);
                    $params['glob'] = $uuid['uuid'];
                }else{
                    $params['glob'] = $host['uuid'];
                }
                unset($params['ip']);
            }

            $result = $this->saltapi->getReturn($params['jid']?$params['jid']:null,
                $houid,$params['glob']?$params['glob']:null);
            if($result['status'] !== 0){
                $this->error('未获取到任何记录!');
            }
            $ret = array('minions'=>array(),'total'=>0,'success'=>0);
            foreach($result['data'] as $value){
                if(is_array($value['returns']['return'])){
                    $value['returns']['return'] = json_encode($value['returns']['return'],JSON_UNESCAPED_UNICODE);
                    $value['returns']['return'] = str_replace('",',"\",\n",$value['returns']['return'] );
                }
                array_push($ret['minions'],$value['returns']);
                $ret['total']++;
                if($value['returns']['success'] == 1 && $value['returns']['retcode'] == 0){
                    $ret['success']++;
                }
            }
            // p($ret);
            $this->result = $ret;
            $this->display('execute_function');
        }
    }
}

?>
