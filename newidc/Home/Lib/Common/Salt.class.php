<?php
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
    	$key = "a1092dbd86242a8ec49d4073cbb14986";
    	$sign = md5($method . $uri . $fun . $key);
    	return $sign; 
    }
    public function getRuturn(){
    	$jid = "20141202204132732153";
    	$ip = "115.239.196.161";
		$model = M('Hostlist');
		$server = $model->where(array('mainip'=>$ip))->find();
		$host = $this->getHost($server['houid']);
		$host .= '/getjobinfo';
		$params = array();
		$params['fun'] = "grains.items";
		$params['jid'] = $jid;
		$params['tgt'] = empty($server['uuid'])?$ip:$server['uuid'];
		$params['expr_form'] = empty($server['uuid'])?'ipcidr':"glob";
		$params['timeout'] = 3;
		$params['sign'] = $this->checkBaseAuth('POST','/getjobinfo',$params['fun']);
		$params = http_build_query($params);
		$ret = $this->post($host,$params);
		p($ret);
    	
    }
    /**
     * 发起POST请
     * Enter description here ...
     * @param $url
     * @param $header
     * @param $data
     * @return 返回一个数组
     */
    public function post($url,$data=null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 29);
		curl_setopt($ch, CURLOPT_TIMEOUT, 29);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$ret = curl_exec($ch);
		$info = curl_getinfo($ch);
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
    public function getUuid($ip){
    	$model = M('Hostlist');
    	$map['mainip'] = $ip;
    	if(!$host = $model->where($map)->find()){
    		return array('state'=>2,"info"=>"Ip地址错误");
    	}
    	if(in_array($ip,$this->ips)){
    		$host['houid'] = 1;   //如果是堡垒机IP. 就指定机房ID是1
    		// p($host);
    	}
    	$url = $this->getHost($host['houid']);
    	if(empty($host['uuid'])){
    		$ret = $this->findID($ip,$host['houid']);
    		if($ret == false && count($ret) == 0){
    			return array('uuid'=>'',"url",'state'=>1);
    		}
    		$uuid = array_pop($ret);
    		if(is_array($ret)){
    			$data['id'] = $host['id'];
    			$data['uuid'] = $uuid;
    			$model->data($data)->save();
    		}
    		return array('uuid'=>$uuid,'url'=>$url,'state'=>0);
    	}else{
    		return array('uuid'=>$host['uuid'],'url'=>$url,'state'=>0);
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
    public function serverOp($uuid,$houid,$fun,$arg,$ip){
        if(empty($uuid)) {
            $ret = $this->getUuid($ip);
            $uuid = $ret['uuid'];
        }
        $url = $this->getHost($houid);
        if(empty($url)) return false;
        $url .= '/cmd';
        $data = array(
            'fun' => $fun,
            'arg' => $arg,
            'tgt' => $uuid,
            );
        $data['sign'] = $this->checkBaseAuth('POST','/cmd',$data['fun']);
        $data = http_build_query($data);
        $ret = $this->post($url, $data);
        return $ret[$uuid];
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
}
?>









