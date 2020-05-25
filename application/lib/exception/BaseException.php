<?php
/**
 * Created by PhpStorm.
 * User: v_luyan
 * Date: 2019/7/15
 * Time: 15:26
 */

namespace app\lib\exception;
use think\Exception;

class BaseException extends Exception
{
    //自定义错误码
    public $code;
    //错误具体消息
    public $msg;

    //构造函数用于接收传入的异常信息，并初始化类中的属性
    public function __construct($code=5000, $msg='')
    {
        $this->code = $code;
        $this->msg = $msg;

    }


}