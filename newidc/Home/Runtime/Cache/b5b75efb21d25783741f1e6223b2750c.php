<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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

        <link rel="stylesheet" href="__PUBLIC__/assets/css/ace.min.css" />
        <link rel="stylesheet" href="__PUBLIC__/assets/css/ace-rtl.min.css" />

        <!--[if lte IE 8]>
          <link rel="stylesheet" href="__PUBLIC__/assets/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="__PUBLIC__/assets/js/html5shiv.js"></script>
        <script src="__PUBLIC__/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
<body class="login-layout">


    <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <i class="icon-leaf green"></i>
                                    <span class="red">游奇</span>
                                    <span class="white">IDC管理系统</span>
                                </h1>
                                <h4 class="blue">&copy; UQEE</h4>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="icon-coffee green"></i>
                                                用户登陆
                                            </h4>

                                            <div class="space-6"></div>

                                            <form id="Login" action="<?php echo U('verify');?>" method="POST">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" name="userName" class="form-control" placeholder="帐号" />
                                                            <i class="icon-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" name="userPass" class="form-control" placeholder="密码" />
                                                            <i class="icon-lock"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" name="verify" class="col-xs-5" placeholder="验证码" />
                                                            <img id="code" onclick="$.refreshcode(this);" class="col-xs-4" style="height:28px;"  src="<?php echo U('createCode');?>" title="点击刷新">
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>

                                                    <div class="clearfix center">

                                                        <button type="submit" class="width-35  btn btn-sm btn-primary">
                                                            <i class="icon-key"></i>
                                                            登陆
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>

                                        </div><!-- /widget-main -->
                                    </div><!-- /widget-body -->
                                </div><!-- /login-box -->

                            </div><!-- /position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div><!-- /.main-container -->


        <!--[if !IE]> -->

        <script type="text/javascript">
            window.jQuery || document.write("<script src='__PUBLIC__/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
        <script type="text/javascript">
         window.jQuery || document.write("<script src='__PUBLIC__/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
        </script>
        <![endif]-->
        <script type="text/javascript">
            if("ontouchend" in document) document.write("<script src='__PUBLIC__/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
        </script>

        <script type="text/javascript">
            $(document).ready(function(){
                $("#code").on('click',function(){
                    $(this).attr('src',$(this).attr('src'));
                });
                $.extend({
                    refreshcode:function (obj) {
                        var t = $(obj).get(0);
                        if(t.src.indexOf('?') == -1){
                            t.src = '<?php echo U("createCode");?>' + '?t=' + Math.random();
                        }else{
                            t.src = '<?php echo U("createCode");?>' + '&t=' + Math.random();
                        }
                    }
                })

            });
        </script>


</body>
</html>