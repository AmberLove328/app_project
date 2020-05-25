<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 9:50
 */

namespace app\index\controller;
use think\Controller;
use think\Loader;

class CommonStandard extends Controller
{


    /**
     * 获取所有数据
     * @param array|string $whereTime
     * @param string $place
     * @return array
     */
    public function charts_data_all($whereTime, $place){
        $where = ['status'=>['=','0'], 'place'=>['=',$place]];
        $data = Loader::model('Webmail')->selectallfiled($where,$whereTime,'type,mailsp,usetime,createtime');
        return collection($data)->toArray(); //将对象转换成数组返回

    }

    /**
     * 获取139所有数据
     * @param array $whereTime
     * @param string $place
     * @param string $type
     * @return array
     */
    public function charts_data_139($whereTime,$type ,$place){
        $where = ['mailsp'=>['like','%'.'139'.'%'],'status'=>['=','0'], 'place'=>['=',$place],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectFiled($where,$whereTime,'mailsp,createtime,usetime');
        return collection($data)->toArray(); //将对象转换成数组返回
    }

    /**
     * 获取189所有数据
     * @param array $whereTime
     * @param string $type
     * @param string $place
     * @return array
     */
    public function charts_data_189($whereTime,$type, $place){
        $where = ['mailsp'=>['=','189.cn'],'status'=>['=','0'], 'place'=>['=',$place],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectFiled($where,$whereTime,'mailsp,usetime,createtime');
        return collection($data)->toArray(); //将对象转换成数组返回
    }

    /**
     * 获取163所有数据
     * @param array $whereTime
     * @param string $type
     * @param string $place
     * @return array
     */
    public function charts_data_163($whereTime,$type,$place){
        $where = ['mailsp'=>['=','163.com'],'status'=>['=','0'], 'place'=>['=',$place],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectFiled($where,$whereTime,'mailsp,usetime,createtime');
        return collection($data)->toArray(); //将对象转换成数组返回
    }

    /**
     * 获取qq所有数据
     * @param array $whereTime
     * @param string $type
     * @param string $place
     * @return array
     */
    public function charts_data_qq($whereTime,$type,$place){
        $where = ['mailsp'=>['=','qq.com'],'status'=>['=','0'], 'place'=>['=',$place],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectFiled($where,$whereTime,'mailsp,usetime,createtime');
        return collection($data)->toArray(); //将对象转换成数组返回
    }

    /**
     * 获取sina所有数据
     * @param array $whereTime
     * @param string $type
     * @param string $place
     * @return array
     */
    public function charts_data_sina($whereTime,$type,$place){
        $where = ['mailsp'=>['=','sina.com'],'status'=>['=','0'], 'place'=>['=',$place],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectFiled($where,$whereTime,'mailsp,usetime,createtime');
        return collection($data)->toArray(); //将对象转换成数组返回
    }


    /**
     * 处理139_hui数据
     * @param array $arr
     * @return array
     */
    public function data_139_hui($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != '139.com_hui'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理139_3.0数据
     * @param array $arr
     * @return array
     */
    public function data_139_3($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != '139.com_3.0'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理139_6.0数据
     * @param array $arr
     * @return array
     */
    public function data_139_6($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != '139.com_6.0'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }


    /**
     * 处理189数据
     * @param array $arr
     * @return array
     */
    public function data_189($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != '189.cn'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }


    /**
     * 处理163数据
     * @param array $arr
     * @return array
     */
    public function data_163($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != '163.com'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }


    /**
     * 处理qq数据
     * @param array $arr
     * @return array
     */
    public function data_qq($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'qq.com'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }


    /**
     * 处理sina数据
     * @param array $arr
     * @return array
     */
    public function data_sina($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'sina.com'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 通过类型获取数据
     * @param array $arr
     * @param string $type
     * @param string $time
     * @return array
     */
    public function chartsData(array $arr,$type,$time){
        $data=[];
        $data['title'] = $this->getTypeTitle($type);
        if(!empty($arr)){
            $data['createtime'] = array_column($arr,"createtime");  //将多个数组转换成一个数组
            $data['usetime'] = array_column($arr,"usetime");  //将多个数组转换成一个数组
        }else{
            $data['createtime'] = [$time];
            $data['usetime'] = [number_format(0,2)];
        }

        return $data;
    }


    /**
     * 通过类型获取title
     * @param string $type
     * @return string
     */
    public function getTypeTitle($type){
        switch ($type){
            case '1':
                return "打开首页";
            case '2':
                return "邮箱登录";
            case '3':
                return "打开写信页";
            case '6':
                return "读邮件";
            case '7':
                return "下载1M附件";
            case '8':
                return "发送邮件";
            case '9':
                return "搜索邮件";
            case '12':
                return "接收外域";
            case '108':
                return "超大附件上传";
            case '109':
                return "超大附件下载";
            default:
                return "未知类型";
        }
    }

    /**
     * 性能指标均值计算结果
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function data_avg($arr_139=[],$arr_163=[],$arr_189=[],$arr_qq=[],$arr_sina=[]){
        return [
            $this->dataAvg([$arr_139[0],$arr_163[0],$arr_189[0],$arr_qq[0],$arr_sina[0]]),
            $this->dataAvg([$arr_139[1],$arr_163[1],$arr_189[1],$arr_qq[1],$arr_sina[1]]),
            $this->dataAvg([$arr_139[2],$arr_163[2],$arr_189[2],$arr_qq[2],$arr_sina[2]]),
            $this->dataAvg([$arr_139[3],$arr_163[3],$arr_189[3],$arr_qq[3],$arr_sina[3]]),
            $this->dataAvg([$arr_139[4],$arr_163[4],$arr_189[4],$arr_qq[4],$arr_sina[4]]),
            $this->dataAvg([$arr_139[5],$arr_163[5],$arr_189[5],$arr_qq[5],$arr_sina[5]]),
            $this->dataAvg([$arr_139[6],$arr_163[6],$arr_189[6],$arr_qq[6],$arr_sina[6]]),
            $this->dataAvg([$arr_139[7],$arr_163[7],$arr_189[7],$arr_qq[7],$arr_sina[7]]),
            $this->dataAvg([$arr_139[8],$arr_163[8],$arr_189[8],$arr_qq[8],$arr_sina[8]]),
        ];
    }

    /**
     * 性能指标均值单组计算
     * @param array $arr
     * @return string
     */
    private function dataAvg(array $arr){
        $avg = '';
        if(!empty($arr)){
            $i = 0;
            foreach ($arr as $v){
                if($v != 0 && $v != null){
                    $i = $i + 1;
                }
            }
            $i == 0 ? $avg = number_format(0,2) : $avg =  number_format(floor(array_sum($arr)/$i*100)/100,2);
        }
        return $avg;

    }



    /**
     * 性能指标排序结果
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function data_order($arr_139=[],$arr_163=[],$arr_189=[],$arr_qq=[],$arr_sina=[]){
        if(!empty($arr_139)){
            $data = [
                $this->dataOrder($arr_139[0],[$arr_163[0],$arr_189[0],$arr_qq[0],$arr_sina[0]]),
                $this->dataOrder($arr_139[1],[$arr_163[1],$arr_189[1],$arr_qq[1],$arr_sina[1]]),
                $this->dataOrder($arr_139[2],[$arr_163[2],$arr_189[2],$arr_qq[2],$arr_sina[2]]),
                $this->dataOrder($arr_139[3],[$arr_163[3],$arr_189[3],$arr_qq[3],$arr_sina[3]]),
                $this->dataOrder($arr_139[4],[$arr_163[4],$arr_189[4],$arr_qq[4],$arr_sina[4]]),
                $this->dataOrder($arr_139[5],[$arr_163[5],$arr_189[5],$arr_qq[5],$arr_sina[5]]),
                $this->dataOrder($arr_139[6],[$arr_163[6],$arr_189[6],$arr_qq[6],$arr_sina[6]]),
                $this->dataOrder($arr_139[7],[$arr_163[7],$arr_189[7],$arr_qq[7],$arr_sina[7]]),
                $this->dataOrder($arr_139[8],[$arr_163[8],$arr_189[8],$arr_qq[8],$arr_sina[8]]),
            ];

        }else{
            $data = ['暂无排名','暂无排名','暂无排名','暂无排名','暂无排名','暂无排名','暂无排名','暂无排名','暂无排名'];
        }
        return $data;
    }

    /**
     * 性能指标单组排序
     * @param float|string $avg_139
     * @param array $arr
     * @return string
     */
    private function dataOrder($avg_139,array $arr){
        if(round($avg_139,2) != 0) {
            $order = 1;
            foreach ($arr as $v) {
                if ($v != 0) {
                    if ($avg_139 > $v) {
                        $order = $order + 1;
                    }
                }
            }
        }else{
            $order = '暂无排名';
        }
        return $order;
    }


    /**
     * 计算各项均值均值结果
     * @param array $arr
     * @return array
     */
    public function avg_charts($arr=[]){
        return [
            $this->avgCharts($arr,1), //打开首页均值 type=1
            $this->avgCharts($arr,2), //邮箱登录均值 type=2
            $this->avgCharts($arr,3), //打开写信页均值 type=3
            $this->avgCharts($arr,6), //读邮件均值 type=6
            $this->avgCharts($arr,7), //下载1M附件均值 type=7
            $this->avgCharts($arr,8), //发送邮件均值 type=8
            $this->avgCharts($arr,9), //搜索邮件均值 type=9
            $this->avgCharts($arr,12), //接收外域均值 type=12
            $this->avgCharts($arr,109) //超大附件下载均值 type=109
        ];

    }


    /**
     * 计算各项单组均值
     * @param array $arr
     * @param string $type
     * @return string
     */
    private function avgCharts(array $arr,$type){
        $avg ='';
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                if(strval($v['type']) != $type){
                    unset($arr[$key]);
                }
            }
            $arr_avg =  array_column($arr, 'usetime');  //将多个数组转换成一个数组
            $count = count($arr_avg);
            $count == 0 ? $avg=number_format(0,2) : number_format($avg = floor(array_sum($arr_avg)/$count*100)/100,2);
        }
        return $avg;
    }


}