<?php

namespace app\index\controller;

use app\lib\exception\ParamsException;

use app\index\controller\Api as api;
use think\Controller;
use think\Log;

class Index extends Controller
{

    /**
     * 空方法跳转到首页
     */
    public function _empty()
    {
        Log::record('方法不存在,跳转到首页', 'info');
        $this->redirect('/', 302);
    }

    /**
     * 首页渲染
     */
    public function index()
    {
        $com = new Common();
        $com->getConfig($com->arr_standard, $com->arr_interface, $com->arr_cool);
        return view('static/admin/index.html');
    }


    //-----------------------------------------------
    //导出
    /**
     * 标准版性能指标导出接口
     * @throws
     */
    public function exportStandard()
    {
        $whereTime = 'today';
        $name = date('Ymd', time());
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                    $name = date('Ymd', strtotime($_GET['startTime']));
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                    $name = date('Ymd', strtotime($_GET['startTime'])) . '-' . date('Ymd', strtotime($_GET['endTime']));
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                $name = date('Ymd', strtotime($_GET['startTime']));
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                $name = date('Ymd', strtotime($_GET['endTime']));
            }
        }
        !empty($_GET['place']) ? $place = $_GET['place'] : $place = 'web_gzjd';
        $eName = $this->exportName($place);
        $xlsData = $this->dataStandard($whereTime, $place);
        unset($xlsData['place']);
        unset($xlsData['over']);
        unset($xlsData['over_mail']);
        //这里引入PHPExcel文件注意路径修改
        vendor("PHPExcel");
        vendor("PHPExcel.Writer.Excel5");
        vendor("PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.IOFactory");
        $objExcel = new \PHPExcel();
        $objActSheet = $objExcel->getActiveSheet();
        $letter = explode(',', "A,B,C,D,E,F,G,H,I,J");
        $arrHeader = ['竞品邮箱', '登录页打开时长', '登录时长', '打开写信页时长', '读邮件', '下载1M附件', '发送邮件', '搜索邮件', '接收外域', '超大附件下载'];
        $arrTarget = ['139_3.0', '139_6.0', '139灰度', '189', '163', 'QQ', 'Sina', '平均值', '139_3.0排名'];
        for ($i = 0; $i < count($arrHeader); $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
            $objActSheet->getStyle("$letter[$i]1")->getAlignment()->setWrapText(true);
        };
        $j = 2;
        for ($i = 0; $i < count($arrTarget); $i++) {
            $objActSheet->setCellValue("A$j", "$arrTarget[$i]");
            $j = $j + 1;
        };
        $a = 2;
        foreach ($xlsData as $k => $v) {
            $objActSheet->setCellValue('B' . $a, $v['0']);
            $objActSheet->setCellValue('C' . $a, $v['1']);
            $objActSheet->setCellValue('D' . $a, $v['2']);
            $objActSheet->setCellValue('E' . $a, $v['3']);
            $objActSheet->setCellValue('F' . $a, $v['4']);
            $objActSheet->setCellValue('G' . $a, $v['5']);
            $objActSheet->setCellValue('H' . $a, $v['6']);
            $objActSheet->setCellValue('I' . $a, $v['7']);
            $objActSheet->setCellValue('J' . $a, $v['8']);
            $objActSheet->getRowDimension($k)->setRowHeight(20);
            $a = $a + 1;
        }
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(20);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        $objActSheet->getColumnDimension('F')->setWidth(20);
        $objActSheet->getColumnDimension('G')->setWidth(20);
        $objActSheet->getColumnDimension('H')->setWidth(20);
        $objActSheet->getColumnDimension('I')->setWidth(20);
        $objActSheet->getColumnDimension('J')->setWidth(20);
        //设置表格居中
        $objActSheet->getStyle('A1:J10')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getStyle('A1:J10')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $outfile = "标准版性能指标_" . $eName . $name . ".xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outfile . '"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        //这里直接导出文件
        $objWriter->save('php://output');
        Log::record('标准版性能指标导出成功', 'info');
        exit;
    }

    /**
     * IMAP/SMTP接口性能指标导出接口
     * @throws
     */
    public function exportInterface()
    {
        $whereTime = 'today';
        $name = date('Ymd', time());
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                    $name = date('Ymd', strtotime($_GET['startTime']));
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                    $name = date('Ymd', strtotime($_GET['startTime'])) . '-' . date('Ymd', strtotime($_GET['endTime']));
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                $name = date('Ymd', strtotime($_GET['startTime']));
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                $name = date('Ymd', strtotime($_GET['endTime']));
            }
        }
        $xlsData = $this->dataInterface($whereTime, true);
        unset($xlsData['over']);
        unset($xlsData['over_mail']);
        //这里引入PHPExcel文件注意路径修改
        vendor("PHPExcel");
        vendor("PHPExcel.Writer.Excel5");
        vendor("PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.IOFactory");
        $objExcel = new \PHPExcel();
        $objActSheet = $objExcel->getActiveSheet();
        $letter = explode(',', "A,B,C,D,E,F");
        $arrHeader = ['SMTP/IMAP', 'IMAP:下载30字正文时长', 'IMAP:下载30字正文成功率', 'SMTP:发送1M附件时长', 'IMAP:下载100封邮件头平均用时'];
        $arrTarget = ['139', '189', '163', 'QQ', 'Sina', '平均值', '139排名'];
        for ($i = 0; $i < count($arrHeader); $i++) {
            $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
            $objActSheet->getStyle("$letter[$i]1")->getAlignment()->setWrapText(true);
        };
        $j = 2;
        for ($i = 0; $i < count($arrTarget); $i++) {
            $objActSheet->setCellValue("A$j", "$arrTarget[$i]");
            $j = $j + 1;
        };
        $a = 2;
        foreach ($xlsData as $k => $v) {
            $objActSheet->setCellValue('B' . $a, $v['0']);
            $a == 8 ? $objActSheet->setCellValue('C' . $a, $v['1']) : $objActSheet->setCellValue('C' . $a, $v['1'] . '%');
            $objActSheet->setCellValue('D' . $a, $v['2']);
            $objActSheet->setCellValue('E' . $a, $v['3']);
            $objActSheet->getRowDimension($k)->setRowHeight(20);
            $a = $a + 1;
        }

        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(20);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        //设置表格居中
        $objActSheet->getStyle('A1:E8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getStyle('A1:E8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $outfile = "IMAP_SMTP接口性能指标" . $name . ".xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outfile . '"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        //这里直接导出文件
        $objWriter->save('php://output');
        Log::record('IMAP/SMTP接口性能指标导出成功', 'info');
        exit;
    }


    /**
     * 酷版性能指标导出接口
     * @throws
     */
    public function exportCool()
    {
        $whereTime = 'today';
        $name = date('Ymd', time());
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                    $name = date('Ymd', strtotime($_GET['startTime']));
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                    $name = date('Ymd', strtotime($_GET['startTime'])) . '-' . date('Ymd', strtotime($_GET['endTime']));
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                $name = date('Ymd', strtotime($_GET['startTime']));
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                $name = date('Ymd', strtotime($_GET['endTime']));
            }
        }
        //4g+wifi全部数据
        $xlsData = $this->dataCool($whereTime);
        unset($xlsData['over']);
        unset($xlsData['over_mail']);
        //4g+wifi全部竞品排名
        $xlsDataOrder = $this->orderCool($whereTime);
        //wifi数据
        $xlsDataWifi = $this->dataCoolWifi($whereTime);
        unset($xlsDataWifi['over']);
        //4g数据
        $xlsData4g = $this->dataCool4g($whereTime);
        unset($xlsData4g['over']);
        //这里引入PHPExcel文件注意路径修改
        vendor("PHPExcel");
        vendor("PHPExcel.Writer.Excel5");
        vendor("PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.IOFactory");
        $objExcel = new \PHPExcel();
        $objActSheet = $objExcel->getActiveSheet();
        $objActSheet->setCellValue("A1", "各网络下139手机邮箱及竞品邮箱指标");
        $objActSheet->setCellValue("A2", "酷版");
        $objActSheet->setCellValue("A12", "多网络下用时均值");
        $objActSheet->setCellValue("A22", "酷版邮箱排名");
        //合并单元格
        $objActSheet->mergeCells('A1' . ":" . 'B1');
        //设置粗体
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A2')->getFont()->setBold(true);
        $objActSheet->getStyle('A12')->getFont()->setBold(true);
        $objActSheet->getStyle('A22')->getFont()->setBold(true);

        $letter_4g = explode(',', "B,C,D,E");
        $letter_wifi = explode(',', "G,H,I,J");
        $arrHeader = ['酷版：登录邮箱时长', '酷版：打开写信页时长', '酷版：打开未读邮件时长', '酷版：附件下载时长'];
        $arrTarget = ['139邮箱酷版', '189手机邮箱', '163邮箱智能版', 'QQ邮箱触屏版', 'Sina手机邮箱', '平均值', '排名'];

        //填充4G数据
        $j = 4;
        $a = 4;
        $objActSheet->setCellValue("A3", "4G");
        $objActSheet->getStyle("A3")->getAlignment()->setWrapText(true);
        for ($i = 0; $i < count($arrHeader); $i++) {
            $objActSheet->setCellValue("$letter_4g[$i]3", "$arrHeader[$i]");
            $objActSheet->getStyle("$letter_4g[$i]3")->getAlignment()->setWrapText(true);
        };
        for ($i = 0; $i < count($arrTarget); $i++) {
            $objActSheet->setCellValue("A$j", "$arrTarget[$i]");
            $j = $j + 1;
        };
        foreach ($xlsData4g as $k => $v) {
            $objActSheet->setCellValue('B' . $a, $v['0']);
            $objActSheet->setCellValue('C' . $a, $v['1']);
            $objActSheet->setCellValue('D' . $a, $v['2']);
            $objActSheet->setCellValue('E' . $a, $v['3']);
            $objActSheet->getRowDimension($k)->setRowHeight(20);
            $a = $a + 1;
        }
        //填充wifi数据
        $j = 4;
        $a = 4;
        $objActSheet->setCellValue("F3", "Wifi");
        $objActSheet->getStyle("F3")->getAlignment()->setWrapText(true);
        for ($i = 0; $i < count($arrHeader); $i++) {
            $objActSheet->setCellValue("$letter_wifi[$i]3", "$arrHeader[$i]");
            $objActSheet->getStyle("$letter_wifi[$i]3")->getAlignment()->setWrapText(true);
        };
        for ($i = 0; $i < count($arrTarget); $i++) {
            $objActSheet->setCellValue("F$j", "$arrTarget[$i]");
            $j = $j + 1;
        };
        foreach ($xlsDataWifi as $k => $v) {
            $objActSheet->setCellValue('G' . $a, $v['0']);
            $objActSheet->setCellValue('H' . $a, $v['1']);
            $objActSheet->setCellValue('I' . $a, $v['2']);
            $objActSheet->setCellValue('J' . $a, $v['3']);
            $objActSheet->getRowDimension($k)->setRowHeight(20);
            $a = $a + 1;
        }
        //填充4G+wifi数据
        $b = 14;
        $c = 14;
        $objActSheet->setCellValue("A13", "4G+wifi");
        $objActSheet->getStyle("A13")->getAlignment()->setWrapText(true);
        for ($i = 0; $i < count($arrHeader); $i++) {
            $objActSheet->setCellValue("$letter_4g[$i]13", "$arrHeader[$i]");
            $objActSheet->getStyle("$letter_4g[$i]13")->getAlignment()->setWrapText(true);
        };
        for ($i = 0; $i < count($arrTarget); $i++) {
            $objActSheet->setCellValue("A$b", "$arrTarget[$i]");
            $b = $b + 1;
        };
        foreach ($xlsData as $k => $v) {
            $objActSheet->setCellValue('B' . $c, $v['0']);
            $objActSheet->setCellValue('C' . $c, $v['1']);
            $objActSheet->setCellValue('D' . $c, $v['2']);
            $objActSheet->setCellValue('E' . $c, $v['3']);
            $objActSheet->getRowDimension($k)->setRowHeight(20);
            $c = $c + 1;
        }
        //4G+wifi排名
        $d = 24;
        $e = 24;
        $objActSheet->setCellValue("A23", "4G+wifi");
        $objActSheet->getStyle("A23")->getAlignment()->setWrapText(true);
        for ($i = 0; $i < count($arrHeader); $i++) {
            $objActSheet->setCellValue("$letter_4g[$i]23", "$arrHeader[$i]");
            $objActSheet->getStyle("$letter_4g[$i]23")->getAlignment()->setWrapText(true);
        };
        for ($i = 0; $i < 5; $i++) {
            $objActSheet->setCellValue("A$d", "$arrTarget[$i]");
            $d = $d + 1;
        };
        foreach ($xlsDataOrder as $k => $v) {
            $objActSheet->setCellValue('B' . $e, $v['0']);
            $objActSheet->setCellValue('C' . $e, $v['1']);
            $objActSheet->setCellValue('D' . $e, $v['2']);
            $objActSheet->setCellValue('E' . $e, $v['3']);
            $objActSheet->getRowDimension($k)->setRowHeight(20);
            $e = $e + 1;
        }

        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        $objActSheet->getColumnDimension('F')->setWidth(20);
        $objActSheet->getColumnDimension('G')->setWidth(20);
        $objActSheet->getColumnDimension('H')->setWidth(25);
        $objActSheet->getColumnDimension('I')->setWidth(25);
        $objActSheet->getColumnDimension('J')->setWidth(20);
        //设置表格居中
        $objActSheet->getStyle('A1:J28')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getStyle('A1:J28')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //单元格加边框
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'allborders' => array( //设置全部边框
                    'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
                ),
            ),
        );
        $objActSheet->getStyle('A3:J10')->applyFromArray($styleThinBlackBorderOutline);
        $objActSheet->getStyle('A13:E20')->applyFromArray($styleThinBlackBorderOutline);
        $objActSheet->getStyle('A23:E28')->applyFromArray($styleThinBlackBorderOutline);
        $color = array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D9E1F2')
            )
        );
        //设置单元格背景颜色
        $objActSheet->getStyle('A3:J3')->applyFromArray($color);
        $objActSheet->getStyle('A13:E13')->applyFromArray($color);
        $objActSheet->getStyle('A23:E23')->applyFromArray($color);
        $objActSheet->getStyle('A4:A10')->applyFromArray($color);
        $objActSheet->getStyle('A14:A20')->applyFromArray($color);
        $objActSheet->getStyle('A24:A28')->applyFromArray($color);
        $objActSheet->getStyle('F4:F10')->applyFromArray($color);

        $outfile = "酷版性能指标" . $name . ".xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outfile . '"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        //这里直接导出文件
        $objWriter->save('php://output');
        Log::record('酷版性能指标导出成功', 'info');
        exit;
    }


    //-----------------------------------------------
    //第一张表
    /**
     * 标准版性能指标排名接口
     * @return array
     * @throws
     */
    public function chartsStandard()
    {
        $whereTime = 'today';
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
            }
        }
        !empty($_GET['place']) ? $place = $_GET['place'] : $place = 'web_gzjd';
        $api = new api();
        return $api->result_data($this->dataStandard($whereTime, $place));
    }



    //-----------------------------------------------
    //第二张表
    /**
     * IMAP/SMTP接口性能指标排名
     * @return array
     * @throws
     */
    public function chartsInterface()
    {
        $whereTime = 'today';
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
            }
        }
        $api = new api();
        return $api->result_data($this->dataInterface($whereTime));
    }



    //-----------------------------------------------
    //第三张表
    /**
     * 酷版性能指标排名
     * @return array
     * @throws
     */
    public function chartsCool()
    {
        $whereTime = 'today';
        if (!empty($_GET['startTime']) || !empty($_GET['endTime'])) {
            if (!empty($_GET['startTime']) && !empty($_GET['endTime'])) {
                if ($_GET['startTime'] == $_GET['endTime']) {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
                } elseif ($_GET['startTime'] > $_GET['endTime']) {
                    throw new ParamsException(1030);
                } else {
                    $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
                }
            } elseif (!empty($_GET['startTime'])) {
                $whereTime = [$_GET['startTime'], date('Y-m-d', strtotime($_GET['startTime'] . "+1 day"))];
            } elseif (!empty($_GET['endTime'])) {
                $whereTime = [$_GET['endTime'], date('Y-m-d', strtotime($_GET['endTime'] . "+1 day"))];
            }
        }
        !empty($_GET['network']) ? $isp = $_GET['network'] : $isp = false;
        $api = new api();
        return $api->result_data($this->dataCool($whereTime, $isp));
    }

    //-----------------------------------------------

    /**
     * 标准版性能指标数据
     * @param string|array $whereTime
     * @param string $place
     * @return array
     */
    public function dataStandard($whereTime, $place)
    {
        $common = new CommonStandard();
        $arr_over = [];
        //获取所有数据
        $data_all = $common->charts_data_all($whereTime, $place);
        $data['place'] = $place;
        $data_139_3 = $common->avg_charts($common->data_139_3($data_all));
        $data['data_139_3'] = $data_139_3;
        $data['data_139_6'] = $common->avg_charts($common->data_139_6($data_all));
        $data['data_139_hui'] = $common->avg_charts($common->data_139_hui($data_all));
        $data['data_189'] = $common->avg_charts($common->data_189($data_all));
        $data['data_163'] = $common->avg_charts($common->data_163($data_all));
        $data['data_qq'] = $common->avg_charts($common->data_qq($data_all));
        $data['data_sina'] = $common->avg_charts($common->data_sina($data_all));
        //计算均值
        $avg = $common->data_avg(
            $common->avg_charts($common->data_139_3($data_all)),
            $common->avg_charts($common->data_163($data_all)),
            $common->avg_charts($common->data_189($data_all)),
            $common->avg_charts($common->data_qq($data_all)),
            $common->avg_charts($common->data_sina($data_all))
        );
        $data['avg'] = $avg;
        //计算139_3.0排名
        $data['order'] = $common->data_order(
            $common->avg_charts($common->data_139_3($data_all)),
            $common->avg_charts($common->data_163($data_all)),
            $common->avg_charts($common->data_189($data_all)),
            $common->avg_charts($common->data_qq($data_all)),
            $common->avg_charts($common->data_sina($data_all))
        );
        //返回指标是否大于均值
        if (!empty($data_139_3) && !empty($avg)) {
            $over = [];
            $count = count($avg);
            for ($i = 0; $i < $count; $i++) {
                if ($data_139_3[$i] > $avg[$i]) {
                    array_push($over, 'true');
                } else {
                    array_push($over, 'false');
                }
            }
        } else {
            $over = ['false', 'false', 'false', 'false', 'false', 'false', 'false', 'false', 'false'];
        }
        //判断邮箱是否缺失指标
        in_array("", $data['data_139_3']) || in_array("0.00", $data['data_139_3']) ? $arr_over = array_merge($arr_over, ['mail_139_3' => 'false']) : $arr_over = array_merge($arr_over, ['mail_139_3' => 'true']);
        in_array("", $data['data_139_6']) || in_array("0.00", $data['data_139_6']) ? $arr_over = array_merge($arr_over, ['mail_139_6' => 'false']) : $arr_over = array_merge($arr_over, ['mail_139_6' => 'true']);
        in_array("", $data['data_139_hui']) || in_array("0.00", $data['data_139_hui']) ? $arr_over = array_merge($arr_over, ['mail_139_hui' => 'false']) : $arr_over = array_merge($arr_over, ['mail_139_hui' => 'true']);
        in_array("", $data['data_189']) || in_array("0.00", $data['data_189']) ? $arr_over = array_merge($arr_over, ['mail_189' => 'false']) : $arr_over = array_merge($arr_over, ['mail_189' => 'true']);
        in_array("", $data['data_163']) || in_array("0.00", $data['data_163']) ? $arr_over = array_merge($arr_over, ['mail_163' => 'false']) : $arr_over = array_merge($arr_over, ['mail_163' => 'true']);
        in_array("", $data['data_qq']) || in_array("0.00", $data['data_qq']) ? $arr_over = array_merge($arr_over, ['mail_qq' => 'false']) : $arr_over = array_merge($arr_over, ['mail_qq' => 'true']);
        in_array("", $data['data_sina']) || in_array("0.00", $data['data_sina']) ? $arr_over = array_merge($arr_over, ['mail_sina' => 'false']) : $arr_over = array_merge($arr_over, ['mail_sina' => 'true']);
        $data['over'] = $over;
        $data['over_mail'] = $arr_over;
        return $data;
    }


    /**
     * IMAP/SMTP接口性能指标数据
     * @param string|array $whereTime
     * @param bool $flag 如果是true则是导出的数据
     * @return array
     */
    public function dataInterface($whereTime, $flag = false)
    {
        $common = new CommonInterface();
        $arr_over = [];
        //获取所有数据
        $data_all = $common->charts_data_all($whereTime);
        $data_139 = $common->data_139($data_all);
        $data_189 = $common->data_189($data_all);
        $data_163 = $common->data_163($data_all);
        $data_qq = $common->data_qq($data_all);
        $data_sina = $common->data_sina($data_all);
        //
        $avg_139 = $common->avg_charts($data_139, $flag);
        $data['data_139'] = $avg_139;
        $data['data_189'] = $common->avg_charts($data_189, $flag);
        $data['data_163'] = $common->avg_charts($data_163, $flag);
        $data['data_qq'] = $common->avg_charts($data_qq, $flag);
        $data['data_sina'] = $common->avg_charts($data_sina, $flag);
        //计算均值
        $avg = $common->data_avg(
            $common->avg_charts($data_139, $flag),
            $common->avg_charts($data_163, $flag),
            $common->avg_charts($data_189, $flag),
            $common->avg_charts($data_qq, $flag),
            $common->avg_charts($data_sina, $flag)
        );
        $data['avg'] = $avg;
        //计算139排名
        $data['order'] = $common->data_order(
            $common->avg_charts($data_139, $flag),
            $common->avg_charts($data_163, $flag),
            $common->avg_charts($data_189, $flag),
            $common->avg_charts($data_qq, $flag),
            $common->avg_charts($data_sina, $flag),
            $common->success_rate($data_139, $data_163, $data_189, $data_qq, $data_sina, $flag),
            $flag
        );

        //返回指标是否大于均值
        if (!empty($avg_139) && !empty($avg)) {
            $over = [];
            $count = count($avg);
            for ($i = 0; $i < $count; $i++) {
                if (!empty($avg_139[$i])) {
                    if ($i == 1) {
                        if ($avg_139[$i] < $avg[$i]) {
                            array_push($over, 'true');
                        } else {
                            array_push($over, 'false');
                        }
                    } else {
                        if ($avg_139[$i] > $avg[$i]) {
                            array_push($over, 'true');
                        } else {
                            array_push($over, 'false');
                        }
                    }
                }
            }
        } else {
            $over = ['false', 'false', 'false', 'false'];
        }
        //判断邮箱是否缺失指标
        in_array("", $data['data_139']) || in_array("0.00", $data['data_139']) ? $arr_over = array_merge($arr_over, ['mail_139' => 'false']) : $arr_over = array_merge($arr_over, ['mail_139' => 'true']);
        in_array("", $data['data_189']) || in_array("0.00", $data['data_189']) ? $arr_over = array_merge($arr_over, ['mail_189' => 'false']) : $arr_over = array_merge($arr_over, ['mail_189' => 'true']);
        in_array("", $data['data_163']) || in_array("0.00", $data['data_163']) ? $arr_over = array_merge($arr_over, ['mail_163' => 'false']) : $arr_over = array_merge($arr_over, ['mail_163' => 'true']);
        in_array("", $data['data_qq']) || in_array("0.00", $data['data_qq']) ? $arr_over = array_merge($arr_over, ['mail_qq' => 'false']) : $arr_over = array_merge($arr_over, ['mail_qq' => 'true']);
        in_array("", $data['data_sina']) || in_array("0.00", $data['data_sina']) ? $arr_over = array_merge($arr_over, ['mail_sina' => 'false']) : $arr_over = array_merge($arr_over, ['mail_sina' => 'true']);
        $data['over'] = $over;
        $data['over_mail'] = $arr_over;
        return $data;
    }

    /**
     * 酷版性能指标数据 4g + wifi
     * @param string|array $whereTime
     * @param string|bool $isp
     * @return array
     */
    public function dataCool($whereTime, $isp = false)
    {
        $common = new CommonCool();
        $arr_over = [];
        $data_all = $common->charts_data_all($whereTime);
        if ($isp !== false && $isp !== 'wifi+4G') {
            if ($isp == 'wifi') {
                $data_all = $common->data_wifi($data_all);
            } elseif ($isp == '4G') {
                $data_all = $common->data_4g($data_all);
            }
        }
        $avg_139 = $common->avg_charts($common->data_139($data_all));
        $data['data_139'] = $avg_139;
        $data['data_189'] = $common->avg_charts($common->data_189($data_all));
        $data['data_163'] = $common->avg_charts($common->data_163($data_all));
        $data['data_qq'] = $common->avg_charts($common->data_qq($data_all));
        $data['data_sina'] = $common->avg_charts($common->data_sina($data_all));
        //计算均值
        $avg = $common->data_avg(
            $common->avg_charts($common->data_139($data_all)),
            $common->avg_charts($common->data_163($data_all)),
            $common->avg_charts($common->data_189($data_all)),
            $common->avg_charts($common->data_qq($data_all)),
            $common->avg_charts($common->data_sina($data_all))
        );
        $data['avg'] = $avg;
        //计算139排名
        $data['order'] = $common->data_order(
            $common->avg_charts($common->data_139($data_all)),
            $common->avg_charts($common->data_163($data_all)),
            $common->avg_charts($common->data_189($data_all)),
            $common->avg_charts($common->data_qq($data_all)),
            $common->avg_charts($common->data_sina($data_all))
        );
        //返回指标是否大于均值
        if (!empty($avg_139) && !empty($avg)) {
            $over = [];
            $count = count($avg);
            for ($i = 0; $i < $count; $i++) {
                if ($avg_139[$i] > $avg[$i]) {
                    array_push($over, 'true');
                } else {
                    array_push($over, 'false');
                }
            }
        } else {
            $over = ['false', 'false', 'false', 'false'];
        }
        //判断邮箱是否缺失指标
        in_array("", $data['data_139']) || in_array("0.00", $data['data_139']) ? $arr_over = array_merge($arr_over, ['mail_139' => 'false']) : $arr_over = array_merge($arr_over, ['mail_139' => 'true']);
        in_array("", $data['data_189']) || in_array("0.00", $data['data_189']) ? $arr_over = array_merge($arr_over, ['mail_189' => 'false']) : $arr_over = array_merge($arr_over, ['mail_189' => 'true']);
        in_array("", $data['data_163']) || in_array("0.00", $data['data_163']) ? $arr_over = array_merge($arr_over, ['mail_163' => 'false']) : $arr_over = array_merge($arr_over, ['mail_163' => 'true']);
        in_array("", $data['data_qq']) || in_array("0.00", $data['data_qq']) ? $arr_over = array_merge($arr_over, ['mail_qq' => 'false']) : $arr_over = array_merge($arr_over, ['mail_qq' => 'true']);
        in_array("", $data['data_sina']) || in_array("0.00", $data['data_sina']) ? $arr_over = array_merge($arr_over, ['mail_sina' => 'false']) : $arr_over = array_merge($arr_over, ['mail_sina' => 'true']);
        $data['over'] = $over;
        $data['over_mail'] = $arr_over;
        return $data;
    }


    /**
     * 酷版性能指标所有竞品排名 4g + wifi
     * @param string|array $whereTime
     * @return array
     */
    private function orderCool($whereTime)
    {
        $common = new CommonCool();
        $data_all = $common->charts_data_all($whereTime);
        $avg_139 = $common->avg_charts($common->data_139($data_all));
        $avg_163 = $common->avg_charts($common->data_163($data_all));
        $avg_189 = $common->avg_charts($common->data_189($data_all));
        $avg_qq = $common->avg_charts($common->data_qq($data_all));
        $avg_sina = $common->avg_charts($common->data_sina($data_all));
        $data['data_139'] = $common->data_order($avg_139, $avg_163, $avg_189, $avg_qq, $avg_sina);
        $data['data_189'] = $common->dataOrder189($avg_139, $avg_163, $avg_189, $avg_qq, $avg_sina);
        $data['data_163'] = $common->dataOrder163($avg_139, $avg_163, $avg_189, $avg_qq, $avg_sina);
        $data['data_qq'] = $common->dataOrderQq($avg_139, $avg_163, $avg_189, $avg_qq, $avg_sina);
        $data['data_sina'] = $common->dataOrderSina($avg_139, $avg_163, $avg_189, $avg_qq, $avg_sina);
        return $data;
    }


    /**
     * 酷版性能指标数据 wifi
     * @param string|array $whereTime
     * @return array
     */
    private function dataCoolWifi($whereTime)
    {
        $common = new CommonCool();
        $data_all = $common->charts_data_all($whereTime);
        //过滤掉4G的数据
        $data_wifi = $common->data_wifi($data_all);
        $data['data_139'] = $common->avg_charts($common->data_139($data_wifi));
        $data['data_189'] = $common->avg_charts($common->data_189($data_wifi));
        $data['data_163'] = $common->avg_charts($common->data_163($data_wifi));
        $data['data_qq'] = $common->avg_charts($common->data_qq($data_wifi));
        $data['data_sina'] = $common->avg_charts($common->data_sina($data_wifi));
        //计算均值
        $data['avg'] = $common->data_avg(
            $common->avg_charts($common->data_139($data_wifi)),
            $common->avg_charts($common->data_163($data_wifi)),
            $common->avg_charts($common->data_189($data_wifi)),
            $common->avg_charts($common->data_qq($data_wifi)),
            $common->avg_charts($common->data_sina($data_wifi))
        );
        //计算139排名
        $data['order'] = $common->data_order(
            $common->avg_charts($common->data_139($data_wifi)),
            $common->avg_charts($common->data_163($data_wifi)),
            $common->avg_charts($common->data_189($data_wifi)),
            $common->avg_charts($common->data_qq($data_wifi)),
            $common->avg_charts($common->data_sina($data_wifi))
        );
        return $data;
    }

    /**
     * 酷版性能指标数据 4g
     * @param string|array $whereTime
     * @return array
     */
    private function dataCool4g($whereTime)
    {
        $common = new CommonCool();
        $data_all = $common->charts_data_all($whereTime);
        //过滤掉wifi的数据
        $data_4g = $common->data_4g($data_all);
        $data['data_139'] = $common->avg_charts($common->data_139($data_4g));
        $data['data_189'] = $common->avg_charts($common->data_189($data_4g));
        $data['data_163'] = $common->avg_charts($common->data_163($data_4g));
        $data['data_qq'] = $common->avg_charts($common->data_qq($data_4g));
        $data['data_sina'] = $common->avg_charts($common->data_sina($data_4g));
        //计算均值
        $data['avg'] = $common->data_avg(
            $common->avg_charts($common->data_139($data_4g)),
            $common->avg_charts($common->data_163($data_4g)),
            $common->avg_charts($common->data_189($data_4g)),
            $common->avg_charts($common->data_qq($data_4g)),
            $common->avg_charts($common->data_sina($data_4g))
        );
        //计算139排名
        $data['order'] = $common->data_order(
            $common->avg_charts($common->data_139($data_4g)),
            $common->avg_charts($common->data_163($data_4g)),
            $common->avg_charts($common->data_189($data_4g)),
            $common->avg_charts($common->data_qq($data_4g)),
            $common->avg_charts($common->data_sina($data_4g))
        );
        return $data;
    }


    /**
     * 传入对应的英文名place返回中文名，用于导出
     * @param string $place
     * @return string
     */
    private function exportName($place)
    {
        switch ($place) {
            case 'web_gzjd':
                return '广州基地';
            case 'web_gzct':
                return '广州彩讯';
            case 'web_szct':
                return '深圳彩讯';
            case 'web_bjlt':
                return '北京彩讯';
            default:
                return '未知';
        }
    }


}
