$(document).ready(function(){
	$("#search").click(function(){
		var typeValue = $("#objlist").val();
		var costType = $("#costType").val();
//		alert(typeValue);
		if(typeValue == 0){
			return false;
		}
		var startTime = $("#getAmount input[name='start']").val();
		var endTime = $("#getAmount input[name='end']").val()
		postData = {
			type:typeValue,
			costtype:costType,
			start:startTime,
			end:endTime
		}
		if(typeValue == 1){
			$(".div").attr('id','container')
			$('#container').css({height: "600px",display:"block"});
			$.ajax({
				type:"post",
				url:getAmountUrl,
				data:postData,
				success:function(data){
					if(data.status == 0){
						art.dialog({
							content:"没有数据,请重新选择时间段",
							lock:true,
							ok:function(){
								location.href = location.href;
							},
							cancel:function(){
								location.href = location.href;
							}
						});
						return false;
					}
					newData = data.data.data;
					amountdata = new Array();
					var j=0;
					for(i in newData){
						amountdata.push([i,newData[i].rmb]);
						j++;
					}
					$('#container').highcharts({
			            chart: {
			                plotBackgroundColor: null,
			                plotBorderWidth: null,
			                plotShadow: false
			            },
			            title: {
			                text: data.info+ ' IDC费用比例图'
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
				}
			});
		}else if(typeValue == 2){
			$(".div").attr('id','container1')
			$('#container1').css({height: "600px",display:"block"});
			$.ajax({
				type:"post",
				url:getAmountUrl,
				data:postData,
				success:function(data){
					if(data.status == 0){
						art.dialog({
							content:"没有数据,请重新选择时间段",
							lock:true,
							ok:function(){
								location.href = location.href;
							},
							cancel:function(){
								location.href = location.href;
							}
						});
						return false;
					}
					newData = data.data.data;
					amountdata = new Array();
					for(i in newData){
						amountdata.push(newData[i]);
					}
					chart = new Highcharts.Chart({
				        chart: {
				            renderTo: 'container1',
				            zoomType: 'x'  //******  这句是实现局部放大的关键处  ******
				        },
				        title: {
				            text: data.info+' 每日费用',
				            x: -20 //center
				        },
				        subtitle: {
				            text: '来自: idc.uqee.com',
				            x: -20
				        },
				        xAxis: {
				            categories: data.data.time
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
			});
		}
	});
});
function one(){

	return false;
}
function oneday(){

    return false;
}
