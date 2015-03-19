$(document).ready(function(){
    var uuid = $("input:text[name='uuid']").val();
    var mainip = $("input:text[name='mainip']").val();
    if(uuid == '' && mainip != ''){
        art.dialog({
            content: "该服务器尚未配置UUID,请选择!",
            lock: true,
            id: "no_install",
            button:[
                {
                    name:"更新UUID",
                    focus:true,
                    callback: function(){
                        var hostid = $("input:hidden[name='id']").val();
                        updateuuid(hostid);
                    }
                },
                {
                    name:"取消",
                }
            ]
        });
    }
    var gameid = $("#gameid").attr('class');
    $("#gameid option[value="+gameid+"]").attr('select',true);
    $("#jichujifang").hide();
    $("#chaozuo").change(function(){
        if($(this).val() == 3){
            $.ajax({
                type:"post",
                url:getVirHostUrl,
                data:{id:$("#id").val()},
                success:function(data){
                    if(data.status==0){
                        art.dialog({content:data.info,lock:true,ok:function(){location.href=location.href}});
                    }
                }
            });
            $("#jichujifang").show();
        }else{
            $("#jichujifang").hide();
        }
    });
    var owner = $("#h_owner").val();
    if(owner == ''){
        $("#owner option[value='0']").attr('selected',true);
    }else{
        $("#owner option[value="+owner+"]").attr('selected',true);
    }
    var ishost = $("#h_ishost").val();
    $("#ishost option[value="+ishost+"]").attr('selected',true);
    var cupid = $("#cupid").attr('class');
    $("#cupid option[value="+cupid+"]").attr('selected',true);
    var seatid = $("#seatid").attr('class')
    $("#seatid option[value="+seatid+"]").attr('selected',true);
    if(ishost == 2){
        $("#ishost").attr('disabled',true);
        $("#cupid").attr('disabled',true);
        $("#seatid").attr('disabled',true);
        $("#gameid").attr('disabled',true);
        $("#showIsHost").show();
        $("#showIsHost1").show();
    }else{
        $("#ishost").attr('disabled',false);
        $("#showIsHost").hide();
        $("#showIsHost1").hide();
        $("#cupid").attr('disabled',false);
        $("#seatid").attr('disabled',false);
        $("#gameid").attr('disabled',false);
    }
    if(ishost == 1){
        $("#add_vir").show();
        $("#virHostList").show();
    }else{
        $("#add_vir").hide();
        $("#virHostList").hide();
    }
    var ismanager = $("#h_ismanager").val();
    $("#ismanager"+ismanager).attr('checked',true);
    if(ishost >= 2){
        $("input:radio[name='ismanager']").attr('disabled',true);
    }
//  $("#gameid").change(function(){
//      if($(this).val() == 7 && ishost == 2){
//          art.dialog({
//              content:"虚拟机不能将所属游戏修改为:宿主机!",
//              ok:function(){
//                  location.href = location.href;
//              }
//          });
//      }
//
//  });
$("#ishost").change(function(){
    if($(this).val()== 2){
        art.dialog({content:"服务器类型不能修改为虚拟机!",lock:true});
        $(this).val(ishost);
    }
    if($(this).val() == 1 && $("#status").val() == 0){
        art.dialog({
            content:"该服务器处于下架状态,不能修改为服务器类型为宿主机!",
            lock:true,
            ok:function(){
                location.href = location.href;
            },
            cancel:function(){
                location.href = location.href;
            }
        });
    }
    if(ishost == 1 && $(this).val() != 1){
        art.dialog({
            content:"该服务器为宿主机,确认要修改服务器类型?",
            ok:function(){
                var id = $("#id").val();
                $.ajax({
                    type:"post",
                    url:getVirHostUrl,
                    data:{id:id},
                    success:function(data){
                        if(data.status==0){
                            art.dialog({
                                content:data.info,
                                lock:true,
                                ok:function(data){
                                    location.href = location.href;
                                },
                                cancel:function(){
                                    location.href = location.href;
                                }
                            });
                        }
                    }
                });
            },
            cancel:function(){
                location.href = location.href;
            }
        });

    }
});
    $("#vir_list_2 tr").hover(function() { // 隔行换色
        $(this).attr({"class" : "tr_3"});
    }, function() {
        if ($(this).attr("id") == 1) {
            $(this).attr({"class" : "tr_1"});
        } else {
            $(this).attr({"class" : "tr_2"});
        }
    });
    $("#status").change(function() {  //如果该服务器下有虚拟机的时候不能修改状态,并重新刷新页面
        if(ishost == 1){
            var id = $("#id").val();
            $.ajax({
                type:"post",
                url:getVirHostUrl,
                data:{id:id,type:true},
                success:function(data){
                    if(data.status == 0){
                        art.dialog({
                            content:data.info,
                            ok:function(){
                                location.href = location.href;
                            },
                            cancel:function(){
                                location.href = location.href;
                            }
                        });
                    }
                }
            });
        }
        if ($(this).val() == 0) { //修改为下架时,清除部分数
            $("#cupid").val(0);
            $("#seatid").val(0);
            $("#mainip").val("");
            $(".subip").val("");
            $(".innip").val("");
            $("#owner").val(0);
            $("#hostype").val(0);
            $("#gameid").val(0);
            $("input[name='starttime']").val('');
            $("input[name='pretime']").val('');
        } else if ($(this).val() == 1) { //修改为闲置时,清除部分数
            $("#owner").val(0);
            $("#hostype").val(0);
            $("#gameid").val(0);
            $("input[name='starttime']").val('');
            $("input[name='pretime']").val('');

        }
    });
    $("#cupid").change(function(){
        $("#seatid option").remove();
        var houid = $("#houseInfo").find("option:selected").val();
        var cupid = $(this).val();
        var hcupid = $("#h_cupid").val()
        if (cupid == hcupid){
            var seatid = $("#h_seatid").val()
            $('<option value="'+seatid+'">'+seatid+'</option>').appendTo("#seatid");
            $('#seatid option[value="'+seatid+'"]').attr('selected',true);
        }
        $.post(seatUrl,{cupid:cupid,houid:houid},function(data,state){
            data = data.data
            $('<option value="0">请选择</option>').appendTo("#seatid");
            for (index in data){
                var seatid = data[index].seatid;
                $('<option value="'+seatid+'">'+seatid+'</option>').appendTo("#seatid");
            }
        });
    });
    $("#seatid").change(function(){
        var cupid = $("#cupid").val();
        var seatid = $(this).val();
        var id = $("#id").val();
        $.ajax({
            type:"POST",
            url:seatStatUrl,
            data:{cupid:cupid,seatid:seatid,id:id},
            success:function(data){
                if(data == 1){
                    art.dialog({
                        title:"通知",
                        content:"该机位已被其它服务器占用,先选择其它机位!",
                        lock:true,
                        ok: function(){
                            $("#seatid").val($("#h_seatid").val());
                        }
                    });
                }
            }
        });
    });
    $("#searchops").keyup(function(){
        var obj = {};
        var count = $("#owner option").length;
        var currvalue = $(this).val();
        if(currvalue.length == 0){
            $("#owner").val($("#h_owner").val());
            return;
        }
        for(var i=0;i<=count;i++){
            var name = $("#owner").get(0).options[i].text;
            var id = $("#owner").get(0).options[i].value;
//          alert('id:'+id);
//          alert('当前次数结果为:'+name.indexOf(currvalue));
if(name.indexOf(currvalue)>=0){
    $("#owner").get(0).options[i].selected=true;
    $("#owner").change();
    break;
}
}
});
    $("#owner").change(function(){
        if($(this).val() !=0 && $("#hostype").val()<=1){
            $("#status").val(2);
            $("#hostype").val(1);
        }
        if($(this).val() == 0){
            $("#status").val(1);
            $("#hostype").val(0);
        }
    });
    $("#hostype").change(function(){
        if(ishost == 1){   //如果是宿主机. 就查出所有虚拟机
            var id = $("#id").val();
            $.ajax({
                type:"post",
                url:getVirHostUrl,
                data:{id:id},
                success:function(data){
                    if(data.status == 0){
                        art.dialog({
                            content:data.info,
                            ok:function(data){
                                location.href = location.href;
                            }
                        });
                    }
                }
            });
        }
        if($(this).val() == 0){
            $("#status").val(1);
            $("#owner").val(0);
            $("#gameid").val(0)
            $("input[name='starttime']").val('');
            $("input[name='pretime']").val('');
        }
    });
    $(window).resize(function() { // 当窗口改变大小时.固定IP填充div
        var dx_list = $("#mainip").position();
        $(".style2").css({
            left : dx_list.left,
            top : dx_list.top + 25
        });
    });
    $("#mainip").keyup(function() { // 填写IP时提示IP
        var pos = $(this).position();  //得到电信IP的坐标
        var mainip = $(this).val();
        var houid = $("#houid").val()
        if (mainip.length == 0){
//          alert(mainip.length);
$("#ajaxShowIp").css("display","none");
return false;
}
$.ajax({
    type:'POST',
    url:getMainUrl,
    data:{mainip:mainip,houid:houid},
    success:function(data,state){
        if(state == 'success' && data.length > 0){
            data = eval(data);
            var ipstr = '';
            for (index in data){
                        ipstr += '<span onclick="writeiptotext(this)" id="'   //将IP写入div
                        + data[index].mainip
                        + '">'
                        + data[index].mainip
                        + '</span><br>';
                    }
                    $("#ajaxShowIp").html(ipstr);
                    $("#ajaxShowIp").css("left", pos.left);
                    $("#ajaxShowIp").css("top", pos.top + 25);
                    $("#ajaxShowIp").css("display", "block");
                }else{
                    $("#ajaxShowIp").html('');
                    $("#ajaxShowIp").css("display","none");
                }
            }
        });
});

});


