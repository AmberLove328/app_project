<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/3
 * Time: 11:40
 */

namespace app\index\controller;
use think\Controller;
use think\Loader;

class CommonMonth extends Controller
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
                $data[$k]['createtime'] = date('Y-m-d',strtotime($v['createtime']));
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
                $data[$k]['createtime'] = date('Y-m-d',strtotime($v['createtime']));
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
                $data[$k]['createtime'] = date('Y-m-d',strtotime($v['createtime']));
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
     * @param array $time
     * @return array
     */
    public function chartsAllStandard(array $arr_139_3,array $arr_139_6,array $arr_139_hui,array $arr_163,array $arr_189,array $arr_qq,array $arr_sina,$type,$time){
        $common = new CommonStandard();
        $data['title'] = $common->getTypeTitle($type);
        $data['time'] = $time;
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
     * @param array $time
     * @return array
     */
    public function chartsAllInterface(array $arr_139,array $arr_163,array $arr_189,array $arr_qq,array $arr_sina,$type,$overtime,$time){
        $common = new CommonInterface();
        $data['title'] = $common->getTypeTitle($type,$overtime);
        $data['time'] = $time;
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
     * @param array $time
     * @return array
     */
    public function chartsAllCool(array $arr_139,array $arr_163,array $arr_189,array $arr_qq,array $arr_sina,$type,$time){
        $common = new CommonCool();
        $data['title'] = $common->getTypeTitle($type);
        $data['time'] = $time;
        !empty($arr_139) ? $data['data_139'] = array_values($arr_139) : $data['data_139']=null;
        !empty($arr_163) ? $data['data_189'] = array_values($arr_163) : $data['data_189']=null;
        !empty($arr_189) ? $data['data_163'] = array_values($arr_189) : $data['data_163']=null;
        !empty($arr_qq) ? $data['data_qq'] = array_values($arr_qq) : $data['data_qq']=null;
        !empty($arr_sina) ? $data['data_sina'] = array_values($arr_sina) : $data['data_sina']=null;
        return $data;

    }


    /**
     * 处理139 imap数据
     * @param array $arr
     * @return array
     */
    public function data_139_imap($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'imap.139.com' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理139 smtp数据
     * @param array $arr
     * @return array
     */
    public function data_139_smtp($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'smtp.139.com' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理163 imap数据
     * @param array $arr
     * @return array
     */
    public function data_163_imap($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'imap.163.com' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理163 smtp数据
     * @param array $arr
     * @return array
     */
    public function data_163_smtp($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'smtp.163.com' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理189 imap数据
     * @param array $arr
     * @return array
     */
    public function data_189_imap($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'imap.189.cn' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理189 smtp数据
     * @param array $arr
     * @return array
     */
    public function data_189_smtp($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'smtp.189.cn' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理qq imap数据
     * @param array $arr
     * @return array
     */
    public function data_qq_imap($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'imap.qq.com' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理qq smtp数据
     * @param array $arr
     * @return array
     */
    public function data_qq_smtp($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'smtp.qq.com' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理sina imap数据
     * @param array $arr
     * @return array
     */
    public function data_sina_imap($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'imap.sina.cn' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理sina smtp数据
     * @param array $arr
     * @return array
     */
    public function data_sina_smtp($arr=[]){
        if(!empty($arr)){
            foreach($arr as $key=>$v){
                //过滤掉其他数据且用时为0不计入均值
                if($v['mailsp'] != 'smtp.sina.cn' || $v['usetime'] == 0){
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }


    /**
     * 计算均值
     * @param array $arr
     * @param array $time
     * @return array
     */
    public function avgChartsData(array $arr,$time){
        $data = [];
        if(!empty($arr)){
            $result = [];
            foreach($arr as $key=>$v){
                $result[$v['createtime']][] = $v;
            }
            foreach ($time as $k=>$v){
                if(!array_key_exists($v,$result)){
                    $result[$v] = [['usetime'=>'0','createtime'=>$v]];
                }
            }
            if(!empty($result)){
                foreach ($result as $k=>$v){
                    if(!empty($v)){
                        $arr_new = array_column($v,'usetime');
                        $count = count($arr_new);
                        $count == 0 ? $avg = 0 : $avg = floor(array_sum($arr_new)/$count*100)/100;
                        $data[$k] = $avg;
                    }else{
                        $data[$k] = 0;
                    }
                }
            }
        }
        //将结果重新按照时间排序
        $newArr = $valArr = array();
        foreach ($data as $k=>$v){
            array_push($valArr,$k);
        }
        asort($valArr);
        reset($valArr); //指针指向数组第一个值
        foreach($valArr as $key=>$value) {
            $newArr[$value] = $data[$value];
        }
        return $newArr;
    }


}