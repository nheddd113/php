<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta http-equiv="X-UA-Compatible" content="IE=7" />

<title>错误提示</title>

<style type="text/css">

<!--

	*{padding:0; margin:0; font-size:12px;}div,span,b,p,td,font{font-size:14px;}

	a:link,a:visited{text-decoration:none;color:#0068a6}

	a:hover,a:active{color:#ff6600;text-decoration: underline}

	.showMsg{border: 1px solid #1e64c8; zoom:1; width:450px; height:172px;position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px}

	.showMsg h5{background-image: url(__PUBLIC__/images/msg.png);background-repeat: no-repeat; color:#fff; padding-left:35px; height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}

	.showMsg .content{ padding:46px 12px 10px 45px; margin:0 10px; font-size:14px; height:64px; text-align:left}

	.showMsg .bottom{ background:#e4ecf7; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center}

	.showMsg .ok,.showMsg .guery{background: url(__PUBLIC__/images/msg_bg.png) no-repeat 0px -560px;}

	.showMsg .guery{background-position: left -460px;}

-->

</style>

</head>

<body>



<div class="showMsg" style="text-align:center">

	<h5>提示:</h5>

    <?php if(isset($message)): ?><div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:423px"><?php echo ($message); ?></div><?php endif; ?>
    <?php if(isset($error)): ?><div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:423px"><?php echo ($error); ?></div><?php endif; ?>

    <div class="bottom">

	<a href="<?php echo ($jumpUrl); ?>">如果您的浏览器没有自动跳转，请点击这里</a>

	<script language="javascript">setTimeout("document.location.href='<?php echo ($jumpUrl); ?>';", 2000);</script>

	        </div>

</div>



</body>

</html>