function checkState(ip){  //请求该Ip的状态
    $.post(chkStaUrl,'mainip='+ip,function(data,state){
        if(state == 'success'){
            if(data == 1){
                changeServer(ip);
            }
        }
    });
}


function changeServer(ip){  //请求服务器id
    var oldMainip = $("#h_mainip").val();
    if(ip == oldMainip){
        return false;
    }
    $.post(getHostUrl,'mainip='+ip,function(data,state){
        if(state == 'success'){
            art.dialog({
                content:"该IP已被服务器:"+data+"使用,根据你的需要选择以下操作!",
                title:"该IP已被服务器使用",
                lock:true,
                esc:false,
                button:[
                {
                    name: '互换信息',
                    focus: true,
                    callback:function (){
                        var currid = $("#hostid").val();
                        var method = 'switch';
                        $.ajax({
                            type:"post",
                            url:switchInfoUrl,
                            data:{method:method,currid:currid,souid:data},
                            success:function(res){
                                art.dialog("服务器互换信息成功!",function(){
                                    location.href = res;
                                });
                            }
                        });
                    }
                },
                {
                    name: '互换IP',
                    callback: function () {
                        var currid = $("#hostid").val();
                        var method = 'chgip';
                        $.ajax({
                            type:"POST",
                            url:switchInfoUrl,
                            data:{method:method,currid:currid,souid:data},
                            success:function(res){
                                art.dialog("服务器互换IP成功!",function(){
                                    location.href = res;
                                });
                            }
                        });
                    }
                },
                {
                    name: '将其下架',
                    callback: function () {
                        var currid = $("#hostid").val();
                        var method = 'chgdown';
                        $.ajax({
                            type:"POST",
                            url:switchInfoUrl,
                            data:{method:method,currid:currid,souid:data},
                            success:function(res){
                                art.dialog("已将服务器:"+data+"下架!",function(){
                                    ;
                                });
                            }
                        });
                    }
                },
                {
                    name: '取消',
                    callback: function () {
                        location.href = location.href;
                    }
                }
                ]
            });
}

});
}

