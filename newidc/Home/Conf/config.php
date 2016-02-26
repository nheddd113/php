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
    'SALT_FUN' => array(array('fun'=>'cmd.run','name'=>'执行命令行命令'),
        array('fun'=>'state.sls','name'=>'执行sls配置'),
        array('fun'=>'cp.get_file','name'=>'同步文件'),
        array('fun'=>'cp.get_dir','name'=>'同步目录'),
        array('fun'=>'uqee_info.startgame','name'=>'开启游戏'),
        array('fun'=>'uqee_info.shutgame','name'=>'关游戏'),
        array('fun'=>'uqee_info.getInfo','name'=>'获取服务器信息'),
        array('fun'=>'saltutil.sync_all','name'=>'同步自定义模块'),
        array('fun'=>'grains.get','name'=>'获取grains值'),
        array('fun'=>'grains.delval','name'=>'删除grains值'),
        array('fun'=>'grains.setval','name'=>'设置grains值'),
        array('fun'=>'uqee_chkgame.hostname','name'=>'修改主机名'),
        array('fun'=>'test.ping','name'=>'测试主机存在'),
        array('fun'=>'uqee_chkgame.checkgame','name'=>'检查服务器是否有游戏在运行'),),
);
$config = require_once('./config_inc.php');
return array_merge($home,$config);
?>
