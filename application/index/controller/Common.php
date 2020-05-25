<?php
/**
 * Created by PhpStorm.
 * User: v_luyan
 * Date: 2019/8/13
 * Time: 10:19
 */

namespace app\index\controller;

use app\lib\exception\ParamsException;
use think\Loader;
use think\Controller;
class Common extends Controller
{
    /**
     * 0-没有数据，1-有数据但无排名是红的，2-有数据且有排名是红的
     */
    public $standardIsOver = '0';
    public $interfaceIsOver = '0';
    public $coolIsOver = '0';


    public $arr_standard = [];
    public $arr_interface = [];
    public $arr_cool = [];

    private static $path_over = 'conf/over.txt';


    /**
     * 获取红点的数据
     *
     * @throws
     */
    public function _initialize()
    {
        $whereTime = 'today';
        $com = new Index();
        $standard = $com->dataStandard($whereTime,'web_gzjd');
        $interface = $com->dataInterface($whereTime);
        $cool = $com->dataCool($whereTime);
        $this->arr_standard = $standard;
        $this->arr_interface = $interface;
        $this->arr_cool = $cool;
        if(in_array('true',$standard['over'])){
            $this->standardIsOver = '2';
        }else{
            foreach ($standard['avg'] as $k=>$v){
                if($v != "0.00"){
                    $this->standardIsOver = '1';
                    break;
                }
            }
        }
        if(in_array('true',$interface['over'])){
            $this->interfaceIsOver = '2';
        }else{
            foreach ($interface['avg'] as $k=>$v){
                if($v != "0.00"){
                    $this->interfaceIsOver = '1';
                    break;
                }
            }
        }
        if(in_array('true',$cool['over'])){
            $this->coolIsOver = '2';
        }else{
            foreach ($cool['avg'] as $k=>$v){
                if($v != "0.00"){
                    $this->coolIsOver = '1';
                    break;
                }
            }
        }
    }

    /**
     * 发送告警信息
     * @param array $standard
     * @param array $interface
     * @param array $cool
     * @throws
     */
    public function getConfig($standard,$interface,$cool){
        $time = date('Y-m-d H:i:s',time());
        $dTime = date('Y-m-d',time());
        $sTime = date('Y-m-d 12:00:00',time());

        file_exists(self::$path_over) ? $result = file_get_contents(self::$path_over) : $result = '';
        //判断是否执行发送告警消息
        if($time >= $sTime && $result !== $dTime){
            $this->messageSave($standard,0);
            $this->messageSave($interface,1);
            $this->messageSave($cool,2);
            file_put_contents(self::$path_over,$dTime);
        }

    }


    /**
     * 消息保存
     * @param array $data
     * @param int $type
     * @throws
     */
    private function messageSave($data,$type){
        if($type == 0){
            //标准版类型
            $target = '标准版';
            $arr = ['打开首页','邮箱登录','打开写信页','读邮件','下载1M附件','发送邮件','搜索邮件','接收外域','超大附件下载'];
            foreach ($data['avg'] as $k=>$v){
                if($v == '0.00'){
                    unset($data['avg'][$k]);
                }
            }
            if(!empty($data['avg'])){
                $map = [];
                //139_3.0
                foreach ($data['data_139_3'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'139_3.0邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //139_6.0
                foreach ($data['data_139_6'] as $k2=>$v2){
                    if($v2 == "0.00" || $v2 == "" || $v2 == null){
                        $msg = $arr[$k2].' - '.'没有数据';
                        array_push($map,['competitor'=>'139_6.0邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //139_hui
                foreach ($data['data_139_hui'] as $k3=>$v3){
                    if($v3 == "0.00" || $v3 == "" || $v3 == null){
                        $msg = $arr[$k3].' - '.'没有数据';
                        array_push($map,['competitor'=>'139_灰度邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //189
                foreach ($data['data_189'] as $k3=>$v3){
                    if($v3 == "0.00" || $v3 == "" || $v3 == null){
                        $msg = $arr[$k3].' - '.'没有数据';
                        array_push($map,['competitor'=>'189邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //163
                foreach ($data['data_163'] as $k3=>$v3){
                    if($v3 == "0.00" || $v3 == "" || $v3 == null){
                        $msg = $arr[$k3].' - '.'没有数据';
                        array_push($map,['competitor'=>'163邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //qq
                foreach ($data['data_qq'] as $k3=>$v3){
                    if($v3 == "0.00" || $v3 == "" || $v3 == null){
                        $msg = $arr[$k3].' - '.'没有数据';
                        array_push($map,['competitor'=>'qq邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //sina
                foreach ($data['data_sina'] as $k3=>$v3){
                    if($v3 == "0.00" || $v3 == "" || $v3 == null){
                        $msg = $arr[$k3].' - '.'没有数据';
                        array_push($map,['competitor'=>'新浪邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                Loader::model('Message')->saveData($map);
            }

        }else if($type == 1){
            //IMAP/SMTP类型
            $target = 'IMAP/SMTP接口';
            $arr = ['imap_下载30字正文时长','imap_下载30字正文时长超过20S成功率','smtp_发送1M附件时长','imap_下载100封邮件头平均用时'];
            foreach ($data['avg'] as $k=>$v){
                if($v == '0.00'){
                    unset($data['avg'][$k]);
                }
            }
            if(!empty($data['avg'])){
                $map = [];
                //139
                foreach ($data['data_139'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'139邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //189
                foreach ($data['data_189'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'189邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //163
                foreach ($data['data_163'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'163邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //qq
                foreach ($data['data_qq'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'qq邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //sina
                foreach ($data['data_sina'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'新浪邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                Loader::model('Message')->saveData($map);
            }
        }else if($type == 2){
            //酷版类型
            $target = '酷版';
            $arr = ['登录邮箱','打开写信页','打开未读邮件','附件下载'];
            foreach ($data['avg'] as $k=>$v){
                if($v == '0.00'){
                    unset($data['avg'][$k]);
                }
            }
            if(!empty($data['avg'])){
                $map = [];
                //139
                foreach ($data['data_139'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'139邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //189
                foreach ($data['data_189'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'189邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //163
                foreach ($data['data_163'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'163邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //qq
                foreach ($data['data_qq'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'qq邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                //sina
                foreach ($data['data_sina'] as $k=>$v){
                    if($v == "0.00" || $v == "" || $v == null){
                        $msg = $arr[$k].' - '.'没有数据';
                        array_push($map,['competitor'=>'新浪邮箱','target'=>$target,'message'=>$msg,'time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
                Loader::model('Message')->saveData($map);
            }

        }else{
            //未知类型抛异常
            throw new ParamsException(1001);
        }
    }



}