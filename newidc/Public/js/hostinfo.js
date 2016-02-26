$(function(){
    var id = $("#hostinfoFrom :hidden[name=id]").val();
    // 删除虚拟机
    $("[data-target=deleteVirHost]").click(function(){
        var id = $(this).attr('sid');
        var url = $(this).attr('url');
        console.log(id);
        if(confirm('确定要删除该服务器吗?')){
            $.post(url,{id:id},function(ret){
                $("#noticediv .modal-title").text(ret.data);
                $("#noticediv .modal-body").text(ret.info);
                $("#noticediv").modal('show');
                $("#noticediv").on('hide.bs.modal',function(){
                    location.reload();
                });
            },'json');
        }
    });
    // 显示或关闭虚拟机列表
    $("#toggle_virhost").click(function(){
        $("#virhostdiv").toggle('slow');
    });
    // 显示或关闭日志
    $("#toggle_loglist").click(function(){
        $("#loglistdiv").toggle('slow');
    });
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
        format: "yyyy-mm-dd hh:00:00",
        pickerPosition: "top-left",
        autoclose: true,
        language: "zh-CN",
        todayBtn: true,
        todayHighlight: true,
        minuteStep: 2,
        minView: 1,
    });
    // 开启提示
    $('[data-rel=tooltip]').tooltip();
    // 开启搜索框
    $("select[name='mainip']").chosen().change(function(){
        var value = $(this).val();
        $("select[name='mainip'] option").each(function(){
            if($(this).val() == value){
                $(this).attr('selected',true);
            }else{
                $(this).attr('selected',false);
            }
        })
    });
    $("select[name='owner']").chosen().change(function(){
        var value = $(this).val();
        $("select[name='owner'] option").each(function(){
            if($(this).val() == value){
                $(this).attr('selected',true);
            }else{
                $(this).attr('selected',false);
            }
        })
    });
    // 交换服务器Ip提示
    function changeip(mainip,subip){
        $.post(getHostInfoUrl,{mainip:mainip},function(ret){
            if(ret.status == 1){
                $("#server_switch .modal-body").text(ret.info);
                $("#server_switch").modal('show');
                $("#server_switch").on('hide.bs.modal',function(){

                })
                window.sourceid = ret.data.id;
            }
        },'json');
        var ips = mainip.split('.')
        ips[0] = 192;
        ips[1] = 168;
        var innip = ips.join('.');
        $("input[name=innip]").val(innip);
        $("input[name=subip]").val(subip);


    }

    $("[data-confirm=switchip]").click(function(){
        if($(this).val() == 'cancel'){
            $("#server_switch").on('hide.bs.modal',function(){
                location.reload();
            })
            return;
        }
        var method = $(this).val();
        var data = {method:method,currid:id,souid:sourceid}
        $.post(switchInfoUrl,data,function(ret){
            $("#noticediv .modal-body").html(ret.info);
            $("#noticediv").modal('show');
            $("#noticediv").on('hide.bs.modal',function(){
                if(method != 'chgdown'){
                    location.href = ret.data;
                }
            })
        },'json');
    });

    // IP有修改的时候检查被换的Ip是不是已经在使用
    $("select[name='mainip']").change(function(){
        $("select[name='mainip'] option").each(function(){
            if($(this).attr('selected') == 'selected'){
                var subip = $(this).attr('subip');
                var mainip = $(this).val();
                changeip(mainip,subip);
                return ;
            }
        })
    });
    $(".chosen-select").trigger("liszt:d");
    $('[data-rel=tooltip]').tooltip();
    var oLanguage={
        "oAria": {
            "sSortAscending": ": 升序排列",
            "sSortDescending": ": 降序排列"
        },
        "oPaginate": {
            "sFirst": "首页",
            "sLast": "末页",
            "sNext": "下页",
            "sPrevious": "上页"
        },
        "sEmptyTable": "没有相关记录",
        "sInfo": "第 _START_ 到 _END_ 条记录，共 _TOTAL_ 条",
        "sInfoEmpty": "第 0 到 0 条记录，共 0 条",
        "sInfoFiltered": "(从 _MAX_ 条记录中检索)",
        "sInfoPostFix": "",
        "sDecimal": "",
        "sThousands": ",",
        "sLengthMenu": "每页显示条数: _MENU_",
        "sLoadingRecords": "正在载入...",
        "sProcessing": "正在载入...",
        "sSearch": "搜索:",
        "sSearchPlaceholder": "",
        "sUrl": "",
        "sZeroRecords": "没有相关记录"
    }
    $.fn.dataTable.defaults.oLanguage=oLanguage;
    $("#dynamic-table").dataTable({
        "aaSorting": [[0, "asc"]],
        "aoColumns": [
            null,null,null,null,null,null,null,
            { "bSortable": false }
        ],
        "lengthMenu": [[10,20,60,80,100 ,-1],[10,20,60,80,100,'所有']],
    });
    $("#log-table").dataTable({
        "aaSorting": [[0, "asc"]],
        "aoColumns": [
            null,null,null,null,null,null,null,
        ],
        "lengthMenu": [[10,20,60,80,100 ,-1],[10,20,60,80,100,'所有']],
    });

    // 系统操作
    $("[data-button=systemop]").click(function(){
        window.systype = $(this).attr('status');
        window.systemopUrl = $(this).attr('href');
        var txt = $(this).text();
        $("#noticedivHostinfo .modal-title").text('警告');
        $("#noticedivHostinfo .modal-body").text('你确定要进行 '+ txt +' 操作吗?');
        $("#noticedivHostinfo").modal('show');
        $("#noticedivHostinfo").on('hide.bs.modal',function(){
            $(this).removeData('bs.modal');
        });
    });
    $("[data-confirm=confirm]").click(function(){
        var t = $(this).val();
        if(t!=2){
            return ;
        }
        var $t = this;
        $(this).val(1);
        if(systype == 3){ //服务器寄出
            $("#jichu").modal('toggle');
            $("#jichu").on('hide.bs.modal',function(){
                $(this).removeData('bs.modal');
                $($t).val(1);
                $(this).off('hide.bs.modal');
            });
            $('[data-confirm="confirm1"]').click(function(){
                var t = $(this).val();
                var $tt = this;
                // $('[data-confirm="confirm"]').val(1);
                if(t != 2){
                    return ;
                }
                var houid = $("#jichu select[name=houid]").val();
                if(houid.length == 0){
                    $("#noticedivHostinfo .modal-title").text('警告');
                    $("#noticedivHostinfo .modal-body").text('寄出服务器目的地不能空!');
                    $("#noticedivHostinfo").modal('show');
                    $("#noticedivHostinfo").on('hide.bs.modal',function(){
                        $(this).removeData('bs.modal');
                        $($t).val(2);
                        $($tt).val(2);
                        $(this).off('hide.bs.modal');
                    });
                    return;
                }
                $.post(systemopUrl,{id:id,houid:houid,type:systype},function(ret){
                    $("#noticedivHostinfo .modal-title").text(ret.data);
                    $("#noticedivHostinfo .modal-body").text(ret.info);
                    $("#noticedivHostinfo").modal('show');
                    $("#noticedivHostinfo").on('hide.bs.modal',function(){
                        if(ret.status == 1){
                            location.reload();
                        }
                        $(this).removeData('bs.modal');
                        $($t).val(2);
                        $($tt).val(2);
                        $(this).off('hide.bs.modal');
                    });
                },'json');

            });
        }else{

            $("#noticedivHostinfo").modal('toggle');
            // $("#noticedivHostinfo").modal('hide');
            $.post(systemopUrl,{id:id,type:systype},function(ret){
                $("#noticedivHostinfo .modal-title").text(ret.data);
                $("#noticedivHostinfo .modal-body").text(ret.info);
                $("#noticedivHostinfo").modal();
                $("#noticedivHostinfo").on('hide.bs.modal',function(){
                    if(ret.status == 1){
                        location.reload();
                    }
                    $(this).removeData('bs.modal');
                    $($t).val(2);
                    $(this).off('hide.bs.modal');
                });
            },'json');
        }
    });
    $("#hostinfoFrom select[name=status]").on('change',function(){
        var status = $(this).val();
        // alert(status);
        switch(status){
            case '0':
            $.xiajia();break;
            case '1':
            $.xianzhi();break;
            break;
            case '2':

            break;
        }
    });
    $.extend({
        xiajia:function(){
            form = $("#hostinfoFrom").get(0);
            form.cupid.value='';
            form.mainip.value='';
            $("select[name='mainip']").trigger("chosen:updated")
            form.subip.value='';
            form.innip.value='';
            form.owner.value=''
            $("select[name='owner']").trigger("chosen:updated")
            form.hostype.value=0;
            if(form.hasOwnProperty('gameid')){
                form.gameid.value='';
            }
        },
        xianzhi:function(){
            form = $("#hostinfoFrom").get(0);
            form.owner.value=''
            $("select[name='owner']").trigger("chosen:updated")
            form.hostype.value=0;
            if(form.hasOwnProperty('gameid')){
                form.gameid.value='';
            }
        },
    });

    // $("select[name=cupid]").on('change',function(){
    //     var url = $(this).data('url');
    //     var cupid = $(this).val();
    //     $.post(url,{cupid:cupid,id:id},function(ret){
    //         $("#hostinfoFrom [name=seatid] option").remove();
    //         for(i in ret.data.seatinfo){
    //             var seatid = ret.data.seatinfo[i].seatid;
    //             if(ret.data.hostinfo.seatid == seatid){
    //                 $('<option value="'+seatid+'" selected>'+seatid+'</option>').appendTo("#hostinfoFrom [name=seatid]");
    //             }else{
    //                 $('<option value="'+seatid+'">'+seatid+'</option>').appendTo("#hostinfoFrom [name=seatid]");
    //             }
    //         }
    //     },'json');

    // });

});
