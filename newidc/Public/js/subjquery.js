$(document).ready(function() {
	$("#i_users").change(function(){
		var gameId = $("#i_gameType").val();
		var userId = $(this).val();
		if(userId != 0){
			if(gameId == 0){
				alert("请选择游戏!");
				$(this).val(0);
			}
		}
	});
	$(window).resize(function(){
		var bgheight = $(window).height();
		$("#jifang").css({height:bgheight});

	});
	$("#conttable tr").hover(function() { // 隔行换色
		$(this).attr({"class" : "tr_3"});
	}, function() {
		if ($(this).attr("id") == 1) {
			$(this).attr({"class" : "tr_1"});
		} else {
			$(this).attr({"class" : "tr_2"});
		}
	});
	$("#logshow tr").hover(function() { // 隔行换色
		$(this).attr({"class" : "tr_3"});
	}, function() {
		if ($(this).attr("id") == 1) {
			$(this).attr({"class" : "tr_1"});
		} else {
			$(this).attr({"class" : "tr_2"});
		}
	});


	$(".but").click(function() { // 点击展列表机柜列表
				var boxid = $(this).attr("name");
				if ($("#" + boxid).css("display") == "none") {
					$("#" + boxid).css("display", "block");
					$(this).val("-");
				} else {
					$("#" + boxid).css("display", "none");
					$(this).val("+");
				}
			});
	$("#i_cupName").change(function() { // 当机柜信息改变时间,清空机位信息
				$("#i_place").empty();
				var i_cupName = $(this).find("option:selected").text();
				var url = $("#i_place").attr("url") + i_cupName;
				var aoption = "<option value='=请选择='>=请选择=</option>";
				$("#i_place").append(aoption);
			});
	var cache = new Array();


	$.extend({
		"parse_data" : function(obj, url, data, state) { // 处理select填充数据(自定义函数)
			if (state == 'success' && data.length > 0) {
				cache[url] == data; // 防止重新查询,好象没作用!
				data = eval(data);
				for (var i in data) {
					var is_exists = $.check_exists(obj, data[i]);
					if (!is_exists) {
						var aoption = "<option value='" + data[i]
								+ "'>" + data[i] + "</option>";
						$(obj).append(aoption); // 增加option
					}
				}
			}
		}
	});

	$.extend({
		"check_exists" : function(obj, item) { // 判断元素是否存在,存在返回真(自定义函数)
			var is_exists = false;
			for (var i = 0; i < obj.length; i++) {
				if (obj.options[i].text == item) {
					is_exists = true;
					break;
				}
			}
			return is_exists;
		}
	});
	$.extend({ // 分析URL
		"get_param" : function(paras) {
			var url = location.href;
			var paraString = url.substring(url.indexOf("?") + 1, url.length)
					.split("&");
			var paraObj = {}
			for (i = 0; j = paraString[i]; i++) {
				paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j
						.substring(j.indexOf("=") + 1, j.length);
			}
			var returnValue = paraObj[paras.toLowerCase()];
			if (typeof(returnValue) == "undefined") {
				return 0;
			} else {
				return returnValue;
			}
		}
	});
	$.extend({ // 分析URL
		"get_uri" : function(url) {
			var url = location.href;
			if (url.indexOf("?") == "-1") {
				var paraString = new Array();
			} else {
				var paraString = url
						.substring(url.indexOf("?") + 1, url.length).split("&");
			}
			var paraObj = {}
			for (i = 0; j = paraString[i]; i++) {
				paraObj[j.substring(0, j.indexOf("="))] = j.substring(j
								.indexOf("=")
								+ 1, j.length);
			}
			paraObj.action = "query";
			return paraObj
		}
	});
	$.extend({ // select调用
		"switch_url" : function(obj, str_name, url) {
			var str_name_val = $(obj).val();
			str_name_val = str_name_val;
			var urlobj = $.get_uri(url);
			if (url.indexOf("?") != "-1") {
				url = url.substring(0, url.indexOf("?") + 1);
			} else {
				url = url + "?";
			}
			if (str_name_val != 0) {
				urlobj[str_name] = encodeURIComponent(str_name_val);
			} else {
				delete urlobj[str_name];
			}
			for (index in urlobj) {
				url += index + "=" + urlobj[index] + "&";
			}
			url = url.substring(0, url.length - 1);
			url = url.replace(/index.php/, "search.php");
			$(obj).val(str_name_val);
			return url;
		}
	});



	$("#userlist").val(decodeURIComponent($.get_param('i_users')));
	$("#userlist").change(function() {
				var url = location.href;
				url = $.switch_url($(this), 'i_users', url);
				location.href = url;
			});
	$("#jflist").val(decodeURIComponent($.get_param('houid'))); // 默认选择为0
	$("#jflist").change(function() {
				var url = location.href;
				url = $.switch_url($(this), 'i_address', url);
				location.href = url;
			});

	$("#gamelist").val($.get_param('i_gameType'));
	$("#gamelist").change(function() {
				var url = location.href;
				url = $.switch_url($(this), 'i_gameType', url);
				location.href = url;
			});
	$("#showtype").val($.get_param('ip_status'));
	$("#showtype").change(function() { // 按使用情况查询IP
				var url = location.href;
				url = $.switch_url($(this), 'ip_status', url);
				location.href = url;
			});
	$("#showjf").val($.get_param('ip_houId'));
	$("#showjf").change(function() {// 按机房查询IP
				var url = location.href;
				url = $.switch_url($(this), 'ip_houId', url);
				location.href = url;
			});
	$.extend({
				"query_type" : function(obj, str_name, url) {
					query_obj = $.get_uri(url);
					if (url.indexOf("?") != "-1") {
						url = url.substring(0, url.indexOf("?") + 1);
					} else {
						url = url + "?";
					}
					var str_name_val = $("#q_contnet").val();
					query_obj.action = "like";
					query_obj[str_name] = str_name_val;
					for (index in query_obj) {
						url += index + '=' + query_obj[index] + "&"
					}
					url = url.substring(0, url.length - 1);
					url = url.replace(/index.php/, "search.php");
					return url;
				}

			});
	$("#hide_vir_list").click(function() { //隐藏虚拟机列表
			$("#vir_list_2").toggle();
			});
	$("#hide_log_list").click(function() { //隐藏日志列表
				$("#logshow").toggle();
			});
	var manyValue =$('input:radio[name="isMany"]:checked').val();
	if(manyValue != 1){
		$("#show0").html('');
		$("#show1").html('');
		$("#mainip").attr({'class':''});$("#subip").attr({'class':''});$("#innip").attr({'class':''});
	}



});


//新建虚拟机批量增加单选事件,1:是,0:否


function not_found() {
	alert("此服务器不存在");
	location.href = "index.php";
}


function ajaxRequest(url,data){
	$.ajax({
		type:"post",
		url:url,
		data:data,
		success:function(data){
		}
	});
	return data
}

function deletecup(){
	return confirm("确定要删除该机柜吗?");
}



