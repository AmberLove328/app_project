<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 14:08
 */

namespace app\index\Model;

use think\Model;

class Webmail extends Model
{

    /**
     * 查询所有相关字段  标准版用
     * @param array|string $where
     * @param array|string $whereTime
     * @param string $field
     * @return object
     * @throws
     */
    public function selectallfiled($where, $whereTime, $field)
    {
        return Webmail::whereTime('createtime', $whereTime)->whereIn('type', '1,2,3,6,7,8,9,12,109')->where($where)->field($field)->select();
    }

    /**
     * 查询所有相关字段  imap/smtp接口用
     * @param array|string $where
     * @param array|string $whereTime
     * @param string $field
     * @return object
     * @throws
     */
    public function selectallsec($where, $whereTime, $field)
    {
        return Webmail::whereTime('createtime', $whereTime)->whereIn('type', '3,5,20')->where($where)->field($field)->select();
    }

    /**
     * 查询所有相关字段  酷版用
     * @param array|string $where
     * @param array|string $whereTime
     * @param string $field
     * @return object
     * @throws
     */
    public function selectallthird($where, $whereTime, $field)
    {
        return Webmail::whereTime('createtime', $whereTime)->whereIn('type', '2,3,4,20')->where($where)->where([
            'place' => [['=', 'android_cool'], ['=', 'android_smart163'], ['=', 'android_189_cool'], ['=', 'android_qq_cool'], ['=', 'android_tssina'], 'or'],
        ])->field($field)->select();
    }

    /**
     * 查询所有相关字段 标准版按照月份查询
     * @param array|string $where
     * @param array|string $whereTime
     * @param string $field
     * @return object
     * @throws
     */
    public function selectMonthStandard($where, $whereTime, $field)
    {
        return Webmail::whereTime('createtime', $whereTime)->where($where)->where([
            'mailsp' => [['like', '%' . '139' . '%'], ['=', '189.cn'], ['=', '163.com'], ['=', 'qq.com'], ['=', 'sina.com'], 'or'],
        ])->order('createtime')->field($field)->select();
    }

    /**
     * 查询所有相关字段 imap/smtp接口按照月份查询
     * @param array|string $where
     * @param array|string $whereTime
     * @param string $field
     * @return object
     * @throws
     */
    public function selectMonthInterface($where, $whereTime, $field)
    {
        return Webmail::whereTime('createtime', $whereTime)->where($where)->order('createtime')->field($field)->select();
    }


    /**
     * 查询所有相关字段 酷版按照月份查询
     * @param array|string $where
     * @param array|string $whereTime
     * @param string $field
     * @return object
     * @throws
     */
    public function selectMonthCool($where, $whereTime, $field)
    {
        return Webmail::whereTime('createtime', $whereTime)->where($where)->where([
            'place' => [['=', 'android_cool'], ['=', 'android_smart163'], ['=', 'android_189_cool'], ['=', 'android_qq_cool'], ['=', 'android_tssina'], 'or'],
        ])->order('createtime')->field($field)->select();
    }


    /**
     * 查询相关字段
     * @param array|string $where
     * @param array|string $whereTime
     * @param string $field
     * @return object
     * @throws
     */
    public function selectFiled($where, $whereTime, $field)
    {
        return Webmail::whereTime('createtime', $whereTime)->where($where)->field($field)->select();
    }


}