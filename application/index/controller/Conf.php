<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/2
 * Time: 14:15
 */

namespace app\index\controller;
use app\lib\exception\ParamsException;
use app\index\controller\Api as api;
use think\Controller;

class Conf extends Controller
{
    private static $path_conf = 'conf/conf.txt';
    private static $path_fpt = 'conf/ftp.txt';
    private  static $key = "1234567890654321";
    private static $iv = "1234567890123456";

    /**
     * 更新系统设置
     * @param array|string $params
     * @return array
     * @throws
     */
    public function updateConf($params){
        if(empty($params)){
            throw new ParamsException(1010);
        }
        $string = [];
        if($params && is_array($params)){
            foreach ($params as $key=> $value){
                $string[] = $key.'='.$value;
            }
        }
        $data = implode(',',$string);
        if(file_put_contents(self::$path_conf,$data)){
            $api = new api();
            return $api->result_data();
        }else{
            throw new ParamsException();
        }
    }

    /**
     * 获取系统设置接口
     * @return array
     */
    public function getConf(){
        $api = new api();
        return $api->result_data($this->getSiteConf());
    }

    /**
     * 获取缓存设置方法
     * @throws
     * @return array
     */
    public function getSiteConf(){
        file_exists(self::$path_conf) ? $result = file_get_contents(self::$path_conf) : $result = '';
        $data = [];
        if(!empty($result)){
            $arr = str_replace(['=',','],['"=>"','","'],'["'.$result.'"]');
            eval("\$data"." = $arr;");   // 把字符串作为PHP代码执行
        }
        return $data;

    }


    /**
     * 更新FTP设置
     * @param array|string $params
     * @return array
     * @throws
     */
    public function updateFtp($params){
        $aes = new Aes(self::$key,self::$iv);
        //解密
        $result = json_decode($aes->decrypt($params),true);
        //校验
        if($result['host'] == null){
            throw new ParamsException(1011);
        }
        if($result['port'] == null){
            throw new ParamsException(1012);
        }
        if($result['username'] == null){
            throw new ParamsException(1013);
        }
        if($result['password'] == null){
            throw new ParamsException(1014);
        }
        if($result['place'] == null){
            throw new ParamsException(1015);
        }
        if($result['path'] == null){
            throw new ParamsException(1016);
        }
        if(file_put_contents(self::$path_fpt,$params)){
            $api = new api();
            return $api->result_data();
        }else{
            throw new ParamsException();
        }

    }


    /**
     * 获取FTP设置接口
     * @return array
     */
    public function getFtp(){
        file_exists(self::$path_fpt) ? $result = file_get_contents(self::$path_fpt) : $result = null;
        $api = new api();
        return $api->result_data($result);
    }

    /**
     * 获取FTP设置解密后的数据
     * @return array
     */
    public function getFtpConf(){
        file_exists(self::$path_fpt) ? $result = file_get_contents(self::$path_fpt) : $result = [];
        $data = [];
        if(!empty($result)){
            $aes = new Aes(self::$key,self::$iv);
            //解密
            $data = json_decode($aes->decrypt($result),true);
        }
        return $data;
    }


}