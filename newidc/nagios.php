<?php
class nagios{
    function __construct($options){
        $this->hostfile = '/usr/local/nagios/etc/uqee/host.cfg';
        $this->groupfile = '/usr/local/nagios/etc/uqee/hostgroup.cfg';
        $this->hostPattern = '/[#|;]*define\s+host\s*\{\t*(.*\n){0,5}\s*\}/';
        $this->groupPattern = '/[#|;]*define\s+hostgroup\s*\{\s*(.*\n){0,5}\s*\}/';
        $this->options = $options;
        $this->backCfg($this->hostfile,$this->groupfile);
    }
    //增加前备份原文件
    private function backCfg($hostfile,$groupfile){
        $end = '.bak';
        system("sudo cp ". $hostfile . " " .$hostfile . $end);
        system("sudo cp ". $groupfile . " " .$groupfile . $end);
    }
    //解析host.cfg文件. 把主机都解析出来
    private function parseHostFile($filename,$pattern){
        $content = file_get_contents($filename);
        preg_match_all($pattern,$content,$matches);
        $hostList = array();
        foreach($matches[0] as $each){
            $eachHost = $this->parseHost($each);
            if ($eachHost === false) continue;
            array_push($hostList,$eachHost);
        }
        return $hostList;
    }
    //检查要增加的主机是不是已经存在
    private function checkHost($hostList,$host,$name){
        $state = false;
        foreach($hostList as $key=>$value){
            if ($host == $value['address'] || $name == $value['host_name']){
                $state = $key;
                break;
            }
        }
        return $state;
    }

    //读取输入字符
    private function read(){
        $fp = fopen("/dev/stdin","r");
        $value = fgets($fp,255);
        return trim($value);
    }
    //删除主机和主机组成员
    public function del(){
        echo "\033[32m要删除的服务器IP:\033[0m";
        $address = $this->read();
        $hostList = $this->parseHostFile($this->hostfile,$this->hostPattern);
        $index = $this->checkHost($hostList,$address,'');
        if($index === false){
            die("要删除的主机不存在\n");
        }
        $host_name = $hostList[$index]['host_name'];
        unset($hostList[$index]);
        //读取主机组文件,并转成数组
        $groupList = $this->parseHostFile($this->groupfile,$this->groupPattern);
        //解析members,转成数组
        $groupList = $this->groupParseMembers($groupList);
        foreach($groupList as $key=>$group){
            if(array_key_exists('members',$group) && in_array($host_name,$group['members'])){
                $index = array_search($host_name,$group['members']);
                if($index !== false){
                    unset($groupList[$key]['members'][$index]);
                }
            }
        }
        //将数组转换成文件写入配置.
        $this->write($hostList);
        $this->write($groupList,true);
        echo "\033[31m主机已增加,正在重启nagios,请稍等…………\n\033[0m";
        system("sudo invoke-rc.d nagios restart");
        die("\033[31m修改主机操作已完成\n\033[0m");
    }

