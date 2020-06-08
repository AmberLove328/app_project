<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/21
 * Time: 10:36
 */

namespace app\index\controller;

use app\lib\exception\ParamsException;
use think\Controller;
use think\response\Json;

class Api extends Controller
{

    /** 通用接口数据
     * @param int $code 状态码
     * @param String $msg 信息
     * @param array $data 接口数据
     * @param int $httpCode http状态码
     * @return Json
     */
    public function result_data($data = [], $code = 0, $msg = '', $httpCode = 200)
    {

        $res = [
            'code' => $code,
            'data' => $data,
            'msg' => $msg
        ];
        return json($res, $httpCode);
    }

    /** 通用接口数据 - 带分页
     * @param int $code 状态码
     * @param String $msg 信息
     * @param array $data 接口数据
     * @param int $count 数量
     * @param int $httpCode http状态码
     * @return Json
     */
    public function result_dataPage($data = [], $count = 0, $code = 0, $msg = '', $httpCode = 200)
    {

        $res = [
            'code' => $code,
            'count' => $count,
            'data' => $data,
            'msg' => $msg
        ];
        return json($res, $httpCode);
    }


    /**
     * 连接FTP将文件下载到本地
     * @param string $local_path
     * @param string $file_name
     * @return bool
     * @throws
     */
    public function localDown($local_path, $file_name)
    {
        $conf = new Conf();
        $result = $conf->getFtpConf();
        if (empty($result)) {
            throw new ParamsException(1018);
        }
        $config = [
            'host' => $result['host'],
            'port' => intval($result['port']),
            'user' => $result['username'],
            'pass' => $result['password'],
        ];
        $ftp = new Ftp($config);
        if (!$ftp->connect()) {
            throw new ParamsException(1020);
        }
        $file = $result['path'] . '/' . $file_name;
        $res = $ftp->download($local_path, $file);
        if (!$res) {
            throw new ParamsException(1017);
        }
        $ftp->close();
        return true;

    }


    /**
     *  文件下载
     * @return Json
     * @throws
     */

    public function downFile()
    {
        if (empty($_GET['file'])) {
            throw new ParamsException(1001);
        }
        $file = $_SERVER['DOCUMENT_ROOT'] . strchr($_GET['file'], '/share');
        $api = new Api();
        $down = new FileDownload();
        $flag = $down->download($file);
        if (!$flag) {
            throw new ParamsException(1002);
        }
        return $api->result_data();
    }


}