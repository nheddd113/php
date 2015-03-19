<?php
$home =  array(
	//'配置项'=>'配置值'
	'LOG_RECORD'            => true,
	'SHOW_PAGE_TRACE'=>false,
	'HOST_TYPE'=>array(0=>'服务器',1=>'宿主机',2=>'虚拟机',3=>'存储器'),
	'XIA_JIA'=>array('cupid'=>0,'seatid'=>0,'mainip'=>'','subip'=>'','innip'=>'','status'=>0,'hostype'=>0,'gameid'=>0,'owner'=>0,'starttime'=>''),
	'PLACE' => array(1=>'国内',2=>'台湾',3=>'越南',4=>'韩国',5=>'日本',6=>'美国',7=>'泰国',8=>'马来西亚'),

	'LANG_SWITCH_ON' => true,   // 开启语言包功能
	'LANG_LIST'        => 'zh-cn,en-us', // 允许切换的语言列表 用逗号分隔
	'DEFAULT_LANG' => 'zh-cn', // 默认语言
);
$config = require_once('./config_inc.php');
return array_merge($home,$config);
?>