layui.use(['laydate','form'],function () {
    var form = layui.form;
    var $ = layui.$;
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
        elem: '#searchtime', //指定元素
        max:getNowFormatDate(),
        type:'year'
    });

    function charts(datetime,type) {
        var myChart = echarts.init(document.getElementById("container"));
        myChart.showLoading();
        $.get('chartsYearCool',{createtime:datetime,type:type}).done(function (res) {
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
                        name:'月份',
                        boundaryGap: false,
                        data: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
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
                });
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
        charts($('#searchtime').val(),$('#type').val());

    });

    //重置按钮点击事件
    $('#reload').click(function () {
        charts();
    });

    form.render();

});