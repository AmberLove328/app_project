<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/8
 * Time: 16:54
 */

namespace app\index\controller;
use think\Controller;
use think\Loader;

class CommonYear extends Controller
{

    /**
     * 标准版获取所有数据
     * @param array|string $whereTime
     * @param string $type
     * @return array
     */
    public function chartsDataAllStandard($whereTime,$type){
        $where = ['status'=>['=',0], 'place'=>['=','web_gzjd'],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectMonthStandard($where,$whereTime,'mailsp,usetime,createtime');
        $data = collection($data)->toArray(); //将对象转换成数组返回
        if(!empty($data)){
            foreach ($data as $k=>$v){
                $data[$k]['createtime'] = date('Y-m',strtotime($v['createtime']));
            }
        }
        return $data;
    }


    /**
     * imap/smtp接口获取所有数据
     * @param array|string $whereTime
     * @param bool $overtime
     * @param string $type
     * @return array
     */
    public function chartsDataAllInterface($whereTime,$type,$overtime=false){
        $overtime ? $where = ['place'=>['like','%'.'imap'.'%'],'type'=>['=',$type],'usetime'=>['>=',20]] : $where = ['place'=>['like','%'.'imap'.'%'],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectMonthInterface($where,$whereTime,'mailsp,usetime,createtime');
        $data = collection($data)->toArray(); //将对象转换成数组返回
        if(!empty($data)){
            foreach ($data as $k=>$v){
                $data[$k]['createtime'] = date('Y-m',strtotime($v['createtime']));
            }
        }
        return $data;
    }


    /**
     * 酷版获取所有数据
     * @param array|string $whereTime
     * @param string $type
     * @return array
     */
    public function chartsDataAllCool($whereTime,$type){
        $where = ['status'=>['=',0],'type'=>['=',$type]];
        $data = Loader::model('Webmail')->selectMonthCool($where,$whereTime,'place,usetime,createtime');
        $data = collection($data)->toArray(); //将对象转换成数组返回
        if(!empty($data)){
            foreach ($data as $k=>$v){
                $data[$k]['createtime'] = date('Y-m',strtotime($v['createtime']));
            }
        }
        return $data;
    }


    /**
     * 标准版数据处理
     * @param array $arr_139_3
     * @param array $arr_139_6
     * @param array $arr_139_hui
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @param string $type
     * @return array
     */
    public function chartsAllStandard(array $arr_139_3,array $arr_139_6,array $arr_139_hui,array $arr_163,array $arr_189,array $arr_qq,array $arr_sina,$type){
        $common = new CommonStandard();
        $data['title'] = $common->getTypeTitle($type);
        !empty($arr_139_3) ? $data['data_139_3'] = array_values($arr_139_3) : $data['data_139_3']=null;
        !empty($arr_139_6) ? $data['data_139_6'] = array_values($arr_139_6) : $data['data_139_6']=null;
        !empty($arr_139_hui) ? $data['data_139_hui'] = array_values($arr_139_hui) : $data['data_139_hui']=null;
        !empty($arr_163) ? $data['data_189'] = array_values($arr_163) : $data['data_189']=null;
        !empty($arr_189) ? $data['data_163'] = array_values($arr_189) : $data['data_163']=null;
        !empty($arr_qq) ? $data['data_qq'] = array_values($arr_qq) : $data['data_qq']=null;
        !empty($arr_sina) ? $data['data_sina'] = array_values($arr_sina) : $data['data_sina']=null;

        return $data;

    }

    /**
     * imap/smtp接口数据处理
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @param string $type
     * @param bool $overtime
     * @return array
     */
    public function chartsAllInterface(array $arr_139,array $arr_163,array $arr_189,array $arr_qq,array $arr_sina,$type,$overtime){
        $common = new CommonInterface();
        $data['title'] = $common->getTypeTitle($type,$overtime);
        !empty($arr_139) ? $data['data_139'] = array_values($arr_139) : $data['data_139']=null;
        !empty($arr_163) ? $data['data_189'] = array_values($arr_163) : $data['data_189']=null;
        !empty($arr_189) ? $data['data_163'] = array_values($arr_189) : $data['data_163']=null;
        !empty($arr_qq) ? $data['data_qq'] = array_values($arr_qq) : $data['data_qq']=null;
        !empty($arr_sina) ? $data['data_sina'] = array_values($arr_sina) : $data['data_sina']=null;
        return $data;

    }


    /**
     * 酷版数据处理
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @param string $type
     * @return array
     */
    public function chartsAllCool(array $arr_139,array $arr_163,array $arr_189,array $arr_qq,array $arr_sina,$type){
        $common = new CommonCool();
        $data['title'] = $common->getTypeTitle($type);
        !empty($arr_139) ? $data['data_139'] = array_values($arr_139) : $data['data_139']=null;
        !empty($arr_163) ? $data['data_189'] = array_values($arr_163) : $data['data_189']=null;
        !empty($arr_189) ? $data['data_163'] = array_values($arr_189) : $data['data_163']=null;
        !empty($arr_qq) ? $data['data_qq'] = array_values($arr_qq) : $data['data_qq']=null;
        !empty($arr_sina) ? $data['data_sina'] = array_values($arr_sina) : $data['data_sina']=null;
        return $data;

    }


    /**
     * 月份处理
     * @param array
     * @return array
     */
    public function yearData($arr){
        $data = [];
        if(!empty($arr)){
            !empty($arr["01"]) ? array_push($data,number_format($arr['01'],2)) : array_push($data,number_format(0,2));
            !empty($arr["02"]) ? array_push($data,number_format($arr['02'],2)) : array_push($data,number_format(0,2));
            !empty($arr["03"]) ? array_push($data,number_format($arr['03'],2)) : array_push($data,number_format(0,2));
            !empty($arr["04"]) ? array_push($data,number_format($arr['04'],2)) : array_push($data,number_format(0,2));
            !empty($arr["05"]) ? array_push($data,number_format($arr['05'],2)) : array_push($data,number_format(0,2));
            !empty($arr["06"]) ? array_push($data,number_format($arr['06'],2)) : array_push($data,number_format(0,2));
            !empty($arr["07"]) ? array_push($data,number_format($arr['07'],2)) : array_push($data,number_format(0,2));
            !empty($arr["08"]) ? array_push($data,number_format($arr['08'],2)) : array_push($data,number_format(0,2));
            !empty($arr["09"]) ? array_push($data,number_format($arr['09'],2)) : array_push($data,number_format(0,2));
            !empty($arr["10"]) ? array_push($data,number_format($arr['10'],2)) : array_push($data,number_format(0,2));
            !empty($arr["11"]) ? array_push($data,number_format($arr['11'],2)) : array_push($data,number_format(0,2));
            !empty($arr["12"]) ? array_push($data,number_format($arr['12'],2)) : array_push($data,number_format(0,2));
        }
        return $data;
    }



    /**
     * 计算均值
     * @param array
     * @return array
     */
    public function avgChartsData(array $arr){
        $data = [];
        if(!empty($arr)){
            $result = [];
            foreach($arr as $key=>$v){
                $result[date('m',strtotime($v['createtime']))][] = $v;
            }
            if(!empty($result)){
                foreach ($result as $k=>$v){
                    $arr_new = array_column($v,'usetime');
                    $count = count($arr_new);
                    $count == 0 ? $avg=number_format(0,2) : $avg = number_format(floor(array_sum($arr_new)/$count*100)/100,2);
                    $data[$k] = $avg;
                }
            }
        }
        return $data;
    }




}