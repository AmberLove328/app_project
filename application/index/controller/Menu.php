<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21 0021
 * Time: 19:30
 */

namespace app\index\controller;
use app\index\controller\Api as api;
class Menu extends Common
{

    /**
     * 返回菜单json
     * @return array
     */
    public function getMenu(){
        $data = array(
            array('icon'=>'layui-icon-home','title'=>"主页",'list'=>
                array(
                    array('jump'=>'/','title'=>'标准版性能指标','isOver'=>$this->standardIsOver),
                    array('jump'=>'home/homepage1','name'=>'homepage1','title'=>'IMAP/SMTP接口','isOver'=>$this->interfaceIsOver),
                    array('jump'=>'home/homepage2','name'=>'homepage2','title'=>'酷版性能指标','isOver'=>$this->coolIsOver),
                    array('jump'=>'message/','name'=>'message','title'=>'告警中心'),
                    array('jump'=>'home/analysis','name'=>'analysis','title'=>'ping日均值'),
                    array('jump'=>'home/analysisExceed','name'=>'analysisExceed','title'=>'ping超标150%')
                )
            ),
            array('icon'=>'layui-icon-component','title'=>'指标监控(月)','name'=>'month','list'=>
                array(
                    array('jump'=>'month/standard','name'=>'standard','title'=>'标准指标'),
                    array('jump'=>'month/interface','name'=>'interface','title'=>'接口指标'),
                    array('jump'=>'month/cool','name'=>'cool','title'=>'酷版指标'),
                )
            ),
            array('icon'=>'layui-icon-app','title'=>'指标监控(年)','name'=>'year','list'=>
                array(
                    array('jump'=>'year/standard','name'=>'standard','title'=>'标准指标'),
                    array('jump'=>'year/interface','name'=>'interface','title'=>'接口指标'),
                    array('jump'=>'year/cool','name'=>'cool','title'=>'酷版指标'),
                )
            ),
            array('icon'=>'layui-icon-chart','title'=>'邮箱指标','name'=>'mail','list'=>
                array(
                    array('jump'=>'mail/139','name'=>'139','title'=>'139邮箱'),
                    array('jump'=>'mail/163','name'=>'163','title'=>'163邮箱'),
                    array('jump'=>'mail/189','name'=>'189','title'=>'189邮箱'),
                    array('jump'=>'mail/qq','name'=>'qq','title'=>'qq邮箱'),
                    array('jump'=>'mail/sina','name'=>'sina','title'=>'新浪邮箱'),
                )
            ),
            array('icon'=>'layui-icon-set','title'=>'系统设置','name'=>'set','list'=>
                array(
                    array('jump'=>'set/dir','name'=>'dir','title'=>'文件管理'),
                    array('jump'=>'set/website','name'=>'website','title'=>'网站设置'),
                    array('jump'=>'set/ftp','name'=>'ftp','title'=>'FTP设置'),
                )
            ),
        );
        $api = new api();
        return $api->result_data($data);
    }

}