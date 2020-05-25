<?php
/**
 * Created by PhpStorm.
 * User: v_luyan
 * Date: 2019/7/19
 * Time: 14:20
 */

namespace app\index\controller;
use app\index\controller\Api as api;
use app\lib\exception\ParamsException;
use think\Controller;
use think\Loader;


class PacketInternetGroper extends Controller
{

    //本地文件位置
    private static $url = 'static/';

    //分批数据
    private $limit = 1000;

    /**
     * 获取均值数据
     * @param array $where
     * @return float
     * @throws
     */
    private function pingAvg($where){
        return Loader::model('Ping')->pingAvg($where);
    }


    /**
     * ping日均值接口
     * @return array
     * @throws
     */
    public function chartsPing(){
        !empty($_GET['pingtime']) ? $time = $_GET['pingtime'] : $time = date('Y-m-d',time());
        $where_139=array('status'=>['=',1],'mailsp'=>['=','mail.10086.cn'],'pingtime'=>['like',$time.'%']);
        $where_163=array('status'=>['=',1],'mailsp'=>['=','mail.163.com'],'pingtime'=>['like',$time.'%']);
        $where_qq=array('status'=>['=',1],'mailsp'=>['=','mail.qq.com'],'pingtime'=>['like',$time.'%']);
        $data['time'] = array($time);
        $data['mail_139'] = array(number_format($this->pingAvg($where_139),2));
        $data['mail_163'] = array(number_format($this->pingAvg($where_163),2));
        $data['mail_qq'] = array(number_format($this->pingAvg($where_qq),2));
        $api = new api();
        return $api->result_data($data);
    }

    /**
     * ping值大于日均值*1.5
     * @return array
     * @throws
     */
    public function chartsPingExceed(){
        !empty($_GET['pingtime']) ? $time = $_GET['pingtime'] : $time = date('Y-m-d',time());
        !empty($_GET['mail']) ? $mail = $_GET['mail'] : $mail = 'mail.10086.cn';
        $where=array('status'=>['=',1],'mailsp'=>['=',$mail],'pingtime'=>['like',$time.'%']);
        $avg = $this->pingAvg($where);
        $where_new = array('status'=>['=',1],'mailsp'=>['=',$mail],'pingtime'=>['like',$time.'%'],'usetime'=>['>=',$avg*1.5]);
        $result = Loader::model('Ping')->selectAll($where_new,'pingtime,usetime');
        $result = collection($result)->toArray();
        $data = [];
        $pingTime = [];
        $useTime = [];
        if(!empty($result)){
            foreach ($result as $k=>$v){
                array_push($pingTime,$v['pingtime']);
                array_push($useTime,$v['usetime']);
            }
        }else{
            $pingTime = [$time];
            $useTime = [number_format(0,2)];
        }
        if($mail == 'mail.10086.cn'){
            $title = '10086';
        }elseif ($mail == 'mail.163.com'){
            $title = '163';
        }elseif ($mail == 'mail.qq.com'){
            $title = 'qq';
        }else{
            $title = '未知';
        }
        $data['pingtime'] = $pingTime;
        $data['usetime'] = $useTime;
        $data['time'] = array($time);
        $data['title'] = array($title);
        $data['avg'] = $avg;
        $api = new api();
        return $api->result_data($data);
    }



    /**
     * 通过FTP下载log文件并保存到数据库
     * @param array $params
     * @return array
     * @throws
     */
    public function ImportPingFtpData($params){
        $api = new api();
        $conf = new Conf();
        $result = $conf->getFtpConf();
        if(empty($result)){
            throw new ParamsException(1018);
        }
        $local_path = self::$url.$params['mail'].'_'.$params['pingtime'].'.log';
        $ftp_name = $params['mail'].'_'.$params['pingtime'].'.log';
        //将FTP服务器文件下载到本地
        if($api->localDown($local_path,$ftp_name)){
            $this->saveData($params['mail'],$params['pingtime'],$result['place'],$local_path);
        }else{
            throw new ParamsException(1017);
        }
        return $api->result_data();

    }


    /**
     * 通过上传log文件并保存到数据库
     * @return array
     * @throws
     */
    public function UploadPingData(){
        $file = request()->file('file'); // 获取表单上传文件
        if (empty($file)) {
            throw new ParamsException(1001);
        }else{
            $api = new api();
            $conf = new Conf();
            $result = $conf->getFtpConf();
            if(empty($result)){
                throw new ParamsException(1018);
            }
            $dir_path=iconv('utf-8','gb2312',$_SERVER['DOCUMENT_ROOT'].'/'.self::$url);
            $info = $file->move($dir_path,false);
            $name = $info->getInfo()['name'];
            unset($info);
            $arr = explode('_',$name);
            $arr_2 = explode('.',$arr[1]);
            $res = $this->saveData($arr[0],$arr_2[0],$result['place'],self::$url.$name);
            if($res){
                return $api->result_data();
            }else{
                throw new ParamsException();
            }
        }

    }


    /**
     * 将数据保存到数据库，在保存之前清空当前日期的数据
     * @param string $mail
     * @param string $time
     * @param string $place
     * @param string $local_path
     * @return bool
     * @throws
     */
    private function saveData($mail,$time,$place,$local_path){
        ini_set('max_execution_time', '0');//设置永不超时，无限执行下去直到结束
        //插入数据之前为了避免重复插入，先删除当日的mailsp数据
        $where = array('mailsp'=>$mail,'pingtime'=>['like',$time.'%'],'place'=>$place);
        Loader::model('Ping')->deleteData($where);
        //获取文件内容并分批插入数据库
        $data = $this->readContent($local_path,$time);
        if(!empty($data)){
            if(count($data) > 1000){
                $count=ceil(count($data)/$this->limit);
                //分批插入数据库
                for ($i=1;$i<=$count;$i++){
                    $offset=($i-1)*($this->limit);
                    $ids=array_slice($data,$offset,$this->limit);
                    $res = Loader::model('Ping')->saveData($ids);
                    if(!$res){
                        throw new ParamsException();
                    }
                }
            }else{
                $res = Loader::model('Ping')->saveData($data);
                if(!$res){
                    throw new ParamsException();
                }
            }
            unset($data);
            //删除本地文件
            if(file_exists($local_path)){
                unlink($local_path);
            }
            return true;
        }
        return false;

    }


    /** 读取文件内容返回数组格式数据准备入库
     * @param string $file_path
     * @param string $time
     * @return array
     * @throws
     */
    private function readContent($file_path,$time){
        if(file_exists($file_path)){
            $content = file_get_contents($file_path);
            $arr = explode('sec.',$content);
            $conf = new Conf();
            $result = $conf->getFtpConf();
            if(empty($result)){
                throw new ParamsException(1018);
            }
            $data_all = array();
            $data = array();
            foreach ($arr as $k=>$v){
                //拿到成功的数据
                if(strstr($v,'OK')){
                    array_push($data_all,strchr($v,'OK - '));
                }
            }
            if(!empty($data_all)){
                foreach ($data_all as $k=>$v){
                    $a = explode('|',$v);
                    $data[$k]['Pingtime'] = strchr($a[0],$time);
                    $b = explode('->',$a[1]);
                    $data[$k]['Mailsp'] = strchr($b[0],'mail');
                    $data[$k]['Usetime'] = trim(substr(strrchr($b[1], "d "), 1));
                    strchr($v,'OK - ') ? $data[$k]['Status'] = 1 : $data[$k]['Status'] = 0;
                    $data[$k]['Place'] = $result['place'];
                }
            }
            return $data;
        }else{
            throw new ParamsException(1002);
        }
    }





}