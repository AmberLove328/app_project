<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/8
 * Time: 16:57
 */

namespace app\index\controller;
use think\Cache;
use think\Controller;
use app\index\controller\Api as api;
class MailYear extends Controller
{

    /**
     * 标准版性能指标接口
     * @return array
     */
    public function chartsYearStandard(){
        if(!empty($_GET['createtime'])){
            $whereTime = array($_GET['createtime'].'-01-01 00:00:00',date('Y',strtotime(($_GET['createtime']+1).date("-m-d G:i:s",$_GET['createtime']))).'-01-01 00:00:00');
            $eTime = $_GET['createtime'];
        }else{
            $whereTime = array(date('Y',time()).'-01-01 00:00:00',date('Y',strtotime((date('Y',time())+1).date("-m-d G:i:s",date('Y',time())))).'-01-01 00:00:00');
            $eTime = date('Y',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 1;
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || intval($result['cache']) <= 0){
            $commonYear = new CommonYear();
            $common = new CommonStandard();
            $whereTimeBefore = array($whereTime['0'],date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")));
            $whereTimeAfter = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")),$whereTime['1']);
            $dataBefore = $commonYear->chartsDataAllStandard($whereTimeBefore,$type);
            $dataAfter = $commonYear->chartsDataAllStandard($whereTimeAfter,$type);
            $data = $commonYear->chartsAllStandard(
                $commonYear->yearData($commonYear->avgChartsData($common->data_139_3($dataBefore)) + $commonYear->avgChartsData($common->data_139_3($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_139_6($dataBefore)) + $commonYear->avgChartsData($common->data_139_6($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_139_hui($dataBefore)) + $commonYear->avgChartsData($common->data_139_hui($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_163($dataBefore)) + $commonYear->avgChartsData($common->data_163($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_189($dataBefore)) + $commonYear->avgChartsData($common->data_189($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_qq($dataBefore)) + $commonYear->avgChartsData($common->data_qq($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_sina($dataBefore)) + $commonYear->avgChartsData($common->data_sina($dataAfter))),
                $type
            );
        }else{
            if(Cache::get($type.'yearStandard'.$eTime)){
                $data = Cache::get($type.'yearStandard'.$eTime);
            }else{
                $commonYear = new CommonYear();
                $common = new CommonStandard();
                $whereTimeBefore = array($whereTime['0'],date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")));
                $whereTimeAfter = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")),$whereTime['1']);
                $dataBefore = $commonYear->chartsDataAllStandard($whereTimeBefore,$type);
                $dataAfter = $commonYear->chartsDataAllStandard($whereTimeAfter,$type);
                $data = $commonYear->chartsAllStandard(
                    $commonYear->yearData($commonYear->avgChartsData($common->data_139_3($dataBefore)) + $commonYear->avgChartsData($common->data_139_3($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_139_6($dataBefore)) + $commonYear->avgChartsData($common->data_139_6($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_139_hui($dataBefore)) + $commonYear->avgChartsData($common->data_139_hui($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_163($dataBefore)) + $commonYear->avgChartsData($common->data_163($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_189($dataBefore)) + $commonYear->avgChartsData($common->data_189($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_qq($dataBefore)) + $commonYear->avgChartsData($common->data_qq($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_sina($dataBefore)) + $commonYear->avgChartsData($common->data_sina($dataAfter))),
                    $type
                );
                Cache::set($type.'yearStandard'.$eTime,$data,$result['cache']*60);
            }
        }
        $api = new api();
        return $api->result_data($data);
    }


    /**
     * imap/smtp接口性能指标
     * @return array
     */
    public function chartsYearInterface(){
        if(!empty($_GET['createtime'])){
            $whereTime = array($_GET['createtime'].'-01-01 00:00:00',date('Y',strtotime(($_GET['createtime']+1).date("-m-d G:i:s",$_GET['createtime']))).'-01-01 00:00:00');
            $eTime = $_GET['createtime'];
        } else{
            $whereTime = array(date('Y',time()).'-01-01 00:00:00',date('Y',strtotime((date('Y',time())+1).date("-m-d G:i:s",date('Y',time())))).'-01-01 00:00:00');
            $eTime = date('Y',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 3;
        !empty($_GET['overtime']) ? $overtime = $_GET['overtime'] : $overtime=0;
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || intval($result['cache']) <= 0){
            $commonMonth = new CommonMonth();
            $commonYear = new CommonYear();
            $whereTimeQ1 = array($whereTime['0'],date('Y-m-d H:i:s',strtotime($whereTime['0']."+3 month")));
            $whereTimeQ2 = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+3 month")),date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")));
            $whereTimeQ3 = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")),date('Y-m-d H:i:s',strtotime($whereTime['0']."+9 month")));
            $whereTimeQ4 = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+9 month")),$whereTime['1']);
            $dataQ1 = $commonYear->chartsDataAllInterface($whereTimeQ1,$type,$overtime);
            $dataQ2 = $commonYear->chartsDataAllInterface($whereTimeQ2,$type,$overtime);
            $dataQ3 = $commonYear->chartsDataAllInterface($whereTimeQ3,$type,$overtime);
            $dataQ4 = $commonYear->chartsDataAllInterface($whereTimeQ4,$type,$overtime);
            if($type == 3 || $type == 20){
                $avg_139 = $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ4));
                $avg_163 = $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ4));
                $avg_189 = $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ4));
                $avg_qq = $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ4));
                $avg_sina = $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ4));
            }else{
                $avg_139 = $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ4));
                $avg_163 = $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ4));
                $avg_189 = $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ4));
                $avg_qq = $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ4));
                $avg_sina = $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ1)) +
                    $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ2)) +
                    $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ3)) +
                    $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ4));
            }
            $data = $commonYear->chartsAllInterface(
                $commonYear->yearData($avg_139),
                $commonYear->yearData($avg_163),
                $commonYear->yearData($avg_189),
                $commonYear->yearData($avg_qq),
                $commonYear->yearData($avg_sina),
                $type,
                $overtime
            );
        }else{
            if(Cache::get($type.$overtime.'yearInterface'.$eTime)){
                $data = Cache::get($type.$overtime.'yearInterface'.$eTime);
            }else{
                $commonMonth = new CommonMonth();
                $commonYear = new CommonYear();
                $whereTimeQ1 = array($whereTime['0'],date('Y-m-d H:i:s',strtotime($whereTime['0']."+3 month")));
                $whereTimeQ2 = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+3 month")),date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")));
                $whereTimeQ3 = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")),date('Y-m-d H:i:s',strtotime($whereTime['0']."+9 month")));
                $whereTimeQ4 = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+9 month")),$whereTime['1']);
                $dataQ1 = $commonYear->chartsDataAllInterface($whereTimeQ1,$type,$overtime);
                $dataQ2 = $commonYear->chartsDataAllInterface($whereTimeQ2,$type,$overtime);
                $dataQ3 = $commonYear->chartsDataAllInterface($whereTimeQ3,$type,$overtime);
                $dataQ4 = $commonYear->chartsDataAllInterface($whereTimeQ4,$type,$overtime);
                if($type == 3 || $type == 20){
                    $avg_139 = $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_139_imap($dataQ4));
                    $avg_163 = $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_163_imap($dataQ4));
                    $avg_189 = $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_189_imap($dataQ4));
                    $avg_qq = $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_qq_imap($dataQ4));
                    $avg_sina = $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_sina_imap($dataQ4));
                }else{
                    $avg_139 = $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_139_smtp($dataQ4));
                    $avg_163 = $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_163_smtp($dataQ4));
                    $avg_189 = $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_189_smtp($dataQ4));
                    $avg_qq = $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_qq_smtp($dataQ4));
                    $avg_sina = $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ1)) +
                        $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ2)) +
                        $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ3)) +
                        $commonYear->avgChartsData($commonMonth->data_sina_smtp($dataQ4));
                }
                $data = $commonYear->chartsAllInterface(
                    $commonYear->yearData($avg_139),
                    $commonYear->yearData($avg_163),
                    $commonYear->yearData($avg_189),
                    $commonYear->yearData($avg_qq),
                    $commonYear->yearData($avg_sina),
                    $type,
                    $overtime
                );
                Cache::set($type.$overtime.'yearInterface'.$eTime,$data,$result['cache']*60);
            }
        }

        $api = new api();
        return $api->result_data($data);
    }


    /**
     * 酷版性能指标
     * @return array
     */
    public function chartsYearCool(){
        if(!empty($_GET['createtime'])){
            $whereTime = array($_GET['createtime'].'-01-01 00:00:00',date('Y',strtotime(($_GET['createtime']+1).date("-m-d G:i:s",$_GET['createtime']))).'-01-01 00:00:00');
            $eTime = $_GET['createtime'];
        }else{
            $whereTime = array(date('Y',time()).'-01-01 00:00:00',date('Y',strtotime((date('Y',time())+1).date("-m-d G:i:s",date('Y',time())))).'-01-01 00:00:00');
            $eTime = date('Y',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 2;
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || intval($result['cache']) <= 0){
            $commonYear = new CommonYear();
            $common = new CommonCool();
            $whereTimeBefore = array($whereTime['0'],date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")));
            $whereTimeAfter = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")),$whereTime['1']);
            $dataBefore = $commonYear->chartsDataAllCool($whereTimeBefore,$type);
            $dataAfter = $commonYear->chartsDataAllCool($whereTimeAfter,$type);
            $data = $commonYear->chartsAllCool(
                $commonYear->yearData($commonYear->avgChartsData($common->data_139($dataBefore)) + $commonYear->avgChartsData($common->data_139($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_163($dataBefore)) + $commonYear->avgChartsData($common->data_163($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_189($dataBefore)) + $commonYear->avgChartsData($common->data_189($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_qq($dataBefore)) + $commonYear->avgChartsData($common->data_qq($dataAfter))),
                $commonYear->yearData($commonYear->avgChartsData($common->data_sina($dataBefore)) + $commonYear->avgChartsData($common->data_sina($dataAfter))),
                $type
            );
        }else{
            if(Cache::get($type.'yearCool'.$eTime)){
                $data = Cache::get($type.'yearCool'.$eTime);
            }else{
                $commonYear = new CommonYear();
                $common = new CommonCool();
                $whereTimeBefore = array($whereTime['0'],date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")));
                $whereTimeAfter = array(date('Y-m-d H:i:s',strtotime($whereTime['0']."+6 month")),$whereTime['1']);
                $dataBefore = $commonYear->chartsDataAllCool($whereTimeBefore,$type);
                $dataAfter = $commonYear->chartsDataAllCool($whereTimeAfter,$type);
                $data = $commonYear->chartsAllCool(
                    $commonYear->yearData($commonYear->avgChartsData($common->data_139($dataBefore)) + $commonYear->avgChartsData($common->data_139($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_163($dataBefore)) + $commonYear->avgChartsData($common->data_163($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_189($dataBefore)) + $commonYear->avgChartsData($common->data_189($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_qq($dataBefore)) + $commonYear->avgChartsData($common->data_qq($dataAfter))),
                    $commonYear->yearData($commonYear->avgChartsData($common->data_sina($dataBefore)) + $commonYear->avgChartsData($common->data_sina($dataAfter))),
                    $type
                );
                Cache::set($type.'yearCool'.$eTime,$data,$result['cache']*60);
            }
        }

        $api = new api();
        return $api->result_data($data);
    }


}