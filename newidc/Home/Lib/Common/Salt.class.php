<?php
define('NOT_RETURN_ERROR_CODE', 1);
define('ERROR_CODE_OK', 0);
define('NO_SUCH_MASTER', 2);
define('BAD_REQUEST', 3);
define('INVALID_PARAMS', 4);
class Salt extends Think{
    //允许该问的IP
    /**
     * 获取该机房对应的salt主机地址
     * Enter description here ...
     * @param int $houid 机房ID
     * @return url 该机房所对应的salt接口
     */
    public function __construct(){
        //堡垒机IP
        $this->key = "a1092dbd86242a8ec49d4073cbb14986";
        $this->ips = array('122.226.111.35','210.73.216.181','78.46.79.111','223.202.45.25','223.202.32.165',
            '118.102.7.5','122.147.184.5','218.32.57.198','27.131.146.126','211.55.34.78','1.234.11.81',
            '85.195.92.173','210.129.81.69','198.23.85.140','198.143.185.186','114.129.22.7','103.29.186.77',
            '119.81.48.106','192.95.62.131','210.242.105.143','125.212.193.18','14.192.69.116','27.254.37.151',
            '118.69.174.201','61.219.14.132');
    }
    public function getHost($houid){
        $salt = M('salt');
        $hosts = $salt->where(array('houseid'=>$houid))->find();
        $url = "http://{$hosts['host']}:{$hosts['port']}";
        return $url;
    }
    /**
     * 获取签名
     * Enter description here ...
     * @param string $method  请求方式
     * @param string $uri  请求uri
     * @param string $fun  操作方法
     * @return sign 返回签名
     */
    public function checkBaseAuth($method,$uri,$fun){
        $sign = hash_hmac('sha1',$method . $uri . $fun , $this->key);
        return base64_encode($sign);
    }

