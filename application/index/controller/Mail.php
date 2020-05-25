<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 17:11
 */

namespace app\index\controller;
use think\Cache;
use think\Controller;
use app\index\controller\Api as api;

class Mail extends Controller{

    /**
     * 139邮箱
     * @return array
     */
    public function charts_139(){
        if(!empty($_GET['createtime'])){
            $whereTime = [$_GET['createtime'],date('Y-m-d',strtotime($_GET['createtime']."+1 day"))];
            $time = $_GET['createtime'];
        }else{
            $whereTime = 'today';
            $time = date('Y-m-d',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 1;
        !empty($_GET['place']) ? $place = $_GET['place'] : $place = 'web_gzjd';
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || intval($result['cache']) <= 0){
            $common=new CommonStandard();
            //获取所有139数据
            $data_all = $common->charts_data_139($whereTime,$type,$place);
            //过滤出139_3.0数据
            $data_139 = $common->data_139_3($data_all);
            $data = $common->chartsData($data_139,$type,$time);
            $data['place'] = $place;
        }else{
            if(Cache::get($place.$type.'139'.$time)){
                $data = Cache::get($place.$type.'139'.$time);
            }else{
                $common=new CommonStandard();
                //获取所有139数据
                $data_all = $common->charts_data_139($whereTime,$type,$place);
                //过滤出139_3.0数据
                $data_139 = $common->data_139_3($data_all);
                $data = $common->chartsData($data_139,$type,$time);
                $data['place'] = $place;
                Cache::set($place.$type.'139'.$time,$data,intval($result['cache'])*60);
            }
        }

        $api = new api();
        return $api->result_data($data);
    }


    /**
     * 163邮箱
     * @return array
     */
    public function charts_163(){
        if(!empty($_GET['createtime'])){
            $whereTime = [$_GET['createtime'],date('Y-m-d',strtotime($_GET['createtime']."+1 day"))];
            $time = $_GET['createtime'];
        }else{
            $whereTime = 'today';
            $time = date('Y-m-d',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 1;
        !empty($_GET['place']) ? $place = $_GET['place'] : $place = 'web_gzjd';
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || $result['cache'] <= 0){
            $common=new CommonStandard();
            //获取所有163数据
            $data_all = $common->charts_data_163($whereTime,$type,$place);
            //过滤出163数据
            $data_163 = $common->data_163($data_all);
            $data = $common->chartsData($data_163,$type,$time);
            $data['place'] = $place;
        }else{
            if(Cache::get($place.$type.'163'.$time)){
                $data = Cache::get($place.$type.'163'.$time);
            }else{
                $common=new CommonStandard();
                //获取所有163数据
                $data_all = $common->charts_data_163($whereTime,$type,$place);
                //过滤出163数据
                $data_163 = $common->data_163($data_all);
                $data = $common->chartsData($data_163,$type,$time);
                $data['place'] = $place;
                Cache::set($place.$type.'163'.$time,$data,$result['cache']*60);
            }
        }

        $api = new api();
        return $api->result_data($data);
    }


    /**
     * 189邮箱
     * @return array
     */
    public function charts_189(){
        if(!empty($_GET['createtime'])){
            $whereTime = [$_GET['createtime'],date('Y-m-d',strtotime($_GET['createtime']."+1 day"))];
            $time = $_GET['createtime'];
        }else{
            $whereTime = 'today';
            $time = date('Y-m-d',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 1;
        !empty($_GET['place']) ? $place = $_GET['place'] : $place = 'web_gzjd';
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || $result['cache'] <= 0){
            $common=new CommonStandard();
            //获取所有189数据
            $data_all = $common->charts_data_189($whereTime,$type,$place);
            //过滤出189数据
            $data_189 = $common->data_189($data_all);
            $data = $common->chartsData($data_189,$type,$time);
            $data['place'] = $place;
        }else{
            if(Cache::get($place.$type.'189'.$time)){
                $data = Cache::get($place.$type.'189'.$time);
            }else{
                $common=new CommonStandard();
                //获取所有189数据
                $data_all = $common->charts_data_189($whereTime,$type,$place);
                //过滤出189数据
                $data_189 = $common->data_189($data_all);
                $data = $common->chartsData($data_189,$type,$time);
                $data['place'] = $place;
                Cache::set($place.$type.'189'.$time,$data,$result['cache']*60);
            }
        }
        $api = new api();
        return $api->result_data($data);
    }


    /**
     * qq邮箱
     * @return array
     */
    public function charts_qq(){
        if(!empty($_GET['createtime'])){
            $whereTime = [$_GET['createtime'],date('Y-m-d',strtotime($_GET['createtime']."+1 day"))];
            $time = $_GET['createtime'];
        }else{
            $whereTime = 'today';
            $time = date('Y-m-d',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 1;
        !empty($_GET['place']) ? $place = $_GET['place'] : $place = 'web_gzjd';
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || $result['cache'] <= 0){
            $common=new CommonStandard();
            //获取所有qq数据
            $data_all = $common->charts_data_qq($whereTime,$type,$place);
            //过滤出qq数据
            $data_qq = $common->data_qq($data_all);
            $data = $common->chartsData($data_qq,$type,$time);
            $data['place'] = $place;
        }else{
            if(Cache::get($place.$type.'qq'.$time)){
                $data = Cache::get($place.$type.'qq'.$time);
            }else{
                $common=new CommonStandard();
                //获取所有qq数据
                $data_all = $common->charts_data_qq($whereTime,$type,$place);
                //过滤出qq数据
                $data_qq = $common->data_qq($data_all);
                $data = $common->chartsData($data_qq,$type,$time);
                $data['place'] = $place;
                Cache::set($place.$type.'qq'.$time,$data,$result['cache']*60);
            }
        }
        $api = new api();
        return $api->result_data($data);
    }


    /**
     * 新浪邮箱
     * @return array
     */
    public function charts_sina(){
        if(!empty($_GET['createtime'])){
            $whereTime = [$_GET['createtime'],date('Y-m-d',strtotime($_GET['createtime']."+1 day"))];
            $time = $_GET['createtime'];
        }else{
            $whereTime = 'today';
            $time = date('Y-m-d',time());
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 1;
        !empty($_GET['place']) ? $place = $_GET['place'] : $place = 'web_gzjd';
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if(empty($result['cache']) || $result['cache'] == null || $result['cache'] <= 0){
            $common=new CommonStandard();
            //获取所有sina数据
            $data_all = $common->charts_data_sina($whereTime,$type,$place);
            //过滤出sina数据
            $data_sina = $common->data_sina($data_all);
            $data = $common->chartsData($data_sina,$type,$time);
            $data['place'] = $place;
        }else{
            if(Cache::get($place.$type.'sina'.$time)){
                $data = Cache::get($place.$type.'sina'.$time);
            }else{
                $common=new CommonStandard();
                //获取所有sina数据
                $data_all = $common->charts_data_sina($whereTime,$type,$place);
                //过滤出sina数据
                $data_sina = $common->data_sina($data_all);
                $data = $common->chartsData($data_sina,$type,$time);
                $data['place'] = $place;
                Cache::set($place.$type.'sina'.$time,$data,$result['cache']*60);
            }
        }
        $api = new api();
        return $api->result_data($data);
    }




}