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

class CommonCool extends Controller
{

    /**
     * 获取所有数据
     * @param array|string $whereTime
     * @return array
     */
    public function charts_data_all($whereTime){
        $where = ['status'=>['=',0]];
        $data = Loader::model('Webmail')->selectallthird($where,$whereTime,'type,isp,place,usetime');
        return collection($data)->toArray(); //将对象转换成数组返回
    }

    /**
     * 过滤掉4g的数据，返回wifi的数据
     * @param array $arr
     * @return array
     */
    public function data_wifi($arr){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['isp'] != 'wifi'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }


    /**
     * 过滤掉wifi的数据，返回4g的数据
     * @param array| $arr
     * @return array
     */
    public function data_4g($arr){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['isp'] != '4g'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理139酷版数据
     * @param array $arr
     * @return array
     */
    public function data_139(array $arr){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['place'] != 'android_cool'){
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
    public function data_163(array $arr){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['place'] != 'android_smart163'){
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
    public function data_189(array $arr){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['place'] != 'android_189_cool'){
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
                if($v['place'] != 'android_qq_cool'){
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
                if($v['place'] != 'android_tssina'){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 通过类型获取title
     * @param string $type
     * @return string
     */
    public function getTypeTitle($type){
        switch ($type){
            case '2':
                return "登陆邮箱";
            case '3':
                return "打开未读邮件";
            case '4':
                return "打开写信页";
            case '20':
                return "附件下载";
            default:
                return "未知类型";
        }
    }


    /**
     * 计算各项均值均值
     * @param array $arr
     * @return array
     */
    public function avg_charts($arr=[]){
        return [
            $this->avgCharts($arr,2), //酷版_登陆邮箱时长均值 type=2
            $this->avgCharts($arr,4), //酷版_打开写信页时长均值 type=4
            $this->avgCharts($arr,3), //酷版_打开未读邮件时长均值 type=3
            $this->avgCharts($arr,20) //酷版_附件下载时长均值 type=20
        ];
    }

    /**
     * 计算酷版时长均值
     * @param array $arr
     * @param string $type
     * @return string
     */
    public function avgCharts(array $arr,$type){
        $avg ='';
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                if(strval($v['type']) != $type){
                    unset($arr[$key]);
                }
            }
            $arr_avg =  array_column($arr, 'usetime');  //将多个数组转换成一个数组
            $count = count($arr_avg);
            $count == 0 ? $avg = number_format(0,2) : $avg = number_format(floor(array_sum($arr_avg)/$count*100)/100,2);
        }
        return $avg;
    }


    /**
     * 酷版性能指标均值
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
            $this->dataAvg([$arr_139[3],$arr_163[3],$arr_189[3],$arr_qq[3],$arr_sina[3]])
        ];
    }

    /**
     * 酷版性能指标均值
     * @param array $arr
     * @return string
     */
    public function dataAvg(array $arr){
        $avg='';
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
     * 酷版性能指标排名 163
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function dataOrder163($arr_139=[],$arr_163=[],$arr_189=[],$arr_qq=[],$arr_sina=[]){
        if(!empty($arr_163)){
            return [
                $this->dataOrder($arr_163[0],[$arr_139[0],$arr_189[0],$arr_qq[0],$arr_sina[0]]),
                $this->dataOrder($arr_163[1],[$arr_139[1],$arr_189[1],$arr_qq[1],$arr_sina[1]]),
                $this->dataOrder($arr_163[2],[$arr_139[2],$arr_189[2],$arr_qq[2],$arr_sina[2]]),
                $this->dataOrder($arr_163[3],[$arr_139[3],$arr_189[3],$arr_qq[3],$arr_sina[3]]),
            ];
        }else{
            return ["暂无排名","暂无排名","暂无排名","暂无排名"];
        }
    }

    /**
     * 酷版性能指标排名 189
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function dataOrder189($arr_139=[],$arr_163=[],$arr_189=[],$arr_qq=[],$arr_sina=[]){
        if(!empty($arr_189)){
            return [
                $this->dataOrder($arr_189[0],[$arr_139[0],$arr_163[0],$arr_qq[0],$arr_sina[0]]),
                $this->dataOrder($arr_189[1],[$arr_139[1],$arr_163[1],$arr_qq[1],$arr_sina[1]]),
                $this->dataOrder($arr_189[2],[$arr_139[2],$arr_163[2],$arr_qq[2],$arr_sina[2]]),
                $this->dataOrder($arr_189[3],[$arr_139[3],$arr_163[3],$arr_qq[3],$arr_sina[3]]),
            ];
        }else{
            return ["暂无排名","暂无排名","暂无排名","暂无排名"];
        }
    }

    /**
     * 酷版性能指标排名 qq
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function dataOrderQq($arr_139=[],$arr_163=[],$arr_189=[],$arr_qq=[],$arr_sina=[]){
        if(!empty($arr_qq)){
            return [
                $this->dataOrder($arr_qq[0],[$arr_139[0],$arr_163[0],$arr_189[0],$arr_sina[0]]),
                $this->dataOrder($arr_qq[1],[$arr_139[1],$arr_163[1],$arr_189[1],$arr_sina[1]]),
                $this->dataOrder($arr_qq[2],[$arr_139[2],$arr_163[2],$arr_189[2],$arr_sina[2]]),
                $this->dataOrder($arr_qq[3],[$arr_139[3],$arr_163[3],$arr_189[3],$arr_sina[3]]),
            ];
        }else{
            return ["暂无排名","暂无排名","暂无排名","暂无排名"];
        }
    }

    /**
     * 酷版性能指标排名 sina
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function dataOrderSina($arr_139=[],$arr_163=[],$arr_189=[],$arr_qq=[],$arr_sina=[]){
        if(!empty($arr_sina)){
            return [
                $this->dataOrder($arr_sina[0],[$arr_139[0],$arr_163[0],$arr_189[0],$arr_qq[0]]),
                $this->dataOrder($arr_sina[1],[$arr_139[1],$arr_163[1],$arr_189[1],$arr_qq[1]]),
                $this->dataOrder($arr_sina[2],[$arr_139[2],$arr_163[2],$arr_189[2],$arr_qq[2]]),
                $this->dataOrder($arr_sina[3],[$arr_139[3],$arr_163[3],$arr_189[3],$arr_qq[3]]),
            ];
        }else{
            return ["暂无排名","暂无排名","暂无排名","暂无排名"];
        }
    }


    /**
     * 酷版性能指标排名
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function data_order($arr_139=[],$arr_163=[],$arr_189=[],$arr_qq=[],$arr_sina=[]){
        if(!empty($arr_139)){
            return [
                $this->dataOrder($arr_139[0],[$arr_163[0],$arr_189[0],$arr_qq[0],$arr_sina[0]]),
                $this->dataOrder($arr_139[1],[$arr_163[1],$arr_189[1],$arr_qq[1],$arr_sina[1]]),
                $this->dataOrder($arr_139[2],[$arr_163[2],$arr_189[2],$arr_qq[2],$arr_sina[2]]),
                $this->dataOrder($arr_139[3],[$arr_163[3],$arr_189[3],$arr_qq[3],$arr_sina[3]]),
            ];
        }else{
            return ["暂无排名","暂无排名","暂无排名","暂无排名"];
        }
    }

    /**
     * 酷版性能指标排名
     * @param float|string $avg
     * @param array $arr
     * @return string
     */
    public function dataOrder($avg,$arr){
        if(round($avg,2) != 0) {
            $order = 1;
            foreach ($arr as $v) {
                if ($v != 0) {
                    if ($avg > $v) {
                        $order = $order + 1;
                    }
                }
            }
        }else{
            $order = "暂无排名";
        }
        return $order;
    }



}