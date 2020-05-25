layui.use(['laydate','form'], function(){
    var laydate = layui.laydate;
    var $ = layui.$;
    var form = layui.form;

    //设置开始时间
    var startTime = laydate.render({
        elem: '#startTime', //指定元素
        max:getNowFormatDate(),
        btns: ['confirm'],
        done:function(value,date){
            endTime.config.min={
                year:date.year,
                month:date.month-1,//关键
                date:date.date
            };
        }
    });
    //设置结束时间
    var endTime = laydate.render({
        elem: '#endTime', //指定元素
        max:getNowFormatDate(),
        btns: ['confirm'],
        done:function(value,date){
            startTime.config.max={
                year:date.year,
                month:date.month-1,//关键
                date:date.date
            }
        }
    });

    //图表
    function charts(startTime,endTime,place) {
        var myChart = echarts.init(document.getElementById("container"));
        myChart.showLoading();
        $.get('chartsStandard',{startTime:startTime,endTime:endTime,place:place}).done(function (res) {
            if(res.code === 0){
                myChart.hideLoading();
                var seriesLabel = {
                    normal: {
                        show: true
                    }
                };
                var style_139_3;
                var style_139_6;
                var style_139_hui;
                var style_163;
                var style_189;
                var style_qq;
                var style_sina;
                res.data.over_mail.mail_139_3 === 'false' ? style_139_3 = {name:'139_3.0',textStyle:{color:'red'}} : style_139_3 = {name:'139_3.0'};
                res.data.over_mail.mail_139_6 === 'false' ? style_139_6 = {name:'139_6.0',textStyle:{color:'red'}} : style_139_6 = {name:'139_6.0'};
                res.data.over_mail.mail_139_hui === 'false' ? style_139_hui = {name:'139_灰度',textStyle:{color:'red'}} : style_139_hui = {name:'139_灰度'};
                res.data.over_mail.mail_189 === 'false' ? style_189 = {name:'189邮箱', textStyle:{color:'red'}} : style_189 = {name:'189邮箱'};
                res.data.over_mail.mail_163 === 'false' ? style_163 = {name:'163邮箱', textStyle:{color:'red'}} : style_163 = {name:'163邮箱'};
                res.data.over_mail.mail_qq === 'false' ? style_qq = {name:'qq邮箱', textStyle:{color:'red'}} : style_qq = {name:'qq邮箱'};
                res.data.over_mail.mail_sina === 'false' ? style_sina = {name:'新浪邮箱', textStyle:{color:'red'}} : style_sina = {name:'新浪邮箱'};
                myChart.setOption({
                    title: {
                        text: '性能指标：平均值'
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    legend: {
                        data:[style_139_3,style_139_6,style_139_hui,style_163,style_189,style_qq,style_sina,'平均值']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    calculable: true,
                    toolbox: {
                        show:true,
                        feature: {
                            saveAsImage: {show: true}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        name: '指标',
                        data: ['打开首页','邮箱登录','打开写信页','读邮件','下载1M附件','发送邮件','搜索邮件','接收外域','超大附件下载']
                    },
                    yAxis: {
                        type: 'value',
                        max: '30',
                        name: '耗时',
                        axisLabel: {
                            formatter: '{value} s'
                        }
                    },
                    series: [
                        {
                            name: '139_3.0',
                            type: 'bar',
                            data: res.data ? res.data.data_139_3 : '',
                            label: seriesLabel
                        },
                        {
                            name: '139_6.0',
                            type: 'bar',
                            data: res.data ? res.data.data_139_6 : '',
                            label: seriesLabel
                        },
                        {
                            name: '139_灰度',
                            type: 'bar',
                            data: res.data ? res.data.data_139_hui : '',
                            label: seriesLabel
                        },
                        {
                            name: '163邮箱',
                            type: 'bar',
                            data: res.data ? res.data.data_163 : '',
                            label: seriesLabel
                        },
                        {
                            name: '189邮箱',
                            type: 'bar',
                            data: res.data ?  res.data.data_189 : '',
                            label: seriesLabel
                        },
                        {
                            name: 'qq邮箱',
                            type: 'bar',
                            data: res.data ? res.data.data_qq : '',
                            label: seriesLabel
                        },
                        {
                            name: '新浪邮箱',
                            type: 'bar',
                            data: res.data ? res.data.data_sina : '',
                            label: seriesLabel
                        },
                        {
                            name:'平均值',
                            type:'line',
                            data:res.data ? res.data.avg : '',
                            label: seriesLabel
                        }
                    ]
                });

                //139邮箱排名
                var T=document.getElementsByTagName('table').item(0);
                var td=T.getElementsByTagName('td');
                var len=td.length;
                for(var i=0;i<len;i++){
                    if(res.data){
                        if(res.data.order[i] !== undefined){
                            if(res.data.over[i] !== undefined){
                                if(res.data.over[i] === 'true'){
                                    td.item(i).innerHTML = "<p style='color: red'>"+res.data.order[i]+"</p>";
                                }else {
                                    td.item(i).innerHTML = res.data.order[i];
                                }
                            }
                        }
                    }
                }
            }else{
                layer.msg(res.msg,{icon:5,time:1000});
            }

        });
    }

    charts();

    //限制日期只能选择到今天为止
    function getNowFormatDate() {
        var date = new Date();
        var seperator_1 = "-";
        var seperator_2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
            var currentDate = date.getFullYear() + seperator_1 + month
            + seperator_1 + strDate + " " + date.getHours() + seperator_2
            + date.getMinutes() + seperator_2 + date.getSeconds();
        return currentDate;
    }

    //搜索按钮点击事件
    $('#search').click(function () {
        charts($('#startTime').val(),$('#endTime').val(),$('#place').val());
    });

    var baseServer = window.location.protocol + '//' + window.location.host + '/';
    //导出
    $('#export').click(function () {
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        var place = $('#place').val();
        window.open(baseServer + 'index/index/exportStandard?startTime='+startTime+'&endTime='+endTime+'&place='+place);
    });


    form.render();
});