layui.use(['laydate', 'form'], function () {
    var form = layui.form;
    var $ = layui.$;
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
        elem: '#searchtime', //指定元素

    });

    function charts(datetime, type) {
        var myChart = echarts.init(document.getElementById("container"));
        myChart.showLoading();
        $.get('chartsMonthStandard', {createtime: datetime, type: type}).done(function (res) {
            if (res.code === 0) {
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
                        data: ['139_3.0', '139_6.0', '139_灰度', '189邮箱', '163邮箱', 'qq邮箱', '新浪邮箱']
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
                        name: '日期',
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
                            name: '139_3.0',
                            type: 'line',
                            data: res.data.data_139_3,
                            label: label
                        },
                        {
                            name: '139_6.0',
                            type: 'line',
                            data: res.data.data_139_6,
                            label: label
                        },
                        {
                            name: '139_灰度',
                            type: 'line',
                            data: res.data.data_139_hui,
                            label: label
                        },
                        {
                            name: '189邮箱',
                            type: 'line',
                            data: res.data.data_189,
                            label: label
                        },
                        {
                            name: '163邮箱',
                            type: 'line',
                            data: res.data.data_163,
                            label: label
                        },
                        {
                            name: 'qq邮箱',
                            type: 'line',
                            data: res.data.data_qq,
                            label: label
                        },
                        {
                            name: '新浪邮箱',
                            type: 'line',
                            data: res.data.data_sina,
                            label: label
                        }
                    ]
                })
            } else {
                layer.msg(res.msg, {icon: 5, time: 1000});
            }

        });

    }

    charts();


    //搜索按钮点击事件
    $('#search').click(function () {
        charts($('#searchtime').val(), $('#type').val());

    });

    //重置按钮点击事件
    $('#import').click(function () {

    });

    form.render();

});