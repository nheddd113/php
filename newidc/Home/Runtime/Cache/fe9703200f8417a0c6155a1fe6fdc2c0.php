<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title>管理员登陆</title>
<link href="__PUBLIC__/style/login.css" type=text/css rel=stylesheet></link>
<link href="__PUBLIC__/style/newhead.css" type=text/css rel=stylesheet></link>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/login.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/Base.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/prototype.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/mootools.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ThinkAjax.js"></script>
<script type="text/javascript">
var verifyUrl = "<?php echo U('Home/Login/verify');?>";
function complete(data,status){
	alert(status);
	if(status == 0){
		alert(data);
	}
}
</script>
</head>
<body bgcolor=#eef8e0 > 
<form method="post" action="__URL__/verify" name="LoginForm" id="login" onsubmit="return CheckData();">
<table cellspacing=0 cellpadding=0 width=1004 border=0 align="center" style="margin:0 auto;">
  <tbody>
    <tr>
      <td colSpan=6><IMG height=92 alt="1" src="__PUBLIC__/images/crm_1.gif" 
    width=345></td>
      <td colSpan=4><IMG height=92 alt="" src="__PUBLIC__/images/crm_2.gif" 
    width=452></td>
      <td><IMG height=92 alt="" src="__PUBLIC__/images/crm_3.gif" width=207></td>
    </tr>
    <tr>
      <td colSpan=6><IMG height=98 alt="" src="__PUBLIC__/images/crm_4.gif" 
    width=345></td>
      <td colSpan=4><IMG height=98 alt="" src="__PUBLIC__/images/crm_5.gif" 
    width=452></td>
      <td><IMG height=98 alt="" src="__PUBLIC__/images/crm_6.gif" width=207></td>
    </tr>
    <tr>
      <td rowSpan=5><IMG height=370 alt="" src="__PUBLIC__/images/crm_7.gif" 
    width=59></td>
      <td colSpan=5><IMG height=80 alt="" src="__PUBLIC__/images/crm_8.gif" 
    width=286></td>
      <td colSpan=4><IMG height=80 alt="" src="__PUBLIC__/images/crm_9.gif" 
    width=452></td>
      <td><IMG height=80 alt="" src="__PUBLIC__/images/crm_10.gif" width="207"></td>
    </tr>
    <tr>
      <td><IMG height=110 alt="" src="__PUBLIC__/images/crm_11.gif" width="127"></td>
      <td background=__PUBLIC__/images/crm_12.gif colSpan=6><table id=table1 cellSpacing=0 cellPadding=0 width="98%" border=0>
          <tbody>
            <tr>
              <td><table id=table2 cellSpacing=1 cellPadding=0 width="100%" 
              border=0>
                  <tbody>
                  <tr>
                  <td  width=81><FONT color=#ffffff>用户名：</FONT></td>
                  <td><input type="text" name="userName" maxlength="16" value="" id="userName" style="width:132px"/></td>
                  </tr>
                  
                  <tr>
                    <td  width=81><FONT color=#ffffff>密&nbsp; 
                      码：</FONT></td>
                    <td><input type="password" name="userPass" maxlength="16" id="userPass" style="width:132px"/></td>
                  </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
      <td colSpan=2 rowSpan=2><IMG height=158 alt="" 
      src="__PUBLIC__/images/crm_13.gif" width=295></td>
      <td rowSpan=2><IMG height=158 alt="" src="__PUBLIC__/images/crm_14.gif" 
      width=207></td>
    </tr>
    <tr>
      <td rowSpan=3><IMG height=180 alt="" src="__PUBLIC__/images/crm_15.gif" 
      width=127></td>
      <td rowSpan=3><IMG height=180 alt="" src="__PUBLIC__/images/crm_16.gif" 
    width=24></td>
      <td>
      	<INPUT title=登录后台 type=image height=48 alt="" width=86 src="__PUBLIC__/images/crm_17.gif" name=image>
      </td>
      <td>
      	<IMG height=48 alt="" src="__PUBLIC__/images/crm_18.gif" width=21>
      </td>
      <td colSpan=2><A href="#" onClick="concelCli()">
      	<IMG title=返回首页 height=48 alt="" src="__PUBLIC__/images/crm_19.gif" width=84 border=0></A>
      </td>
      <td><IMG height=48 alt="" src="__PUBLIC__/images/crm_20.gif" width=101></td>
    </tr>
    <tr>
      <td colSpan=5 rowSpan=2><IMG height=132 alt="" 
      src="__PUBLIC__/images/crm_21.gif" width=292></td>
      <td rowSpan=2><IMG height=132 alt="" src="__PUBLIC__/images/crm_22.gif" 
      width=170></td>
      <td colSpan=2><IMG height=75 alt="" src="__PUBLIC__/images/crm_23.gif" 
    width=332></td>
    </tr>
    <tr>
      <td colSpan=2><IMG height=57 alt="" src="__PUBLIC__/images/crm_24.gif" 
    width=332></td>
    </tr>
    <tr>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=59></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=127></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=24></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=86></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=21></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=28></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=56></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=101></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=170></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" width=125></td>
      <td><IMG height=1 alt="" src="__PUBLIC__/images/spacer.gif" 
  width=207></td>
    </tr>
  </tbody>
</table>
</form>
</body>
</html>