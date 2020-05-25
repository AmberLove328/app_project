layui.use(['laydate','form'],function () {
    var form = layui.form;
    var laydate = layui.laydate;
    var $ = layui.$;

    //执行一个laydate实例
    laydate.render({
        elem: '#searchtime', //指定元素
        max:getNowFormatDate()
    });

    function charts(datetime,type,place) {
        var myChart = echarts.init(document.getElementById("container"));
        myChart.showLoading();
        $.get('charts_189',{createtime:datetime,type:type,place:place}).done(function (res) {
            if(res.code === 0){
                var label = {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                };
                myChart.hideLoading();
                myChart.setOption({
                    title : {
                        text: res.data ? res.data.title : ''
                    },
                    tooltip : {
                        trigger: 'axis'
                    },

                    toolbox: {
                        show : true,
                        feature : {
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            name: '时间',
                            data : res.data ? res.data.createtime : ''
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value',
                            name: '耗时',
                            axisLabel: {
                                formatter: '{value} s'
                            }
                        }
                    ],
                    series : [
                        {
                            name:'耗时',
                            type:'line',
                            areaStyle: {},
                            data:res.data ? res.data.usetime : '',
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
        charts($('#searchtime').val(),$('#type').val(),$('#place').val());

    });

    //重置按钮点击事件
    $('#reload').click(function () {
        charts();
    });

    form.render();

});