function deleteHost(value){ //删除主机
    art.dialog({
        content:"确认要删除该服务器吗?",
        lock:true,
        ok:function(){
            $.ajax({
                type:"post",
                url:value.url,
                data:{id:value.id},
                success:function(data){
                    art.dialog({
                        title:data.data,
                        content:data.info,
                        ok:function(){
                            location.href = location.href;
                        }
                    });
                }
            });
        },
        cancel:function(){}
    })
    return false;
}

function writeiptotext(obj) { // 将IP写入文本框.
    if($("#status").val()== 0){
        $("#status").val("1");
    }
    //$('#status option[value="1"]').attr('selected',true);
    var dxip = $(obj).text();
    result = checkState(dxip);
    $("input#mainip").val(dxip);
    var ip_arr = dxip.split('.');
    ip_arr[0] = '192';
    ip_arr[1] = '168';
    var inIP = ip_arr.join('.');
    $("#ajaxShowIp").css("display", "none");
    $("input[name='innip']").val(inIP);
    $.post(ajaxUrl,{mainip:dxip}, function(data, state) { // 查询对应的网通IP
        if(state == 'success' && data.length>0){
            $("input[name='subip']").val(data); // 将网通IP写入文框中
        }
    });

}

function xianzhi_check() { //修改为闲置状态时检查
    if (chgserver.mainip.value == '') {
        art.dialog("电信IP不能为空!");
        chgserver.mainip.focus();
        return false;
    }
    if (chgserver.innip.value == '') {
        art.dialog("内网IP不能为空!");
        chgserver.innip.focus();
        return false;
    }
    if (chgserver.cupid.value == 0) {
        art.dialog("机柜不能为空!");
        chgserver.cupid.focus();
        return false;
    }
    if (chgserver.seatid.value == 0) {
        art.dialog("机位不能为空!");
        chgserver.seatid.focus();
        return false;
    }
    return true;
}

function return_false(){
    return false;
}

function check_confirm() {
    if (!confirm("确认要修改数据吗？")) {
        $("#chgserver").attr('onsubmit','return return_false();');
    }else{
        $("#chgserver").attr('onsubmit','return check_change_item();');
    }
}

