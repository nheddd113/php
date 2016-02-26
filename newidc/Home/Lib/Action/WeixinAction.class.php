<?php
class WeixinAction extends Action{

    protected function _initialize(){
        import("@.Common.Salt");
        $this->saltapi = new Salt();
    }

    public function notice(){
        import("@.Common.weixin");
        $webchat = new Webchat();
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $fun = I('fun','');
        $sign = I('sign','');
        Log::write(print_r($_REQUEST,true),"Request","",LOG_PATH."Test_weixin".date('y_m_d').".log");
        $content = array();
        if($this->saltapi->verifyBaseAuth($method,$uri,$fun,$sign)){
            $content['content'] = I('content','Unkown');
            $content['content']= str_replace('\n', "\n", $content['content']);
            $partyid = I('partyid','');
            if(empty($partyid)){
                $partyid = 4;  // 监控组
            }else{
                $partyid = str_replace(',', '|', $partyid);
            }
            echo "partyid is ". $partyid;
            $state = M('notify')->find();
            if($state['state']>0){
                $this->ajaxReturn($webchat->send($content,$partyid),'',0);
            }

        }else{
            $this->ajaxReturn('认证失败','',1);
        }
    }
    /**
     * 登陆异常IP通知
     * 登陆组
     * @return [type] [description]
     */
    public function index(){
        import("@.Common.weixin");
        $webchat = new Webchat();
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = '/webchat';
        $fun = I('fun','');
        $sign = I('sign','');
        Log::write(print_r($_REQUEST,true),"Request","",LOG_PATH."Test_weixin".date('y_m_d').".log");
        $content = array();
        if($this->saltapi->verifyBaseAuth($method,$uri,$fun,$sign)){
            $content['content'] = I('content');
            if(empty($content)){
                $this->ajaxReturn('错误内容','',3);
            }
            $c = explode(" ", $content['content']);
            $c[10] = substr($c[10] . ' ' . $c[11],0,-1);
            $data = array('login_name'=>$c[1],'dest_ip'=>$c[8],
                'dest_hostname'=>$c[13],'request_ip'=>$c[4],
                'login_time'=>date('Y-m-d H:i:s'));
            M('SshloginLog')->add($data);
            Log::write(print_r($data,true),"Request","",LOG_PATH."Test_weixin".date('y_m_d').".log");
            if($this->check_ip_exists($c[4])){
                $partyid = 5;  // 登陆组微信
                $this->ajaxReturn($webchat->send($content,$partyid ),'',0);
            }else{
                $this->ajaxReturn('正常登陆不需要通知','',2);
            }

        }else{
            $this->ajaxReturn('认证失败','',1);
        }
    }
    private function check_ip_exists($ip){
        $model = D('Hostlist');
        $map['mainip|innip'] = $ip;
        if($model->where($map)->count()){
            return false;
        }
        // echo $model->getLastSql();
        $ips = array('101.95.5.22','27.115.76.2','27.115.76.3','27.115.76.4','27.115.76.5','192.168.102.227',
            '116.226.184.199','127.0.0.1','192.168.45.25');
        if(in_array($ip,$ips)){
            return false;
        }
        return true;
    }

    private function parseLog($arr){
        foreach($arr as $k=>$v){
            $v['content'] = explode(' ', $v['content']);
            // p($v);die;
            $v['content'][10] = substr($v['content'][10] . ' ' . $v['content'][11] , 0, -1);
            $arr[$k] = $v;
        }
        return $arr;
    }

}
?>
