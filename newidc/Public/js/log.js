$(document).ready(function(){
	$("#logshow tr").hover(function() { // 隔行换色
		$(this).attr({"class" : "tr_3"});
	}, function() {
		if ($(this).attr("id") == 1) {
			$(this).attr({"class" : "tr_1"});
		} else {
			$(this).attr({"class" : "tr_2"});
		}
	});
	
});