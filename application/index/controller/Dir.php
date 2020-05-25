<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 17:35
 */

namespace app\index\controller;
use app\lib\exception\ParamsException;
use think\Controller;
use app\index\controller\Api as api;
use think\Log;

class Dir extends Controller
{

    /**
     * 文件列表接口
     * @return array
     * @throws
     */
    public function fileList(){
        if(!empty($_GET['dir'])){
            $data = $this->get_dir_info($_GET['dir']);
            Log::record('获取文件列表数据成功','info');
            $api = new api();
            return $api->result_data($data);
        }else{
            throw new ParamsException(1001);
        }
    }

    /**
     * 获取指定路径下的信息
     * @param string $path 路径
     * @return array
     */
    private function get_dir_info($path){
        $data = [];
        $path=iconv('utf-8','gb2312',$path);
        $dir_path = "./share";
        $list=scandir($dir_path.$path);    //获取路径下文件和文件夹信息
        $path=iconv('gb2312','utf-8',$path);
        foreach ($list as $key=>$v){
            if($v != '.' && $v != '..'){
                if($v){
                    //$v=iconv('gb2312','utf-8',$v);
                    $data[$key]['name'] = $v;  //输入文件或文件夹的名称
                    $data[$key]['url']="/share".$path.'/'.$v;
                    $pathSize=iconv('utf-8','gb2312',$dir_path.$path.'/'.$v);  //文件路径
                    if(is_dir($pathSize)){
                        $data[$key]['isDir']=true;
                        $data[$key]['type']="dir";
                        $data[$key]['hasSm']=false;
                    }else{
                        $data[$key]['isDir']=false;
                        $file = pathinfo($v, PATHINFO_EXTENSION);  //获取文件后缀名
                        $data[$key]['type']=$this->typeIcon($file);  //通过后缀名返回对应文件icon
                        $data[$key]['hasSm']=$this->isSm($file);
                    }
                }
            }
        }
        if($data){
            foreach($data as $k=>$v){
                if($v){
                    $isDir[$k] = $v['isDir'];
                }
            }
            //按照文件夹排序
            array_multisort($isDir,SORT_DESC,SORT_STRING, $data);
        }
        return $data;
    }


    /**
     * 判断是否含有缩略图
     * @param string $file 传后缀名
     * @return bool
     */
    private function isSm($file){
        switch ($file){
            case 'png':
            case 'jpg':
            case 'jpeg':
            case 'gif':
            case 'ico':
            case 'bmp':
            case 'tga':
                return true;
            default:
                return false;
        }
    }


    /**
     * 上传接口
     * @param string $path 上传路径
     * @return array
     * @throws
     */
    public function upload($path){
        $file = request()->file('file'); // 获取表单上传文件
        if (empty($file) || empty($path)) {
            throw new ParamsException(1001);
        }
        if($file && $path){
            $dir_path=iconv('utf-8','gb2312',$_SERVER['DOCUMENT_ROOT'].'/share'.$path);
            $info = $file->move($dir_path,false);
            if($info){
                Log::record('上传成功','info');
                $api = new api();
                return $api->result_data();
            }else{
                // 上传失败获取错误信息
                throw new ParamsException();
            }
        }
    }


    /**
     * 删除文件
     * @return array
     * @throws
     */
    public function fileDel(){
        if(!empty($_GET['file'])){
            $path=iconv('utf-8','gb2312',$_GET['file']);
            $dir = $_SERVER['DOCUMENT_ROOT'].strchr($path,'/share');
            if(file_exists($dir)){
                if(unlink($dir)){
                    Log::record('删除文件成功','info');
                    $api = new api();
                    return $api->result_data();
                }else{
                    throw new ParamsException();
                }
            }else{
                //文件夹不存在
                throw new ParamsException(1002);
            }
        }else{
            throw new ParamsException(1001);
        }
    }


    /**
     * 创建文件夹
     * @param string $dir_path 路径
     * @param string $dir_name 名称
     * @return array
     * @throws
     */
    public function addFolder($dir_path, $dir_name){
        if($dir_name == null || $dir_path == null){
            throw new ParamsException(1001);
        }
        $dir = iconv("UTF-8", "GB2312", './share'.$dir_path . '/' . $dir_name);
        if (!is_dir($dir)){
            $result = mkdir ($dir,0777,true);
            if($result){
                Log::record('创建文件夹成功','info');
                $api = new api();
                return $api->result_data();
            }else{
                throw new ParamsException();
            }
        } else {
            //文件夹已存在
            throw new ParamsException(1003);
        }
    }

    /**
     * 重命名文件夹
     * @param string $dir_path 路径
     * @param string $old_name 旧名称
     * @param string $new_name 新名称
     * @return array
     * @throws
     */
    public function renameFolder($dir_path, $old_name, $new_name){
        if($old_name == null || $dir_path == null || $new_name == null){
            throw new ParamsException(1001);
        }
        $dir_old = iconv("UTF-8", "GB2312", './share'.$dir_path . '/' . $old_name);
        $dir_new = iconv("UTF-8", "GB2312", './share'.$dir_path . '/' . $new_name);
        if (!is_dir($dir_new)){
            $result = rename($dir_old,$dir_new);
            if($result){
                Log::record('重命名文件夹成功','info');
                $api = new api();
                return $api->result_data();
            }else{
                throw new ParamsException();
            }
        } else {
            //文件夹已存在
            throw new ParamsException(1003);
        }
    }


    /**
     * 删除文件夹
     * @param string $path 路径
     * @return array
     * @throws
     */
    public function delFolder($path){
        if($path == null){
            throw new ParamsException(1001);
        }
        $dir = iconv("UTF-8", "GB2312", './share'.$path);
        if (is_dir($dir)){
            $list = scandir($dir);
            unset($list[0]);
            unset($list[1]);
            if(!empty($list)){
                //文件夹不为空无法删除
                throw new ParamsException(1004);
            }else{
                if(rmdir($dir)){
                    Log::record('删除文件夹成功','info');
                    $api = new api();
                    return $api->result_data();
                }else{
                    //删除文件夹失败
                    throw new ParamsException();
                }
            }
        } else {
            //文件夹不存在
            throw new ParamsException(1002);
        }
    }


    /**
     * 识别文件类型返回相应类型图标
     * @param int $fileType 文件类型
     * @return string
     */
    private function typeIcon($fileType)
    {
        switch ($fileType) {
            case 'txt':
                return 'txt';
            case 'php':
            case 'java':
            case 'js':
            case 'css':
                return 'code';
            case 'swf':
                return 'flash';
            case 'xlsx':
            case 'xls':
                return 'xls';
            case 'pdf':
                return 'pdf';
            case 'doc':
            case 'docx':
                return 'doc';
            case 'zip':
            case 'rar':
            case 'iso':
            case '7z':
                return 'zip';
            case 'ppt':
            case 'pptx':
                return 'ppt';
            case 'html':
            case 'htm':
                return 'htm';
            case 'mp3':
            case 'wav':
            case 'aac':
                return 'mp3';
            case 'mp4':
            case 'avi':
            case 'rm':
            case 'rmvb':
            case 'mov':
            case 'wmv':
            case 'avm':
            case 'flv':
                return 'mp4';
            default:
                return 'file';
        }
    }

    }
