layui.use(['laydate','form'], function() {
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

    function charts(startTime,endTime,network) {
        var myChart = echarts.init(document.getElementById("container"));
        myChart.showLoading();
        $.get('chartsCool',{startTime:startTime,endTime:endTime,network:network}).done(function (res) {
            if(res.code === 0){
                myChart.hideLoading();
                var seriesLabel = {
                    normal: {
                        show: true
                    }
                };
                var style_139;
                var style_163;
                var style_189;
                var style_qq;
                var style_sina;
                res.data.over_mail.mail_139 === 'false' ? style_139 = {name:'139邮箱',textStyle:{color:'red'}} : style_139 = {name:'139邮箱'};
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
                            type: 'shadow',
                            crossStyle: {
                                color: '#999'
                            }
                        }
                    },
                    legend: {
                        data:[style_139,style_163,style_189,style_qq,style_sina,'平均值']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    calculable: true,
                    toolbox: {
                        feature: {
                            saveAsImage: {show: true}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: ['酷版_登陆邮箱时长','酷版_打开写信页时长','酷版_打开未读邮件时长','酷版_附件下载时长']
                    },
                    yAxis: {
                        type: 'value',
                        max: '20',
                        name: '耗时',
                        axisLabel: {
                            formatter: '{value} s'
                        }
                    },
                    series: [{
                        name: '139邮箱',
                        type: 'bar',
                        data: res.data ? res.data.data_139 : '',
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

        })
    }

    charts();

    function getNowFormatDate() {
        var date = new Date();
        var seperator1 = "-";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = date.getFullYear() + seperator1 + month
            + seperator1 + strDate + " " + date.getHours() + seperator2
            + date.getMinutes() + seperator2 + date.getSeconds();
        return currentdate;
    }

    //搜索按钮点击事件
    $('#search').click(function () {
        charts($('#startTime').val(),$('#endTime').val(),$('#network').val());
    });

    var baseServer = window.location.protocol + '//' + window.location.host + '/';
    //导出
    $('#export').click(function () {
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        window.open(baseServer + 'index/index/exportCool?startTime='+startTime+'&endTime='+endTime);
    });


    form.render();
});