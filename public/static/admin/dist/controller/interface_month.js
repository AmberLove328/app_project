layui.use(['laydate','form'],function () {
    var form = layui.form;
    var $ = layui.$;
    var laydate = layui.laydate;

    //设置开始时间
    var startTime = laydate.render({
        elem: '#startTime', //指定元素
        max:getNowFormatDate(),
        type:'month',
        btns: ['confirm'],
        done:function(value,date){
            endTime.config.min={
                year:date.year,
                month:date.month-1
            };
        }
    });
    //设置结束时间
    var endTime = laydate.render({
        elem: '#endTime', //指定元素
        max:getNowFormatDate(),
        type:'month',
        btns: ['confirm'],
        done:function(value,date){
            startTime.config.max={
                year:date.year,
                month:date.month-1
            }
        }
    });

    function charts(startTime,endTime,type,overtime) {
        var myChart = echarts.init(document.getElementById("container"));
        myChart.showLoading();
        $.get('chartsMonthInterface',{startTime:startTime,endTime:endTime,type:type,overtime:overtime}).done(function (res) {
            if(res.code === 0){
                var label = {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                };
                myChart.hideLoading();
                myChart.setOption({
                    title: {
                        text: res.data.title
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['139邮箱','189邮箱','163邮箱','qq邮箱','新浪邮箱']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        name:'日期',
                        boundaryGap: false,
                        data: res.data.time
                    },
                    yAxis: {
                        type: 'value',
                        name: '耗时',
                        axisLabel: {
                            formatter: '{value} s'
                        }
                    },
                    series: [
                        {
                            name:'139邮箱',
                            type:'line',
                            data:res.data.data_139,
                            label:label
                        },

                        {
                            name:'189邮箱',
                            type:'line',
                            data:res.data.data_189,
                            label:label
                        },
                        {
                            name:'163邮箱',
                            type:'line',
                            data:res.data.data_163,
                            label:label
                        },
                        {
                            name:'qq邮箱',
                            type:'line',
                            data:res.data.data_qq,
                            label:label
                        },
                        {
                            name:'新浪邮箱',
                            type:'line',
                            data:res.data.data_sina,
                            label:label
                        }
                    ]
                })
            }else{
                layer.msg(res.msg,{icon:5,time:1000});
            }

        });

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
        var data = $('#type').val();
        var value = data.split(',');
        charts($('#startTime').val(),$('#endTime').val(),value[0],value[1]);

    });

    //重置按钮点击事件
    $('#reload').click(function () {
        $('#startTime').val("");
        $('#endTime').val("");
    });

    form.render();

});