    public function verifyBaseAuth($method,$uri,$fun,$sign){
        $n_sign = base64_encode(hash_hmac('sha1',$method . $uri . $fun , $this->key));
        Log::write(print_r($n_sign,true),"n_sign","",LOG_PATH."Test_weixin".date('y_m_d').".log");
        if($n_sign == $sign){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 根据任务ID获取任务信息
     * @return [type] [description]
     */
    public function getReturn($jid = '',$houid,$tgt = ''){
        $host = $this->getHost($houid);
        $host .= '/getjobinfo';
        $params = array();
        if(!empty($jid))
            $params['jid'] = $jid;
        if(!empty($tgt)){
            $params['tgt'] = $tgt;
        }
        if(empty($params)){
            return array('status'=>INVALID_PARAMS,'msg'=>'参数有误');
        }
        // p($host);
        $params['fun'] = "";
        $params['sign'] = $this->checkBaseAuth('POST','/getjobinfo',$params['fun']);
        $params = http_build_query($params);
        $ret = $this->post($host,$params);
        if($ret === false){
            return array('status'=>BAD_REQUEST,'data'=>'');
        }
        // echo "1";
        // p($ret);
        foreach($ret as $key=>$v){
            $ret[$key]['returns'] = json_decode($v['returns'],true);
        }
        // echo "2";
        // p($ret);
        if(empty($ret)){
            return array('status'=>NOT_RETURN_ERROR_CODE,'data'=>$ret);
        }else{
            return array('status'=>ERROR_CODE_OK,'data'=>$ret);
        }

    }
    public function getMission($uuid,$houid){
        if(empty($uuid)){
            return array('status'=>INVALID_PARAMS,'msg'=>'空UUID');
        }
        $uri = "/getjids/byid";
        $host = $this->getHost($houid);
        $params = array('fun'=>'','uuid'=>$uuid);
        $host .= $uri;
        $params['sign'] = $this->checkBaseAuth('POST',$uri,$params['fun']);
        // echo $host;
        $ret = $this->post($host,http_build_query($params));
        if($ret === false){
            return array('status'=>BAD_REQUEST,'data'=>'');
        }
        if(empty($ret)){
            return array('status'=>NOT_RETURN_ERROR_CODE,'data'=>$ret);
        }else{
            return array('status'=>ERROR_CODE_OK,'data'=>$ret);
        }

    }
    /**
     * 发起POST请
     * Enter description here ...
     * @param $url
     * @param $header
     * @param $data
     * @return 返回一个数组
     */
    public function post($url,$data=null,$timeout=29){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout + 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout + 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if($info['http_code'] == 200){
            return json_decode($ret,true);
        }
        return false;
    }
    /**
     * [当服务器的UUID为空时向服务器发起请求,获取UUID]
     * @param  $ip [string]
     * @return [array]
     */
    public function getUuid($ip,$force=0){
        $model = M('Hostlist');
        $map['mainip|innip'] = $ip;
        if(!$host = $model->where($map)->find()){
            return array('status'=>2,"info"=>"Ip地址错误");
        }
        $s = D('Salt')->where(array('host'=>$ip))->find();
        if(!empty($s)){
            $host['houid'] = 1;
            if(!empty($s['server_addr']))
                $ip = $s['server_addr'];
        }
        $url = $this->getHost($host['houid']);
        if(empty($host['uuid']) || $force){
            $ret = $this->findID($ip,$host['houid']);
            if($ret == false && $ret['success'] == 0){
                return array('uuid'=>'',"url",'state'=>1);
            }
            // p($ret);
            $uuid = $ret['minions'][0]['id'];
            if(is_array($ret)){
                $data['id'] = $host['id'];
                $data['uuid'] = $uuid;
                $model->data($data)->save();
            }
            return array('uuid'=>$uuid,'url'=>$url,'state'=>0,'houid'=>$host['houid']);
        }else{
            return array('uuid'=>$host['uuid'],'url'=>$url,'state'=>0,'houid'=>$host['houid']);
        }
    }
    private function parseJson($arr){
        echo json_encode($arr);
        exit;
    }
    /**
     * [findID 使用IP获取服务器的UUID]
     * @param  [string] $ip    [服务器IP]
     * @param  [int] $houid [服务器所在机房的机房ID]
     * @return [array]        [description]
     */
    public function findID($ip,$houid){
        $host = $this->getHost($houid);
        $params = array();
        $params['fun'] = "grains.get";
        $params['arg'] = "id";
        $params['async'] = 0;
        $params['tgt'] = $ip;
        $params['expr_form'] = 'ipcidr';
        $params['timeout'] = 3;
        $host .= '/cmd';
        $params['sign'] = $this->checkBaseAuth('POST','/cmd',$params['fun']);
        $params = http_build_query($params);
        $ret = $this->post($host,$params);
        return $ret;
    }
    /**
     * 多服务器进行系统操作
     * @param  [type]  $tgt       [description]
     * @param  [type]  $fun       [description]
     * @param  [type]  $arg       [description]
     * @param  string  $expr_form [description]
     * @param  integer $is_sync   [description]
     * @param  integer $houid     [description]
     * @param  integer $timeout   [description]
     * @return [type]             [description]
     */
    public function rangeServerOp($tgt,$fun,$arg,$expr_form='glob',$is_sync=1,$houid=1,$timeout=20){
        $url = $this->getHost($houid);
        if(empty($url)) return array();
        $params = array('fun'=>$fun,'tgt'=>$tgt,
            'expr_form'=>$expr_form,'timeout'=>$timeout);
        if(!empty($arg)) $params['arg'] = $arg;
        $params['async'] = $is_sync == 1?0:1;
        $uri = '/cmd';
        $url .= $uri;
        $params['sign'] = $this->checkBaseAuth('POST',$uri,$params['fun']);
        $ret = $this->post($url,http_build_query($params));
        if($ret === false){
            return array('status'=>BAD_REQUEST,'msg'=>'请求服务器失败');
        }elseif (empty($ret)) {
            return array('status'=>NOT_RETURN_ERROR_CODE,'msg'=>'返回列表为空');
        }else{
            return $ret;
        }
    }
    /**
     * 单机服务器操作
     * @param  [type] $uuid  [description]
     * @param  [type] $houid [description]
     * @param  [type] $fun   [description]
     * @param  [type] $arg   [description]
     * @param  [type] $ip    [description]
     * @return [type]        [description]
     */
    public function serverOp($uuid,$houid,$fun,$arg=null,$timeout=20,$ip=null){
        if(empty($uuid) && !is_null($ip)) {
            $ret = $this->getUuid($ip);
            $uuid = $ret['uuid'];
            $houid = $uuid['houid'];
        }else{
            $m = D('Hostlist');
            $server = $m->where(array('uuid'=>$uuid))->find();
            $map = array();
            // $map['host|server_addr'] = array(array($server['mainip'],$server['innip']),
            //     array($server['mainip'],$server['innip']),'_multi'=>true);
            $map['_string'] = "host='{$server['mainip']}' or host='{$server['innip']}' or server_addr='{$server['mainip']}' or server_addr='{$server['innip']}'";
            $m = D('Salt');
            $s = $m->where($map)->find();
            if(!empty($s)){
                $houid=1;
            }
        }
        if(empty($uuid)) return array();
        $url = $this->getHost($houid);
        if(empty($url)) return array();
        $url .= '/cmd';
        $data = array(
            'fun' => $fun,
            'tgt' => $uuid,
            'timeout'=>$timeout,
            );
        if(!empty($arg)){
            $data['arg'] = $arg;
        }
        $data['sign'] = $this->checkBaseAuth('POST','/cmd',$data['fun']);
        $data = http_build_query($data);
        $ret = $this->post($url, $data);
        if($ret === false){
            return array('status'=>BAD_REQUEST,'msg'=>'请求服务器失败');
        }elseif (empty($ret)) {
            return array('status'=>NOT_RETURN_ERROR_CODE,'msg'=>'返回列表为空');
        }else{
            $ret['houid'] = $houid;
            return $ret;
        }
    }
    /**
     * [cleanCron 当服务器为闲置时调用该方法,删除crontab -e任务]
     * @param  [string] $uuid  [服务器salt的UUID]
     * @param  [int] $houid [服务器所在机房的机房ID]
     * @return [type]        [description]
     */
    public function cleanCron($uuid,$houid){
        if($uuid == '') return false;
        $url = $this->getHost($houid);
        $url .= '/cmd';
        $data = array("client"=>'local',"fun"=>"file.remove","tgt"=>$uuid,"arg"=>'/var/spool/cron/crontabs/root');
        $data['sign'] = $this->checkBaseAuth('POST','/cmd',$data['fun']);
        $data = http_build_query($data);
        $ret = $this->post($url, array(), $data);
        return $ret[$uuid];
        Log::write("删除计划任务结果".json_encode($ret));
    }
    public function getkey($houid){
        $host = $this->getHost($houid);
        $api = "/getkey";
        $params = array('fun'=>'all_keys');
        $params['sign'] = $this->checkBaseAuth('POST',$api,$params['fun']);
        $ret = $this->post($host . $api, http_build_query($params));
        return $ret;
    }
    /**
     * 忽略空值
     * @param  [type] $var [description]
     * @return [type]      [description]
     */
    public function checkArg($var){
        if(!empty($var)) return $var;
    }
    /**
     * salt操作时记录日志
     * @param  [type]  $data   [description]
     * @param  boolean $insert [description]
     * @return [type]          [description]
     */
    public function addlog($data,$insert=true){
        $model = D('SaltLog');
        if($insert){
            $model->create($data);
            return $model->add();
        }else{
            $model->save($data);
        }

    }
}
?>









