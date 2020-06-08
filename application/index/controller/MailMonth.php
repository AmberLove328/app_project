<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/3
 * Time: 11:44
 */

namespace app\index\controller;

use app\lib\exception\ParamsException;
use think\Cache;
use think\Controller;
use app\index\controller\Api as api;
use think\response\Json;

class MailMonth extends Controller
{

    /**
     * 标准版性能指标接口
     * @return Json
     * @throws
     */
    public function chartsMonthStandard()
    {
        $whereTime = 'month';
        $eTime = date('Y-m', time());
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 month"))];
                    $eTime = $_GET['startTime'];
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 month"))];
                    $eTime = $_GET['startTime'] . '-' . $_GET['endTime'];;
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 month"))];
                $eTime = $_GET['startTime'];
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 month"))];
                $eTime = $_GET['endTime'];
            }
        }

        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 1;
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if (empty($result['cache']) || $result['cache'] == null || intval($result['cache']) <= 0) {
            $commonMonth = new CommonMonth();
            $common = new CommonStandard();
            $dataAll = $commonMonth->chartsDataAllStandard($whereTime, $type);
            $data_139_3 = $common->data_139_3($dataAll);
            $data_139_6 = $common->data_139_6($dataAll);
            $data_139_hui = $common->data_139_hui($dataAll);
            $data_163 = $common->data_163($dataAll);
            $data_189 = $common->data_189($dataAll);
            $data_qq = $common->data_qq($dataAll);
            $data_sina = $common->data_sina($dataAll);
            $time = [];
            if (!empty($dataAll)) {
                foreach ($dataAll as $key => $v) {
                    array_push($time, $v['createtime']);
                }
            }
            $time = array_values(array_unique(array_values($time)));
            $data = $commonMonth->chartsAllStandard(
                $commonMonth->avgChartsData($data_139_3, $time),
                $commonMonth->avgChartsData($data_139_6, $time),
                $commonMonth->avgChartsData($data_139_hui, $time),
                $commonMonth->avgChartsData($data_163, $time),
                $commonMonth->avgChartsData($data_189, $time),
                $commonMonth->avgChartsData($data_qq, $time),
                $commonMonth->avgChartsData($data_sina, $time),
                $type,
                $time
            );
        } else {
            if (Cache::get($type . 'monthStandard' . $eTime)) {
                $data = Cache::get($type . 'monthStandard' . $eTime);
            } else {
                $commonMonth = new CommonMonth();
                $common = new CommonStandard();
                $dataAll = $commonMonth->chartsDataAllStandard($whereTime, $type);
                $data_139_3 = $common->data_139_3($dataAll);
                $data_139_6 = $common->data_139_6($dataAll);
                $data_139_hui = $common->data_139_hui($dataAll);
                $data_163 = $common->data_163($dataAll);
                $data_189 = $common->data_189($dataAll);
                $data_qq = $common->data_qq($dataAll);
                $data_sina = $common->data_sina($dataAll);
                $time = [];
                if (!empty($dataAll)) {
                    foreach ($dataAll as $key => $v) {
                        array_push($time, $v['createtime']);
                    }
                }
                $time = array_values(array_unique(array_values($time)));
                $data = $commonMonth->chartsAllStandard(
                    $commonMonth->avgChartsData($data_139_3, $time),
                    $commonMonth->avgChartsData($data_139_6, $time),
                    $commonMonth->avgChartsData($data_139_hui, $time),
                    $commonMonth->avgChartsData($data_163, $time),
                    $commonMonth->avgChartsData($data_189, $time),
                    $commonMonth->avgChartsData($data_qq, $time),
                    $commonMonth->avgChartsData($data_sina, $time),
                    $type,
                    $time
                );
                Cache::set($type . 'monthStandard' . $eTime, $data, $result['cache'] * 60);
            }
        }
        $api = new api();
        return $api->result_data($data);
    }


    /**
     * imap/smtp接口性能指标
     * @return Json
     * @throws
     */
    public function chartsMonthInterface()
    {
        $whereTime = 'month';
        $eTime = date('Y-m', time());
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 month"))];
                    $eTime = $_GET['startTime'];
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 month"))];
                    $eTime = $_GET['startTime'] . '-' . $_GET['endTime'];;
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 month"))];
                $eTime = $_GET['startTime'];
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 month"))];
                $eTime = $_GET['endTime'];
            }
        }
        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 3;
        !empty($_GET['overtime']) ? $overtime = $_GET['overtime'] : $overtime = 0;
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if (empty($result['cache']) || $result['cache'] == null || intval($result['cache']) <= 0) {
            $commonMonth = new CommonMonth();
            $dataAll = $commonMonth->chartsDataAllInterface($whereTime, $type, $overtime);
            $time = [];
            if (!empty($dataAll)) {
                foreach ($dataAll as $key => $v) {
                    array_push($time, $v['createtime']);
                }
            }
            $time = array_values(array_unique(array_values($time)));
            $data = $commonMonth->chartsAllInterface(
                $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_139 = $commonMonth->data_139_imap($dataAll) : $data_139 = $commonMonth->data_139_smtp($dataAll), $time),
                $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_163 = $commonMonth->data_163_imap($dataAll) : $data_163 = $commonMonth->data_163_smtp($dataAll), $time),
                $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_189 = $commonMonth->data_189_imap($dataAll) : $data_189 = $commonMonth->data_189_smtp($dataAll), $time),
                $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_qq = $commonMonth->data_qq_imap($dataAll) : $data_qq = $commonMonth->data_qq_smtp($dataAll), $time),
                $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_sina = $commonMonth->data_sina_imap($dataAll) : $data_sina = $commonMonth->data_sina_smtp($dataAll), $time),
                $type,
                $overtime,
                $time
            );
        } else {
            if (Cache::get($type . $overtime . 'monthInterface' . $eTime)) {
                $data = Cache::get($type . $overtime . 'monthInterface' . $eTime);
            } else {
                $commonMonth = new CommonMonth();
                $dataAll = $commonMonth->chartsDataAllInterface($whereTime, $type, $overtime);
                $time = [];
                if (!empty($dataAll)) {
                    foreach ($dataAll as $key => $v) {
                        array_push($time, $v['createtime']);
                    }
                }
                $time = array_values(array_unique(array_values($time)));
                $data = $commonMonth->chartsAllInterface(
                    $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_139 = $commonMonth->data_139_imap($dataAll) : $data_139 = $commonMonth->data_139_smtp($dataAll), $time),
                    $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_163 = $commonMonth->data_163_imap($dataAll) : $data_163 = $commonMonth->data_163_smtp($dataAll), $time),
                    $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_189 = $commonMonth->data_189_imap($dataAll) : $data_189 = $commonMonth->data_189_smtp($dataAll), $time),
                    $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_qq = $commonMonth->data_qq_imap($dataAll) : $data_qq = $commonMonth->data_qq_smtp($dataAll), $time),
                    $commonMonth->avgChartsData($type == 3 || $type == 20 ? $data_sina = $commonMonth->data_sina_imap($dataAll) : $data_sina = $commonMonth->data_sina_smtp($dataAll), $time),
                    $type,
                    $overtime,
                    $time
                );
                Cache::set($type . $overtime . 'monthInterface' . $eTime, $data, $result['cache'] * 60);
            }
        }
        $api = new api();
        return $api->result_data($data);
    }


    /**
     * 酷版性能指标
     * @return Json
     * @throws
     */
    public function chartsMonthCool()
    {
        $whereTime = 'month';
        $eTime = date('Y-m', time());
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 month"))];
                    $eTime = $_GET['startTime'];
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 month"))];
                    $eTime = $_GET['startTime'] . '-' . $_GET['endTime'];;
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 month"))];
                $eTime = $_GET['startTime'];
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 month"))];
                $eTime = $_GET['endTime'];
            }
        }

        !empty($_GET['type']) ? $type = $_GET['type'] : $type = 2;
        $cache = new Conf();
        $result = $cache->getSiteConf();
        if (empty($result['cache']) || $result['cache'] == null || intval($result['cache']) <= 0) {
            $commonMonth = new CommonMonth();
            $common = new CommonCool();
            $dataAll = $commonMonth->chartsDataAllCool($whereTime, $type);
            $time = [];
            if (!empty($dataAll)) {
                foreach ($dataAll as $key => $v) {
                    array_push($time, $v['createtime']);
                }
            }
            $time = array_values(array_unique(array_values($time)));
            $data = $commonMonth->chartsAllCool(
                $commonMonth->avgChartsData($common->data_139($dataAll), $time),
                $commonMonth->avgChartsData($common->data_163($dataAll), $time),
                $commonMonth->avgChartsData($common->data_189($dataAll), $time),
                $commonMonth->avgChartsData($common->data_qq($dataAll), $time),
                $commonMonth->avgChartsData($common->data_sina($dataAll), $time),
                $type,
                $time
            );
        } else {
            if (Cache::get($type . 'monthCool' . $eTime)) {
                $data = Cache::get($type . 'monthCool' . $eTime);
            } else {
                $commonMonth = new CommonMonth();
                $common = new CommonCool();
                $dataAll = $commonMonth->chartsDataAllCool($whereTime, $type);
                $time = [];
                if (!empty($dataAll)) {
                    foreach ($dataAll as $key => $v) {
                        array_push($time, $v['createtime']);
                    }
                }
                $time = array_values(array_unique(array_values($time)));
                $data = $commonMonth->chartsAllCool(
                    $commonMonth->avgChartsData($common->data_139($dataAll), $time),
                    $commonMonth->avgChartsData($common->data_163($dataAll), $time),
                    $commonMonth->avgChartsData($common->data_189($dataAll), $time),
                    $commonMonth->avgChartsData($common->data_qq($dataAll), $time),
                    $commonMonth->avgChartsData($common->data_sina($dataAll), $time),
                    $type,
                    $time
                );
                Cache::set($type . 'monthCool' . $eTime, $data, $result['cache'] * 60);
            }
        }

        $api = new api();
        return $api->result_data($data);
    }


}