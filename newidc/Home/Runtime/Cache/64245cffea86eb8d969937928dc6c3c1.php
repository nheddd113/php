<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>上海游奇网络服务器管理系统</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- basic styles -->

        <link href="__PUBLIC__/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="__PUBLIC__/assets/css/font-awesome.min.css" />

        <!--[if IE 7]>
          <link rel="stylesheet" href="__PUBLIC__/assets/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <!-- page specific plugin styles -->

        <!-- fonts -->

        <link rel="stylesheet" href="__PUBLIC__/assets/css/google_font.css" />

        <!-- ace styles -->
        <link rel="stylesheet" type="text/css" href="/Public/css/idc.css" />
        <link rel="stylesheet" href="__PUBLIC__/assets/css/ace.min.css" />
        <link rel="stylesheet" href="__PUBLIC__/assets/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="__PUBLIC__/assets/css/chosen.css" />
        <link rel="stylesheet" href="__PUBLIC__/assets/css/bootstrap-datetimepicker.css" />
        <link rel="stylesheet" href="__PUBLIC__/assets/css/ace-skins.min.css" />
        <!-- <link rel="stylesheet" href="__PUBLIC__/assets/css/bootstrap-datepicker.css" /> -->
        <!--[if lte IE 8]>
          <link rel="stylesheet" href="__PUBLIC__/assets/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="__PUBLIC__/assets/js/html5shiv.js"></script>
        <script src="__PUBLIC__/assets/js/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/idc.css" />
    </head>
    <body class="skin-1">
        <div class="navbar navbar-default" id="navbar">
            <script type="text/javascript">
                try{ace.settings.check('navbar' , 'fixed')}catch(e){}
            </script>

            <div class="navbar-container" id="navbar-container">
                <div class="navbar-header pull-left">
                    <a href="/index.php" class="navbar-brand">
                        <small>
                            <i class="icon-leaf"></i>
                            上海游奇网络公司-服务器管理系统
                        </small>
                    </a><!-- /.brand -->
                </div><!-- /.navbar-header -->
                <div class="navbar-header" role="navigation">
                    <ul class="nav ace-nav">
                        <li class="">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                新建列表
                            </a>
                            <ul class="dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    所有新建操作
                                </li>
                                <li>
                                    <!-- <a href="javascript:$('#createcupborad').modal('show');"> -->
                                    <a href="" data-target="#createcupborad" data-toggle="modal">
                                        <div class="clearfix">
                                            <span class="pull-left">新建机柜</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="" data-target="#createhost" data-toggle="modal">
                                        <div class="clearfix">
                                            <span class="pull-left">新建服务器</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="" data-target="#creategame" data-toggle="modal">
                                        <div class="clearfix">
                                            <span class="pull-left">新建游戏</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                查看列表
                            </a>
                            <ul class="dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    所有查看操作
                                </li>
                                <li>
                                    <a href="<?php echo U('Public/showip');?>">
                                        <div class="clearfix">
                                            <span class="pull-left">查看IP列表</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo U('Public/showHouse');?>">
                                        <div class="clearfix">
                                            <span class="pull-left">查看机房</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo U('Public/showlog');?>">
                                        <div class="clearfix">
                                            <span class="pull-left">查看日志</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo U('Search/search',array('ishost'=>1));?>">
                                        <div class="clearfix">
                                            <span class="pull-left">查看宿主机</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="" data-target="#showgame" data-toggle="modal">
                                        <div class="clearfix">
                                            <span class="pull-left">查看游戏</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo U('Create/addOps');?>">
                                        <div class="clearfix">
                                            <span class="pull-left">更新运营商</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                Salt操作
                            </a>
                            <ul class="dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    Salt操作
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Minion管理</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">子Master管理</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                        <li>
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                监控操作
                            </a>
                            <ul class="dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    监控操作
                                </li>
                                <li>
                                    <a href="<?php echo U('Monitor/index');?>">
                                        <div class="clearfix">
                                            <span class="pull-left">监控列表</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                CDN操作
                            </a>
                            <ul class="dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    CDN操作
                                </li>
                                <li>
                                    <a href="" data-target="#cdnop" data-toggle="modal">
                                        <div class="clearfix">
                                            <span class="pull-left">CDN推送</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                用户管理
                            </a>
                            <ul class="dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    用户管理
                                </li>
                                <li>
                                    <a href="<?php echo U('User/index');?>">
                                        <div class="clearfix">
                                            <span class="pull-left">用户列表</span>
                                        </div>
                                    </a>
                                </li>
                                <?php if(($_SESSION['level']) == "100"): ?><li>
                                    <a href="<?php echo U('Public/sshlog');?>">
                                        <div class="clearfix">
                                            <span class="pull-left">服务器登陆日志</span>
                                        </div>
                                    </a>
                                </li><?php endif; ?>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">
                        <li>
                            <div class="white">
                                当前状态:<span style="color:yellow"><?php echo ($notify?'开启':'关闭'); ?>
                                </span>

                                <a class="" href="<?php echo U(APP_NAME . '/Public/changeNotify',array('state'=>$notify));?>" data-rel="tooltip" data-placement="right" title="点击修改状态"><span class="" style="color:#fff">|<?php echo ($notify?'关闭':'开启'); ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                </span>
                                </a>

                            </div>
                        </li>
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <img class="nav-user-photo" src="__PUBLIC__/assets/avatars/user.jpg" alt="Jason's Photo" />
                                <span class="user-info">
                                    <small>欢迎光临,</small>
                                    <?php echo (session('realname')); ?>
                                </span>

                                <i class="icon-caret-down"></i>
                            </a>

                            <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <li>
                                    <a href="" data-target="#changeuserinfo" data-toggle="modal">
                                        <i class="icon-cog"></i>
                                        设置
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="__APP__/Login/logout">
                                        <i class="icon-off"></i>
                                        退出
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- /.ace-nav -->
                </div><!-- /.navbar-header -->
            </div><!-- /.container -->
        </div>
        <div class="modal fade" id="cdnop" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h2 class="modal-title">CDN自助推送</h2>
                    </div>
                    <div class="modal-body">
                        <form class="form" role="form" method="POST" action="<?php echo U('Public/sendContent');?>">
                            <div>
                                <div class="col-sm-4 text-center">
                                    <span>推送目标:</span>
                                </div>
                                <div class="checkbox=inline form-group col-sm-8">
                                    <div class="col-sm-6 text-center">
                                        <label class="col-sm-12">
                                            <input type="radio" name="ops" value="lx">
                                            <span>S2</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-6 text-center">
                                        <label class="col-sm-12">
                                            <input type="radio" name="ops" checked value="ws">
                                            <span>S1</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="col-sm-4 text-center">
                                    <span>推送类型:</span>
                                </div>
                                <div class="checkbox=inline form-group col-sm-8">
                                    <div class="col-sm-6 text-center">
                                        <label class="col-sm-12">
                                            <input type="radio" name="ctype" checked value="url">
                                            <span>地址</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-6 text-center">
                                        <label class="col-sm-12">
                                            <input type="radio" name="ctype" value="dir">
                                            <span>目录</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>

                                <div class="row text-center">
                                    <textarea cols="60" name="content" rows="10"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row text-center">
                                <button class="btn btn-success" type="button" id="tuisong">
                                    推送
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="type" data-dismiss="modal" class="btn btn-primary">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="noticediv" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h2 class="modal-title">通知</h2>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="type" data-dismiss="modal" class="btn btn-primary">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="creategame" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h2 class="modal-title">增加游戏</h2>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" id="addGameform" action="<?php echo U('Create/addGameHandle');?>" method="POST">
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">游戏名称: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="text" name="name" value="" class="col-sm-9">
                                    <input type="hidden" name="id"/>
                                    <span class="col-sm-2 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">游戏别名: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="text" name="alias" value="" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 text-right">
                                <button type="submit" class="btn btn-primary">保存</button>
                                </div><div class="col-sm-6 text-left">
                                <button type="reset" class="btn btn-default">重填</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="type" data-dismiss="modal" class="btn btn-primary">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="changeuserinfo" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h2 class="modal-title">修改密码</h2>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" onsubmit="return false" id="changeuserinfoform" action="<?php echo U('User/managerHandle');?>" method="POST">
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">用户名: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="text" name="loginname" value="<?php echo (session('username')); ?>" readonly class="col-sm-9">
                                    <input type="hidden" name="id" value="<?php echo (session('userid')); ?>"/>
                                    <span class="col-sm-2 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">原密码: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="password" name="oldpassword" value="" autoComplete="off" placeholder="旧密码" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">新密码: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="password" name="password" value="" placeholder="新密码" autoComplete="off" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">确认密码: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="password" name="password1" value="" placeholder="确认新密码" autoComplete="off" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 text-right">
                                <button type="submit" class="btn btn-primary">保存</button>
                                </div><div class="col-sm-6 text-left">
                                <button type="reset" class="btn btn-default">重填</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="type" data-dismiss="modal" class="btn btn-primary">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showgame" tabindex="-2" role="dialog" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h2 class="modal-title">查看游戏</h2>
                    </div>
                    <div class="modal-body">
                        <table id="game-table"  class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>游戏ID</th>
                                    <th>游戏名称</th>
                                    <th>游戏别名</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(is_array($games)): $i = 0; $__LIST__ = $games;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                        <td>
                                            <?php echo ($vo["id"]); ?>
                                        </td>
                                        <td><?php echo ($vo["name"]); ?></td>
                                        <td><?php echo ($vo["alias"]); ?></td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                <a data-rel="tooltip" data-placement="top" sid="<?php echo ($vo["id"]); ?>" data-target="modifygame" title="修改" class="green" href="javascript:void(0)">
                                                    <i class="icon-pencil bigger-130"></i>
                                                </a>
                                                <a data-rel="tooltip" data-placement="top" data-target="deletegame" url="<?php echo U('Public/deletegame');?>" sid="<?php echo ($vo["id"]); ?>" title="删除" class="red" href="javascript:void(0)">
                                                    <i class="icon-trash bigger-130"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="type" data-dismiss="modal" class="btn btn-primary">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createcupborad" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h2 class="modal-title">增加机柜</h2>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" id="addcupboradform" action="<?php echo U('Create/addCupboardHandle');?>" method="POST">
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">机柜名称: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="text" name="cupname" value="" class="col-sm-9">
                                    <input type="hidden" name="id"/>
                                    <span class="col-sm-2 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">机位总数: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="text" name="seatnum" value="" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">所属机房: </label>
                                <div class="col-sm-9 form-inner">
                                    <select name="houid" class="col-sm-9">
                                        <option></option>
                                        <?php if(is_array($house)): $i = 0; $__LIST__ = $house;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["houname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <span class="col-sm-3 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label text-right">机柜价格: </label>
                                <div class="col-sm-9 form-inner">
                                    <input type="text" name="price" value="" class="col-sm-9" placeholder="请填写机柜价格, 不如知道价格请填0.">
                                    <span class="col-sm-3 red">必填</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 text-right">
                                <button type="submit" class="btn btn-primary">保存</button>
                                </div><div class="col-sm-6 text-left">
                                <button type="reset" class="btn btn-default">重填</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="type" data-dismiss="modal" class="btn btn-primary">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="createhost" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog " style="width:60%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h2 class="modal-title">增加主机</h2>
                    </div>
                    <div class="modal-body">
                        <div class="text-center" style="padding-bottom:30px;">
                            <?php if(is_array($template)): $i = 0; $__LIST__ = $template;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><button type="button" value="<?php echo ($vo["id"]); ?>" data-click="selecttemplate" class="btn btn-info"><?php echo ($vo["name"]); ?></button>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <form class="form-horizontal" role="form" id="createhostform" action="<?php echo U('Create/addHostHandle');?>" method="POST">
                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">资产编码: </label>
                                <div class="col-md-4 form-inner">
                                    <input type="text" name="hostid" value="" class="col-sm-9">
                                    <input type="hidden" name="templateid" value="">
                                    <span class="col-sm-3 red">必填</span>
                                 </div>
                                 <label class="col-sm-2 control-label text-right">服务编码: </label>
                                <div class="col-md-4 form-inner">
                                    <input type="text" name="sertag" value="" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                 </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">所属机房: </label>
                                <div class="col-md-4 form-inner">
                                    <select name="houid" class="col-sm-9">
                                        <option value=""></option>
                                        <?php if(is_array($house)): $i = 0; $__LIST__ = $house;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["houname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <span class="col-sm-3 red">必填</span>
                                 </div>
                                 <label class="col-sm-2 control-label text-right">CPU: </label>
                                <div class="col-md-4 form-inner">
                                    <input type="text" name="cpu" value="" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                 </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">硬盘: </label>
                                <div class="col-md-4 form-inner">
                                    <input type="text" name="disk" value="" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                 </div>
                                 <label class="col-sm-2 control-label text-right">内存: </label>
                                <div class="col-md-4 form-inner">
                                    <input type="text" name="mem" value="" class="col-sm-9">
                                    <span class="col-sm-3 red">必填</span>
                                 </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">宿主机: </label>
                                <div class="col-md-4 form-inner">
                                    <label>
                                        <input name="ishost" class="ace ace-switch ace-switch-5" type="checkbox" />
                                        <span class="lbl"></span>
                                    </label>
                                 </div>
                                 <label class="col-sm-2 control-label text-right">托管: </label>
                                <div class="col-md-4 form-inner">
                                    <label>
                                        <input name="ismanager"  class="ace ace-switch ace-switch-5" type="checkbox"  />
                                        <span class="lbl"></span>
                                    </label>
                                 </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 text-right">
                                <button type="submit" class="btn btn-primary">增加</button>
                                </div><div class="col-sm-6 text-left">
                                <button type="reset" class="btn btn-default">重填</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="type" data-dismiss="modal" class="btn btn-primary">关闭</button>
                    </div>
                </div>
            </div>
        </div>

    <div class="main-container" id="main-container">
        <script type="text/javascript">
            try{ace.settings.check('main-container' , 'fixed')}catch(e){}
        </script>
        <div class="main-container">
            <div class="main-container-inner">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>
                                <div class="sidebar" id="sidebar">
                    <script type="text/javascript">
                        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
                    </script>

                    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                            <h1 class="page_header white">机房</h1>
                        </div>

                        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                            <span class="btn btn-success"></span>
                        </div>
                    </div>

                    <!-- #sidebar-shortcuts -->
                    <!-- 机房机柜 -->
                    <ul class="nav nav-list">
                        <!-- <?php echo p($house);?> -->
                        <?php if(is_array($house)): $i = 0; $__LIST__ = $house;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if(($_GET['houid']) == $key): ?>class="open"<?php endif; ?>>
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-desktop"></i>
                                <span class="menu-text"> <?php echo ($vo["houname"]); ?> </span>

                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu" <?php if(($_GET['houid']) == $key): ?>style="display:block"<?php endif; ?>>
                                <?php if(is_array($cupborad[$key])): $i = 0; $__LIST__ = $cupborad[$key];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if(($_GET['cupid']) == $vo["id"]): ?>class="active"<?php endif; ?>>
                                    <a href="<?php echo U('Search/search',array('houid'=>$vo['houid'],'cupid'=>$vo['id']));?>">
                                        <i class="icon-double-angle-right"></i>
                                        <?php echo ($vo["cupname"]); ?>
                                    </a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul><!-- /.nav-list -->

                    <div class="sidebar-collapse" id="sidebar-collapse">
                        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
                    </div>

                    <script type="text/javascript">
                        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
                    </script>
                </div>

                <div class="main-content">
                    <div class="breadcrumbs" id="breadcrumbs">
                        <script type="text/javascript">
                            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                        </script>

                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home home-icon"></i>
                                <a href="/index.php">首页</a>
                            </li>
                            <li class="active">首页</li>
                        </ul><!-- .breadcrumb -->

                        <div class="nav-search" id="nav-search">
                            <form class="form-search" id="search" method="post" action="<?php echo U('Search/postHandle');?>">
                                <div class="input-group">
                                    <span class="input-icon">
                                        <select name="query_type" class="nav-search-input">
                                            <option <?php if(($query_type) == "mainip"): ?>selected<?php endif; ?> value="mainip">服务器IP</option>
                                            <option <?php if(($query_type) == "sertag"): ?>selected<?php endif; ?> value="sertag">服务编码</option>
                                            <option <?php if(($query_type) == "hostid"): ?>selected<?php endif; ?> value="hostid">资产编码</option>
                                            <option <?php if(($query_type) == "remark"): ?>selected<?php endif; ?> value="remark">备注</option>
                                            <option <?php if(($query_type) == "log"): ?>selected<?php endif; ?> value="log">日志</option>
                                        </select>
                                    </span>
                                    <span class="input-icon">
                                        <input type="text" name="query" value="<?php echo ($query); ?>" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                        <i class="icon-search nav-search-icon"></i>
                                    </span>
                                    <span class="input-icon">
                                        <a href="javascript:$('#search').submit()"><i class="icon-zoom-in bigger-180"></i></a>
                                    </span>
                                </div>
                            </form>
                        </div><!-- #nav-search -->
                    </div>
                    <div class="page-content">
                        <div class="row">
                            <div>
                                <?php if(is_array($count)): $k = 0; $__LIST__ = $count;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="col-md-2 col-sm-12 col-xs-12">
                                            <a href="<?php echo ($v["2"]); ?>">
                                                <div class="panel panel-primary text-center no-boder bg-color-green">
                                                    <div class="panel-header">
                                                        <?php if(($k) == "1"): ?>自有<?php else: ?>非自有<?php endif; ?>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h5><?php echo ($v["1"]); ?>台</h5>
                                                    </div>
                                                    <div class="panel-footer back-footer-green">
                                                        <?php echo L($v[0]);?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-box">
                                    <div class="widget-header widget-header-flat widget-header-small">
                                        <h5>
                                            <i class="icon-signal"></i>
                                            自有服务器分布
                                        </h5>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div id="piechart-placeholder"></div>

                                            <div class="hr hr8 hr-double"></div>

                                            <div class="clearfix">
                                                <?php if(is_array($count['自有'])): $i = 0; $__LIST__ = $count['自有'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="grid3">
                                                        <span class="grey">
                                                            <?php echo L($vo[0]);?>
                                                        </span>
                                                        <h4 class="bigger pull-right"><?php echo ($vo["1"]); ?></h4>
                                                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </div>
                                        </div><!-- /widget-main -->
                                    </div><!-- /widget-body -->
                                </div>

                            <!-- /widget-box -->
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-box">
                                    <div class="widget-header widget-header-flat widget-header-small">
                                        <h5>
                                            <i class="icon-signal"></i>
                                            非自有服务器分布
                                        </h5>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div id="piechart-placeholder1"></div>

                                            <div class="hr hr8 hr-double"></div>

                                            <div class="clearfix">
                                                <?php if(is_array($count['非自有'])): $i = 0; $__LIST__ = $count['非自有'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="grid3">
                                                        <span class="grey">
                                                            <?php echo L($vo[0]);?>
                                                        </span>
                                                        <h4 class="bigger pull-right"><?php echo ($vo["1"]); ?></h4>
                                                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </div>
                                        </div><!-- /widget-main -->
                                    </div><!-- /widget-body -->
                                </div>

                            <!-- /widget-box -->
                            </div>

                            <div class="col-sm-12">
                                <div class="widget-box">
                                    <form id="getAmount">
                                        <div class="widget-header widget-header-flat widget-header-small">
                                            <h5>
                                                <i class="icon-signal"></i>
                                                费用
                                            </h5>
                                            <div class="widget-toolbar no-border col-sm-8">
                                                <div class="input-append date form_datetime col-sm-3">
                                                    <input value="<?php echo ($searchstart); ?>" class="col-sm-10" data-date="2012-12-06" type="text" name="start" />
                                                    <span class="add-on"><i class="icon-th"></i></span>
                                                </div>
                                                <div class="input-append date form_datetime col-sm-3">
                                                    <input value="<?php echo ($searchend); ?>"  class="col-sm-10" data-date="2012-12-02" type="text" name="end" />
                                                    <span class="add-on"><i class="icon-th"></i></span>


                                                </div>

                                                <div class="col-sm-2">
                                                    <select name="" id="objlist" class="col-sm-12">
                                                        <option value="1">项目费用比例图</option>
                                                        <option value="2">每日项目费用</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <select name="" id="costType" class="col-sm-12">
                                                        <option value="1">包含服务器折旧费</option>
                                                        <option value="2">不包含服务器折旧费</option>
                                                        <option value="3">服务器折旧费</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button class="btn btn-minier btn-primary" type="button" id="search" onclick="$.autoSearch();" data-type="1">
                                                        查询
                                                        <i class="icon-angle-down icon-on-right bigger-110"></i>
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div id="containerimg"></div>

                                            <div class="hr hr8 hr-double"></div>

                                            <div class="clearfix">
                                                    <div class="grid3">
                                                        <span class="grey">
                                                        </span>
                                                        <h4 class="bigger pull-right"></h4>
                                                    </div>
                                            </div>
                                        </div><!-- /widget-main -->
                                    </div><!-- /widget-body -->
                                </div>

                            <!-- /widget-box -->
                            </div>



                        </div>
                        <!--#统计完成-->
                    </div>
                </div>
            </div>
        </div>
    </div>



        <script src="__PUBLIC__/assets/js/ace-extra.min.js"></script>

        <!--[if !IE]> -->

        <script type="text/javascript">
            window.jQuery || document.write("<script src='__PUBLIC__/assets/js/jquery-2.0.3.min.js'>"+"<"+"script>");
        </script>

        <!-- <![endif]-->
        <!--[if IE]>
        <script type="text/javascript">
         window.jQuery || document.write("<script src='__PUBLIC__/assets/js/jquery-1.10.2.min.js'>"+"<"+"script>");
        </script>
        <![endif]-->
        <script type="text/javascript">
            if("ontouchend" in document) document.write("<script src='__PUBLIC__/assets/js/jquery.mobile.custom.min.js'>"+"<"+"script>");
        </script>
        <script src="__PUBLIC__/assets/js/bootstrap.min.js"></script>
        <script src="__PUBLIC__/assets/js/typeahead-bs2.min.js"></script>
        <script src="__PUBLIC__/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="__PUBLIC__/assets/js/jquery.ui.touch-punch.min.js"></script>
        <script src="__PUBLIC__/assets/js/jquery.slimscroll.min.js"></script>
        <script src="__PUBLIC__/assets/js/jquery.easy-pie-chart.min.js"></script>
        <script src="__PUBLIC__/assets/js/jquery.sparkline.min.js"></script>
        <script src="__PUBLIC__/assets/js/flot/jquery.flot.min.js"></script>
        <script src="__PUBLIC__/assets/js/flot/jquery.flot.pie.min.js"></script>
        <script src="__PUBLIC__/assets/js/flot/jquery.flot.resize.min.js"></script>
        <script src="__PUBLIC__/assets/js/ace-elements.min.js"></script>
        <script src="__PUBLIC__/assets/js/ace.min.js"></script>
        <script src="__PUBLIC__/assets/js/jquery.dataTables.js"></script>
        <script src="__PUBLIC__/assets/js/jquery.dataTables.bootstrap.js"></script>
        <script src="__PUBLIC__/assets/js/date-time/bootstrap-datepicker.js"></script>
        <script src="__PUBLIC__/assets/js/date-time/moment.min.js"></script>
        <script src="__PUBLIC__/assets/js/date-time/daterangepicker.min.js"></script>
        <script src="__PUBLIC__/assets/js/date-time/bootstrap-datetimepicker.js"></script>
        <script src="__PUBLIC__/assets/js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="__PUBLIC__/js/chart/js/highcharts.js"></script>
        <script type="text/javascript" src="__PUBLIC__/js/chart/js/modules/exporting.js"></script>

        <script>
            $(document).ready(function(){
                $("#tuisong").on('click',function(){
                    var url = $("#cdnop form").attr('action');
                    var data = $("#cdnop form").serialize();
                    $.post(url,data,function(ret){
                        $("#noticediv .modal-title").text(ret.info);
                        $("#noticediv .modal-body").html(ret.info);
                        $("#cdnop").modal('hide');
                        $("#noticediv").modal('show');
                    },'json');
                });
                $("#addGameform").on('submit',function(){
                    var url = this.action;
                    var data = $(this).serialize();
                    $.post(url,data,function(ret){
                        $("#noticediv .modal-title").text(ret.info);
                        $("#noticediv .modal-body").html(ret.data);
                        $("#creategame").modal('hide');
                        $("#noticediv").modal('show');
                        $("#noticediv").on('hide.bs.modal',function(){
                            if(ret.status == 1){
                                location.reload();
                            }
                        });

                    },'json');
                    return false;
                });
                // 修改游戏
                $("[data-target=modifygame]").on('click',function(){
                    var url = "<?php echo U('Public/showGame');?>";
                    var id = $(this).attr('sid');
                    $.post(url,{id:id},function(ret){
                        if(ret.status != 1){
                            $("#noticediv .modal-title").text(ret.info);
                            $("#noticediv .modal-body").html(ret.data);
                            $("#showgame").modal('hide');
                            $("#noticediv").modal('show');
                        }else{
                            var form = $("#creategame form").get(0);
                            form.action = "<?php echo U('Public/amendGame');?>";
                            form.name.value = ret.data.name;
                            form.alias.value =  ret.data.alias;
                            form.id.value = ret.data.id;
                            $("#showgame").modal('hide');
                            $("#creategame").modal();
                        }
                    },'json');
                });
                $("[data-target=deletegame]").on('click',function(){
                    var url = $(this).attr('url');
                    var id = $(this).attr('sid');
                    if(confirm('确定要删除数据吗?')){
                        $.post(url,{id:id},function(ret){
                            $("#noticediv .modal-title").text(ret.info);
                            $("#noticediv .modal-body").html(ret.data);
                            $("#showgame").modal('hide');
                            $("#noticediv").modal('show');
                            $("#noticediv").on('hide.bs.modal',function(){
                                if(ret.status == 1){
                                    location.reload();
                                }
                            })
                        },'json');
                    }
                })
                $("#addcupboradform :submit").on('click',function(){
                    var form = $("#addcupboradform").get(0);
                    var url = form.action;
                    var data = $(form).serialize();
                    $.post(url,data,function(ret){
                        $("#noticediv .modal-title").text(ret.info);
                        $("#noticediv .modal-body").html(ret.data);
                        $("#createcupborad").modal('hide');
                        $("#noticediv").modal('show');
                        $("#noticediv").on('hide.bs.modal',function(){
                            if(ret.status == 1){
                                location.reload();
                            }
                        })
                    },'json');
                    return false;
                });

                $("[data-click=selecttemplate]").click(function(){
                    var form = $("#createhostform").get(0);
                    var id = $(this).val();
                    var url = "<?php echo U('Ajax/getTempInfo');?>";
                    $.post(url,{id:id},function(ret){
                        if(ret.status == 1){
                            form.cpu.value = ret.data.cpu;
                            form.mem.value = ret.data.mem+'G';
                            form.disk.value = ret.data.disk + 'G';
                            form.templateid.value = ret.data.id;
                        }
                    },'json');
                });
                $("#createhostform :submit").click(function(){
                    var form = $("#createhostform").get(0);
                    var message = '';
                    var state = true;
                    if(form.hostid.value.length == 0){
                        message += '资产编码不能为空.<br>';
                        state = false;
                    }
                    if(form.sertag.value.length == 0){
                        message += '服务编码不能为空.<br>';
                        state = false;
                    }
                    if(form.houid.value.length == 0){
                        message += '机房不能为空.<br>';
                        state = false;
                    }
                    if(form.disk.value.length == 0){
                        message += '硬盘不能为空.<br>';
                        state = false;
                    }
                    if(form.mem.value.length == 0){
                        message += '内存不能为空.<br>';
                        state = false;
                    }
                    if(form.cpu.value.length == 0){
                        message += 'CPU不能为空.<br>';
                        state = false;
                    }
                    if(state == false){
                        $("#noticediv .modal-title").html("警告");
                        $("#noticediv .modal-body").html(message);
                        $("#createhost").modal('hide');
                        $("#noticediv").modal('show');
                        return false;
                    }
                    var data = $(form).serialize();
                    $.post(form.action,data,function(ret){
                        $("#noticediv .modal-title").html(ret.info);
                        $("#noticediv .modal-body").html(ret.data);
                        $("#createhost").modal('hide');
                        $("#noticediv").modal('show');
                        $("#noticediv").on('hide.bs.modal',function(){
                            if(ret.status == 1){
                                location.reload();
                            }
                        });

                    },'json');
                    return false;
                });
                $("#changeuserinfoform :submit").click(function(){
                    var form = $("#changeuserinfoform").get(0);
                    var message = '';
                    var state = true;
                    if(form.loginname.value.length == 0){
                        message += '登陆名不能为空.<br>';
                        state = false;
                    }
                    if(form.oldpassword.value.length == 0){
                        message += '旧密码不能为空.<br>';
                        state = false;
                    }
                    if(form.password.value.length == 0){
                        message += '新密码不能为空.<br>';
                        state = false;
                    }
                    if(form.password1.value.length == 0){
                        message += '确认新密码不能为空<br>';
                        state = false;
                    }
                    if(state == false){
                        $("#noticediv .modal-title").html("警告");
                        $("#noticediv .modal-body").html(message);
                        $("#noticediv").modal('show');
                        return state;
                    }
                    var data = $(form).serialize();
                    $.post(form.action,data,function(ret){
                        $("#noticediv .modal-title").html(ret.info);
                        $("#noticediv .modal-body").html(ret.data);
                        $("#noticediv").modal('show');
                        $("#changeuserinfo").modal('hide');
                        $("#noticediv").on('hide.bs.modal',function(){
                            if(ret.status == 1){
                                location.href = '<?php echo U("Login/logout");?>';
                            }
                        });
                    },'json');
                    return false;
                });
            });
        </script>
</body>
</html>


<script>
$(document).ready(function(){
    $.fn.datetimepicker.dates['zh-CN'] = {
        days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
        daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
        daysMin:  ["日", "一", "二", "三", "四", "五", "六", "日"],
        months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        today: "今天",
        suffix: [],
        meridiem: ["上午", "下午"]
    };
    $(".form_datetime ").datetimepicker({
        format: "yyyy-mm-dd",
        pickerPosition: "top-left",
        autoclose: true,
        language: "zh-CN",
        todayBtn: true,
        todayHighlight: true,
        minuteStep: 1,
        minView: 2,
    });
    // 打开自动请求
    $.extend({
        autoSearch:function(){
            var params = new Object();
            params['type'] = $("#objlist").val();
            params['costtype'] = $("#costType").val();
            params['start'] = $("#getAmount input[name=start]").val();
            params['end'] = $("#getAmount input[name=end]").val();
            getAmountUrl = "<?php echo U(APP_NAME . '/Ajax/getAmountCount');?>";
            $.post(getAmountUrl,params,function(ret){
                if(ret.status == 0){
                    $("#containerimg").html("<h1 class='text-center'>暂无数据</h1>");
                    return false;
                }
                newData = ret.data.data;
                amountdata = new Array();

                $("#containerimg").css({"height":"600px"});
                if(params['type'] == 1){
                    var j=0;
                    for(i in newData){
                        amountdata.push([i,newData[i].rmb]);
                        j++;
                    }
                    $('#containerimg').highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false
                        },
                        title: {
                            text: ret.info+ ' IDC费用比例图'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>' +
                                    '金额:<b>{point.y} 元</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                size:'75%',
                                dataLabels: {
                                    enabled: true,
                                    color: '#000000',
                                    connectorColor: '#000000',
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: '费用比',
                            data: amountdata
                        }]
                    });
                }else if(params['type'] == 2){
                    var j=0;
                    for(i in newData){
                        amountdata.push(newData[i]);
                        j++;
                    }
                    chart = new Highcharts.Chart({
                        chart: {
                            renderTo: 'containerimg',
                            zoomType: 'x'  //******  这句是实现局部放大的关键处  ******
                        },
                        title: {
                            text: ret.info+' 每日费用',
                            x: -20 //center
                        },
                        subtitle: {
                            text: '来自: idc.uqee.com',
                            x: -20
                        },
                        xAxis: {
                            categories: ret.data.time
                        },
                        yAxis: {
                            title: {
                                text: '人民币 (￥)'
                            },
                            plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                        },
                        tooltip: {
                            valueSuffix: '元'
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            borderWidth: 0
                        },
                        series: amountdata
                    });
                }

            },'json');
        },
    });
    $.autoSearch();
});
var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'300px'});
var data = [
    <?php if(is_array($count['自有'])): $i = 0; $__LIST__ = $count['自有'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>{ label: "<?php echo L($vo[0]);?>",data: "<?php echo ($vo["1"]); ?>"},<?php endforeach; endif; else: echo "" ;endif; ?>
    // { label: "social networks",  data: 38.7, },
    // { label: "search engines",  data: 24.5,},
    // { label: "ad campaigns",  data: 8.2, },
    // { label: "direct traffic",  data: 18.6, },
    // { label: "other",  data: 10, }
  ]
function drawPieChart(placeholder, data, position) {
  $.plot(placeholder, data, {
    series: {
        pie: {
            show: true,
            tilt:0.8,
            highlight: {
                opacity: 0.25
            },
            stroke: {
                color: '#fff',
                width: 2
            },
            startAngle: 2
        }
    },
    legend: {
        show: true,
        position: position || "ne",
        labelBoxBorderColor: null,
        margin:[-30,15]
    }
    ,
    grid: {
        hoverable: true,
        clickable: true
    }
 })
}
drawPieChart(placeholder, data);
placeholder.data('chart', data);
placeholder.data('draw', drawPieChart);
var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
var previousPoint = null;

placeholder.on('plothover', function (event, pos, item) {
if(item) {
    if (previousPoint != item.seriesIndex) {
        previousPoint = item.seriesIndex;
        var tip = item.series['label'] + " : " + item.series['percent'].toFixed(2)+'%';
        $tooltip.show().children(0).text(tip);
    }
    $tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
} else {
    $tooltip.hide();
    previousPoint = null;
}

});



// 第二个

var placeholder1 = $('#piechart-placeholder1').css({'width':'90%' , 'min-height':'300px'});
var data1 = [
    <?php if(is_array($count['非自有'])): $i = 0; $__LIST__ = $count['非自有'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>{ label: "<?php echo L($vo[0]);?>",data: "<?php echo ($vo["1"]); ?>"},<?php endforeach; endif; else: echo "" ;endif; ?>
  ]
function drawPieChart1(placeholder, data, position) {
  $.plot(placeholder1, data1, {
    series: {
        pie: {
            show: true,
            tilt:0.8,
            highlight: {
                opacity: 0.25
            },
            stroke: {
                color: '#fff',
                width: 2
            },
            startAngle: 2
        }
    },
    legend: {
        show: true,
        position: position || "ne",
        labelBoxBorderColor: null,
        margin:[-30,15]
    }
    ,
    grid: {
        hoverable: true,
        clickable: true
    }
 })
}
drawPieChart1(placeholder1, data);
placeholder1.data('chart', data1);
placeholder1.data('draw', drawPieChart1);
var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
var previousPoint = null;

placeholder1.on('plothover', function (event, pos, item) {
if(item) {
    if (previousPoint != item.seriesIndex) {
        previousPoint = item.seriesIndex;
        var tip = item.series['label'] + " : " + item.series['percent'].toFixed(2)+'%';
        $tooltip.show().children(0).text(tip);
    }
    $tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
} else {
    $tooltip.hide();
    previousPoint = null;
}

});
</script>