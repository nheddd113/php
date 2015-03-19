//function huanse(obj) { // 鼠标放上变色
//	obj.className = "tr_3";
//}
//function huanyuan(obj) { // 鼠标移开还原颜色
//	if (obj.id == "1") {
//		obj.className = "tr_1";
//	} else {
//		obj.className = "tr_2";
//	}
//}
//function openShutManager(oSourceObj, oTargetObj) { // 点击展开列表
//	var sourceObj = typeof oSourceObj == "string" ? document
//			.getElementById(oSourceObj) : oSourceObj;
//	var targetObj = typeof oTargetObj == "string" ? document
//			.getElementById(oTargetObj) : oTargetObj;
//	if (targetObj.style.display != "none") {
//		targetObj.style.display = "none";
//		sourceObj.innerHTML = "+";
//	} else {
//		targetObj.style.display = "block";
//		sourceObj.innerHTML = "-";
//	}
//}

function check_exists(obj,item){
	var is_exists = false;
	for(var i=0;i<obj.length;i++){
		if(item == obj.options[i].text){
			is_exists = true;
			break;
		}
	}
	return is_exists;
}
var cache = new Array();
function get_data_ajax(obj, url) {
	var aval = obj.className;
	if (typeof cache[url] == 'undefined') {
		var ajax = createAjax();
		ajax.get(url, function(data) {
			data = eval(data);
			cache[url] == data;
			for (i in data) {
				if (aval == data[i]) {
					continue;
				}
				var y = document.createElement('option');
				y.value = data[i];
				y.text = data[i];
				var is_exists = check_exists(obj,data[i]);
				if (!is_exists) {
					try {
						obj.add(y, null);
					} catch (ex) {
						obj.add(y);
					}
				}
			}
		})
	}
}

function searchip(obj,url){
	var clsname = obj.name;
	if (typeof cache[url] == 'undefined') {
		var ajax = createAjax();
		ajax.get(url, function(data) {
			data = eval(data);
			cache[url] = data;
		})
	}
}


