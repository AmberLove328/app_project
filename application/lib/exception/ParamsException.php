<?php
/**
 * Created by PhpStorm.
 * User: v_luyan
 * Date: 2019/7/16
 * Time: 14:19
 */

namespace app\lib\exception;


use think\Config;

class ParamsException extends BaseException
{
    //自定义错误码
    public $code;
    //错误具体消息
    public $msg;

    public function __construct($code=5000)
    {
        $msg = Config::get('errorCode')[$code];
        if($msg){
            parent::__construct($code, $msg);
        }else{
            parent::__construct($code);
        }

    }




}