    //增加主机
    public function add(){
        $tmp = array();
        $tmp['use'] = 'uqee-host';
        //判断是取值的两种方式
        if(!array_key_exists('type',$this->options)){  //传参式
            $tmp['host_name'] = $this->options['n'];
            $tmp['alias'] = $this->options['n'];
            $tmp['address'] = $this->options['a'];
            $groupname = $this->options['g'];
            $address = $this->options['a'];
            $name = $this->options['n'];
        }else{  //交互式
            echo "\033[32m要增加的区名:\033[0m";
            $qu = $this->read();
            echo "\033[32m要增的服务器IP:\033[0m";
            $ip = $this->read();
            $tmp['host_name'] = $qu ."_" .$ip;
            $tmp['alias'] = $tmp['host_name'];
            $tmp['address'] = $ip;
            $address = $ip;
            $name = $tmp['host_name'];
        }
        //读取主机文件,并转换成数组
        $hostList = $this->parseHostFile($this->hostfile,$this->hostPattern);
        //判断主机是不是已经在主机文件里面
        if($this->checkHost($hostList,$address,$name) !== false){
            die("\033[33m该主机地址或主机名已经存,请删除后再增加或手动修改\n\033[0m");
        }
        array_push($hostList,$tmp);
        //读取主机组文件,并转成数组
        $groupList = $this->parseHostFile($this->groupfile,$this->groupPattern);
        //解析members,转成数组
        $groupList = $this->groupParseMembers($groupList);
        $groupKeyList = array();
        if(array_key_exists('type',$this->options)){  //交互式
            foreach($groupList as $key=>$value){
                if (array_key_exists('hostgroup_members',$value)) continue;
                array_push($groupKeyList,$key);  //输出可选组的列表
                echo $key ."\t=>\t". $value['hostgroup_name'] ."\n";
            }
            echo "\033[32m请选择要增加的组:\033[0m";
            $groupIndex = $this->read();
        }else{  //传参式
            $groupIndex = false;
            $groupname = $this->options['g'];
            foreach($groupList as $key=>$value){
                if(in_array($groupname,$value)){
                    $groupIndex = $key;
                    break;
                }
            }
        }
        if(!in_array($groupIndex,$groupKeyList)){   //如果输入或传入的组编号不在提供选择的列表里时
            die("\033[33m要增加的组不存,请确认组名正确或手动增加!\n\033[0m");
        }else{
            //如果groupname没有值.则根据groupIndex取值
            $groupname = isset($groupname)?$groupname:$groupList[$groupIndex]['hostgroup_name'];
            if(!$this->checkHostInGroup($name,$groupname,$groupList)){
                array_push($groupList[$groupIndex]['members'],$name);
            }
        }
        //将数组转换成文件写入配置.
        $this->write($hostList);
        $this->write($groupList,true);
        echo "\033[31m主机已增加,正在重启nagios,请稍等…………\n\033[0m";
        system("sudo invoke-rc.d nagios restart");
        die("\033[31m增加主机操作已完成\n\033[0m");
    }
    //检查主机是不是在指定的组里.
    private function checkHostInGroup($name,$groupname,$groupList){
        $state = false;
        foreach($groupList as $key=>$value){
            if($groupname == $value['hostgroup_name']){
                if(!isset($value['members'])){
                    $value['members'] = '';
                }
                if(in_array($name,$value['members'])){
                    $state = true;
                    break;
                }
            }
        }
        return $state;
    }
    //把数据写入文件
    // param type bool: default value false
    // false == host.cfg ,true == hostgroup.cfg
    private function write($arr,$type=false){
        if($type){
            $head = "define hostgroup{\n";
        }else{
            $head = "define host{\n";
        }
        $str = '';
        foreach($arr as $key=>$value){
            $str .= $head;
            foreach($value as $k=>$v){
                $str .= "\t" .$k ;
                if($type && $k== 'members'){
                    $str .= "\t" . join(',',$v) ;
                }else{
                    $str .= "\t" . $v;
                }
                $str .= "\n";
            }
            $str .="}\n";
        }
        if($type){
            $fp = fopen($this->groupfile,"w");
        }else{
            $fp = fopen($this->hostfile,"w");
        }
        fwrite($fp,$str);
        fclose($fp);
    }


    //把group里面的members解析成数组
    private function groupParseMembers($groupList){
        foreach($groupList as $key=>$value){
            foreach($value as $k=>$v){
                if($k == 'members'){
                    $groupList[$key][$k] = explode(',',$v);
                }
            }
        }
        return $groupList;
    }

    /**
       把字符串传成数组
    */
    private function parseHost($str){
        $arr = array();
        $tmp = explode("\n",$str);
        $len = count($tmp) - 1;
        if ($len <= 0) return false;
        for($i=1;$i<$len;$i++){
            list($a,$key,$value) = preg_split("/\s+/",$tmp[$i]);
            if (in_array($a,array('#',';'))){
                $key = $a . $key;
            }
            $arr[$key] = $value;
        }
        return $arr;
    }


}

function usage($shell){
    $usage = "\nUsage: ". $shell ;
    $usage .= " [add|del] \n\tor \nUsage: " . $shell ;
    $usage .= " -n MONITOR_NAME ";
    $usage .= "-m METHOD_NAME [add|del] ";
    $usage .= "-a ADDRESS ";
    $usage .= "-g GROUP_NAME\n\n";
    echo $usage;
}
$shell = $argv[0];
$shortopts = "n:m:a:g:";
$longopts = array('help');
$options = getopt($shortopts,$longopts);

if(array_key_exists('help',$options)){
    usage($shell);
    die;
}
$m = array('add','del','chg');
if(!array_key_exists('n',$options) || !array_key_exists('a',$options) || !array_key_exists('g',$options)){
    $options['type'] = 'false';
}else{
    $options['n'] = $options['n'].'_'.$options['a'];
}

$nagios = new nagios($options);
if(isset($option['m']) || isset($argv[1])){
    $method = isset($option['m'])?$option['m']:$argv[1];
}else{
    echo "缺少操作方法\n";
    usage($shell);
    die;
}
if(in_array($method,$m)){
    $nagios->$method();
}else{
    echo "操作方法有误\n";
    usage($shell);
    die;
}
?>
