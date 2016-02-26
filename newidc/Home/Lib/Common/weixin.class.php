<?php
class Webchat extends Think{
    protected $corpid = 'wx0d05672167f8b48a';
    protected $secret = 'CiHPQdK4nkFNx_Dnv5w3cD7tkTpnWoDMcSwVmVj55nnyGbO88H7_myCxyONtWI8A';
    protected $getTokenUrl = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken';
    protected $access_token = '';
    public function __construct(){
        $this->getAccessToken();
    }


    public function __get($name){
        return $this->$name;
    }
    private function getAccessToken(){
        $model = D('webchat');
        $map['expires_time'] = array('gt',time());
        if(!$token = $model->where($map)->find()){
            $params = array('corpid'=>$this->corpid,'corpsecret'=>$this->secret);
            $url = $this->getTokenUrl . '?' . http_build_query($params);
            $ret = $this->makeRequest($url);
            if($ret === false || isset($ret['errcode'])){
                $this->access_token = '';
            }else{
                $this->access_token = $ret['access_token'];
                $model->add(array('access_token'=>$this->access_token,'expires_time'=>time() + 7200));
            }
        }else{
            $this->access_token = $token['access_token'];
        }
    }

    public function send($data,$partyId=null,$user=null,$agentid=1){
        $url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=" . $this->access_token;
        $params = array('toparty'=>$partyId,'msgtype'=>'text',
            'agentid'=>$agentid,'text'=>array('content'=>$data['content']),
            'touser'=>$user);
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
        while(1){
            $ret = $this->makeRequest($url,$params);
            if($ret!=false && $ret['errcode'] == 40014){
                $this->getAccessToken();
                continue;
            }else{
                break;
            }
        }

        return $ret;
    }

    private function makeRequest($url,$data=null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 29);
        curl_setopt($ch, CURLOPT_TIMEOUT, 29);
        if(!is_null($data)){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        if($info['http_code'] == 200){
            return json_decode($ret,true);
        }
        return false;
    }
}
?>
