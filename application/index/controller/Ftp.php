<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/07/20 0020
 * Time: 17:48
 */

namespace app\index\controller;


class Ftp
{

    private $host = '';//远程服务器地址
    private $user = '';//ftp用户名
    private $pass = '';//ftp密码
    private $port = 21;//ftp登录端口
    private $error = '';//最后失败时的错误信息
    protected $conn;//ftp登录资源

    /**
     * 可以在实例化类的时候配置数据，也可以在下面的connect方法中配置数据
     * Ftp constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        empty($config) or $this->initialize($config);
    }

    /**
     * 初始化数据
     * @param array $config 配置文件数组
     */
    public function initialize(array $config = [])
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->port = isset($config['port']) ?: 21;
    }

    /**
     * 连接及登录ftp
     * @param array $config 配置文件数组
     * @return bool
     */
    public function connect(array $config = [])
    {
        empty($config) or $this->initialize($config);
        if (FALSE == ($this->conn = @ftp_connect($this->host, $this->port, 60))) {
            $this->error = "主机连接失败";
            return FALSE;
        }
        if (!$this->_login()) {
            $this->error = "服务器登录失败";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 登录Ftp服务器
     */
    private function _login()
    {
        return @ftp_login($this->conn, $this->user, $this->pass);
    }


    /**
     * 列出ftp指定目录
     * @param string $remote_path
     * @return array|string
     */
    public function list_file($remote_path = '')
    {
        $contents = @ftp_nlist($this->conn, $remote_path);
        return $contents;
    }

    /**
     * 从ftp服务器下载文件到本地
     * @param string $local_file 本地文件地址
     * @param string $remote_file 远程文件地址
     * @param string $mode 上传模式(ascii和binary其中之一)
     * @return bool
     */
    public function download($local_file = '', $remote_file = '', $mode = 'auto')
    {
        if ($mode == 'auto') {
            $ext = $this->_get_ext($remote_file);
            $mode = $this->_set_type($ext);
        }
        $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
        $result = @ftp_get($this->conn, $local_file, $remote_file, $mode);
        if ($result === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 获取文件的后缀名
     * @param string $local_file
     * @return string
     */
    private function _get_ext($local_file = '')
    {
        return (($dot = strrpos($local_file, '.')) == FALSE) ? 'txt' : substr($local_file, $dot + 1);
    }

    /**
     * 根据文件后缀获取上传编码
     * @param string $ext
     * @return string
     */
    private function _set_type($ext = '')
    {
        //如果传输的文件是文本文件，可以使用ASCII模式，如果不是文本文件，最好使用BINARY模式传输。
        return in_array($ext, ['txt', 'text', 'php', 'phps', 'php4', 'js', 'css', 'htm', 'html', 'phtml', 'shtml', 'log', 'xml'], TRUE) ? 'ascii' : 'binary';
    }

    /**
     * 获取上传错误信息
     */
    public function get_error_msg()
    {
        return $this->error;
    }

    /**
     * 关闭ftp连接
     * @return bool
     */
    public function close()
    {
        return $this->conn ? @ftp_close($this->conn) : FALSE;
    }


}