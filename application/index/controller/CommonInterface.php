<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 9:50
 */

namespace app\index\controller;

use think\Controller;
use think\Loader;

class CommonInterface extends Controller
{

    /**
     * 获取所有数据
     * @param array|string $whereTime
     * @return array
     */
    public function charts_data_all($whereTime)
    {
        $where = ['place' => ['like', '%' . 'imap' . '%']];
        $data = Loader::model('Webmail')->selectallsec($where, $whereTime, 'type,mailsp,usetime');
        return collection($data)->toArray(); //将对象转换成数组返回

    }

    /**
     * 处理139数据
     * @param array $arr
     * @return array
     */
    public function data_139($arr = [])
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $v) {
                //过滤掉其他数据且用时为0不计入均值
                if ($v['mailsp'] != 'imap.139.com' && $v['mailsp'] != 'smtp.139.com' || $v['usetime'] == 0) {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理163数据
     * @param array $arr
     * @return array
     */
    public function data_163($arr = [])
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $v) {
                //过滤掉其他数据且用时为0不计入均值
                if ($v['mailsp'] != 'imap.163.com' && $v['mailsp'] != 'smtp.163.com' || $v['usetime'] == 0) {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理189数据
     * @param array $arr
     * @return array
     */
    public function data_189($arr = [])
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $v) {
                //过滤掉其他数据且用时为0不计入均值
                if ($v['mailsp'] != 'imap.189.cn' && $v['mailsp'] != 'smtp.189.cn' || $v['usetime'] == 0) {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }


    /**
     * 处理qq数据
     * @param array $arr
     * @return array
     */
    public function data_qq($arr = [])
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $v) {
                //过滤掉其他数据且用时为0不计入均值
                if ($v['mailsp'] != 'imap.qq.com' && $v['mailsp'] != 'smtp.qq.com' || $v['usetime'] == 0) {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 处理sina数据
     * @param array $arr
     * @return array
     */
    public function data_sina($arr = [])
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $v) {
                //过滤掉其他数据且用时为0不计入均值
                if ($v['mailsp'] != 'imap.sina.cn' && $v['mailsp'] != 'smtp.sina.cn' && $v['mailsp'] != 'imap.sina.com' && $v['mailsp'] != 'smtp.sina.com' || $v['usetime'] == 0) {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /**
     * 通过类型获取title
     * @param string $type
     * @param bool $overtime
     * @return string
     */
    public function getTypeTitle($type, $overtime)
    {
        switch ($type) {
            case '3':
                $overtime ? $str = "imap_下载30字正文超过20s" : $str = "imap_下载30字正文";
                return $str;
            case '5':
                return "smtp_发送1M附件";
            case '20':
                return "imap_下载100封邮件头平均用时";
            default:
                return "未知类型";
        }
    }


    /**
     * 返回均值
     * @param array $arr
     * @param bool $flag
     * @return array
     */
    public function avg_charts($arr, $flag = false)
    {
        return [
            $this->avgCharts($arr, 3), //imap_下载30字正文均值 type=3
            $this->successRate($arr, $flag), //imap_下载30字正文超过20S指标均值 type=3  usetime>=20
            $this->avgCharts($arr, 5), //smtp_发送1M附件 type=5
            $this->avgCharts($arr, 20), //imap_下载100封邮件头平均用时 type=20
        ];
    }

    /**
     * 计算均值
     * @param array $arr
     * @param string $type
     * @param bool $overtime
     * @return string
     */
    public function avgCharts(array $arr, $type, $overtime = false)
    {

        $avg = '';
        if (!empty($arr)) {
            if ($type == 3) {
                if ($overtime) {
                    //$overtime为true则usetime>20s
                    foreach ($arr as $key => $v) {
                        if (strval($v['type']) != $type || $v['mailsp'] != 'imap.139.com' && $v['mailsp'] != 'imap.163.com' && $v['mailsp'] != 'imap.189.cn' && $v['mailsp'] != 'imap.qq.com' && $v['mailsp'] != 'imap.sina.cn' && $v['mailsp'] != 'imap.sina.com' || round($v['usetime'], 2) < 20) {
                            unset($arr[$key]);
                        }
                    }
                } else {
                    foreach ($arr as $key => $v) {
                        if (strval($v['type']) != $type || $v['mailsp'] != 'imap.139.com' && $v['mailsp'] != 'imap.163.com' && $v['mailsp'] != 'imap.189.cn' && $v['mailsp'] != 'imap.qq.com' && $v['mailsp'] != 'imap.sina.cn' && $v['mailsp'] != 'imap.sina.com') {
                            unset($arr[$key]);
                        }
                    }
                }
            } else if ($type == 5) {
                foreach ($arr as $key => $v) {
                    if (strval($v['type']) != $type || $v['mailsp'] != 'smtp.139.com' && $v['mailsp'] != 'smtp.163.com' && $v['mailsp'] != 'smtp.189.cn' && $v['mailsp'] != 'smtp.qq.com' && $v['mailsp'] != 'smtp.sina.cn' && $v['mailsp'] != 'smtp.sina.com') {
                        unset($arr[$key]);
                    }
                }
            } else if ($type == 20) {
                foreach ($arr as $key => $v) {
                    if (strval($v['type']) != $type || $v['mailsp'] != 'imap.139.com' && $v['mailsp'] != 'imap.163.com' && $v['mailsp'] != 'imap.189.cn' && $v['mailsp'] != 'imap.qq.com' && $v['mailsp'] != 'imap.sina.cn' && $v['mailsp'] != 'imap.sina.com') {
                        unset($arr[$key]);
                    }
                }
            }
            $arr_avg = array_column($arr, 'usetime');  //将多个数组转换成一个数组
            !empty($arr_avg) ? $avg = number_format(floor(array_sum($arr_avg) / count($arr_avg) * 100) / 100, 2) : $avg = number_format(0, 2);
        }
        return $avg;
    }


    /**
     * 计算imap_下载30字正文超过20S指标成功率 type=3 usetime>20
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @param bool $flag
     * @return array
     */
    public function success_rate($arr_139 = [], $arr_163 = [], $arr_189 = [], $arr_qq = [], $arr_sina = [], $flag = false)
    {
        return [
            '139' => $this->successRate($arr_139, $flag),
            '163' => $this->successRate($arr_163, $flag),
            '189' => $this->successRate($arr_189, $flag),
            'qq' => $this->successRate($arr_qq, $flag),
            'sina' => $this->successRate($arr_sina, $flag),
        ];
    }

    /**
     * 共用方法计算成功率
     * @param array $arr
     * @param bool $flag
     * @return string|float
     */
    public function successRate($arr, $flag = false)
    {
        $data = '';
        $flag == true ? $dividend = 100 : $dividend = 10000;
        if (!empty($arr)) {
            foreach ($arr as $key => $v) {
                if ($v['type'] != '3' || $v['mailsp'] != 'imap.139.com' && $v['mailsp'] != 'imap.163.com' && $v['mailsp'] != 'imap.189.cn' && $v['mailsp'] != 'imap.qq.com' && $v['mailsp'] != 'imap.sina.cn') {
                    unset($arr[$key]);
                }
            }

            $all = count($arr);
            foreach ($arr as $key => $v) {
                if (round($v['usetime'], 2) < 20) {
                    unset($arr[$key]);
                }
            }
            $left = count($arr);
            $all == 0 ? $data = number_format(1, 2) : $data = floor((($all - $left) / $all) * 10000) / $dividend;
        }
        return $data;

    }


    /**
     * 接口性能指标计算均值
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @return array
     */
    public function data_avg($arr_139 = [], $arr_163 = [], $arr_189 = [], $arr_qq = [], $arr_sina = [])
    {
        return [
            $this->dataAvg([$arr_139[0], $arr_163[0], $arr_189[0], $arr_qq[0], $arr_sina[0]]),
            $this->dataAvg([$arr_139[1], $arr_163[1], $arr_189[1], $arr_qq[1], $arr_sina[1]]),
            $this->dataAvg([$arr_139[2], $arr_163[2], $arr_189[2], $arr_qq[2], $arr_sina[2]]),
            $this->dataAvg([$arr_139[3], $arr_163[3], $arr_189[3], $arr_qq[3], $arr_sina[3]]),
        ];
    }

    /**
     * 接口性能指标计算均值
     * @param array $arr
     * @return string
     */
    public function dataAvg($arr)
    {
        $avg = '';
        if (!empty($arr)) {
            $i = 0;
            foreach ($arr as $v) {
                if ($v != 0) {
                    $i = $i + 1;
                }
            }
            $i == 0 ? $avg = number_format(0, 2) : $avg = number_format(floor(array_sum($arr) / $i * 100) / 100, 2);
        }
        return $avg;
    }


    /**
     * 接口性能指标计算排名
     * @param array $arr_139
     * @param array $arr_163
     * @param array $arr_189
     * @param array $arr_qq
     * @param array $arr_sina
     * @param array $data_rate
     * @param bool $flag
     * @return array
     */
    public function data_order($arr_139 = [], $arr_163 = [], $arr_189 = [], $arr_qq = [], $arr_sina = [], $data_rate = [], $flag = false)
    {
        if (!empty($arr_139)) {
            $data_order = [
                $this->dataOrder($arr_139[0], [$arr_163[0], $arr_189[0], $arr_qq[0], $arr_sina[0]]),
                $flag ? $this->dataOrder($arr_139[1], [$arr_163[1], $arr_189[1], $arr_qq[1], $arr_sina[1]], $data_rate, false, $flag) : $this->dataOrder($arr_139[1], [$arr_163[1], $arr_189[1], $arr_qq[1], $arr_sina[1]], $data_rate, true),
                $this->dataOrder($arr_139[2], [$arr_163[2], $arr_189[2], $arr_qq[2], $arr_sina[2]]),
                $this->dataOrder($arr_139[3], [$arr_163[3], $arr_189[3], $arr_qq[3], $arr_sina[3]]),
            ];
        } else {
            $data_order = $flag ? ["暂无排名", "1", "暂无排名", "暂无排名"] : ["暂无排名", "暂无失败指标", "暂无排名", "暂无排名"];
        }
        return $data_order;
    }

    /**
     * 接口性能指标计算排名
     * @param string $avg_139
     * @param array $arr
     * @param array $arr_rate
     * @param bool $isRate
     * @param bool $flag
     * @return string|float
     */
    public function dataOrder($avg_139, $arr, $arr_rate = [], $isRate = false, $flag = false)
    {
        if (!$isRate) {
            if (round($avg_139, 2) != 0) {
                $order = 1;
                if (!$flag) {
                    foreach ($arr as $v) {
                        if ($v != 0) {
                            if ($avg_139 > $v) {
                                $order = $order + 1;
                            }
                        }
                    }
                } else {
                    foreach ($arr as $v) {
                        if ($v != 0) {
                            if ($avg_139 < $v) {
                                $order = $order + 1;
                            }
                        }
                    }
                }
            } else {
                $order = "暂无排名";
            }
        } else {
            $order = "暂无失败指标";
            if (!empty($arr_rate['139']) && !empty($arr_rate['163']) && !empty($arr_rate['189']) && !empty($arr_rate['qq']) && !empty($arr_rate['sina'])) {
                if ($arr_rate['139'] != 1 || $arr_rate['163'] != 1 || $arr_rate['189'] != 1 || $arr_rate['qq'] != 1 || $arr_rate['sina'] != 1) {
                    $order = 1;
                    $arr_new = [$arr_rate['163'], $arr_rate['189'], $arr_rate["qq"], $arr_rate["sina"]];
                    foreach ($arr_new as $v) {
                        if ($v != 1) {
                            if ($arr_rate['139'] < $v) {
                                $order = $order + 1;
                            }
                        }
                    }
                }
            }
        }
        return $order;
    }


}