function shangjia_check() { //修改为上架状态时检查
    if (chgserver.mainip.value == '') {
        art.dialog("电信IP不能为空!");
        chgserver.mainip.focus();
        return false;
    }
    if (chgserver.innip.value == '') {
        art.dialog("内网IP不能为空!");
        chgserver.innip.focus();
        return false;
    }
    if (chgserver.cupid.value == 0) {
        art.dialog("机柜不能为空!");
        chgserver.cupid.focus();
        return false;
    }
    if (chgserver.seatid.value == 0) {
        art.dialog("机位不能为空!");
        chgserver.seatid.focus();
        return false;
    }
    if (chgserver.gameid.value == 0) {
        art.dialog("请指定所属业务!");
        chgserver.gameid.focus()
        return false;
    }
    if (chgserver.owner.value == 0) {
        art.dialog("请指定运营商!");
        chgserver.owner.focus()
        return false;
    }
    if (chgserver.hostype.value != 2 ){
        if (chgserver.pretime.value == '') {
            art.dialog("请指定开服时间!");
            chgserver.pretime.focus()
            return false;
        }
    }
    return true;
}

function check_change_item() {
    if (chgserver.status.value == 1) {
        var result =  xianzhi_check();
        var conent = "该操作会删除计划任务,确定要修改服务器信息吗?";
    }
    if (chgserver.status.value == 2) {
        var result = shangjia_check();
        var conent = "确定要修改服务器信息吗?";
    }
    if (chgserver.status.value == 0) {
        var result = true;
        var conent = "该操作会删除计划任务,确定要修改服务器信息吗?";
    }
    if(result ==  true){
        art.dialog({
            title:"修改服务器",
            content:conent,
            lock:true,
            cancel:false,
            button:[
            {
                name:"修改",
                callback:function(){
                    $("#chgserver").attr('onsubmit','return true;');
                    $("#chgserver").submit();
                },
                focus:true
            },{
                name:"取消",
                callback:function(){
                }
            }
            ]
        });
    }
    return false;
}

function systemOp(id){
    var type = $("#chaozuo").val();
    if(type == 0){
        art.dialog({
            content:"请选择正确的选项后再对进行操作!",
            lock:true
        });
        return false;
    }
    art.dialog({
        content:"确认要对该服务器进行操作吗?",
        lock:true,
        id: 1,
        ok:function(){
            var content = $("#content").val();
            data = {type:type,content:content,id:id};
            if(type == 3){  //寄出操作
                var jichujifang = $("#jichujifang").val();
                data.houid = jichujifang;
            }
            var url = $("#system").attr('action');
            art.dialog({
                title: "请稍等",
                id: 'notify',
                content: "正在对该服务器请行操作,可能需要几分钟,请稍等",
                lock: true,
                esc : false,
                cancel : false
            });
            $.ajax({
                type:"post",
                url:url,
                data:data,
                success:function(data){
                    art.dialog.list['notify'].close();
                    art.dialog({
                        title:data.data,
                        content:data.info,
                        lock:true,
                        ok:function(){
                            if(data.status == 1){
                                location.href = location.href;
                            }
                        }
                    });
                }
            });
        },
        cancel:function(){
        }
    });
}
function sycn_all(id){
    art.dialog({
        title: "请稍等",
        content: "     正在获取Salt数据,请稍等      ",
        id: "waitforsync",
        lock: true,
        esc: false,
        cancel: false
    });
    $.ajax({
        type:"POST",
        url:syncUrl,
        data:{id:id},
        success:function(ret){
            art.dialog.list['waitforsync'].close(); 
            art.dialog({
                content: ret.data,
                title:ret.info,
                ok: function(){},
            }).time(2);
        },
    });
}
function updateuuid(hostid){
    art.dialog({
        title: "请稍等",
        content: "     正在获取UUID,请稍等      ",
        id: "waitforuuid",
        lock: true,
        esc: false,
        cancel: false
    });
    $.ajax({
        type:"POST",
        url:updateUuidUrl,
        data:{id:hostid},
        success:function(ret){
            if(ret.status == 1){
                art.dialog.list['waitforuuid'].close(); 
                art.dialog({
                    content: "获取UUID成功,2秒后自动关闭!",
                    lock:true,
                    ok:function(){}
                }).time(2);
                $(":input[name='uuid']").val(ret.data);
                $("input:button[name='getuuid']").css('display','none');
            }else{
                art.dialog.list['waitforuuid'].close(); 
                art.dialog({
                    title: "获取UUID失败",
                    content:"获取UUID失败,请检查,<br>1:服务器是否已安装salt,<br>" +
                    "2:salt minion是否启动正常,<br>" + 
                    "3:检查堡垒机是否允许idc.uqee.com访问,<br>" +
                    "4:堡垒机是否已启动/mnt/data/xl/python_xl/salt_twisted/main.py",
                    lock: true,
                    ok:function(){},                    
                });
            }
        }
    });
}











