<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;


//文件管理
Route::rule('fileList', 'index/Dir/fileList', 'GET');
Route::rule('upload', 'index/Dir/upload', 'POST');
Route::rule('addFolder', 'index/Dir/addFolder', 'POST');
Route::rule('renameFolder', 'index/Dir/renameFolder', 'POST');
Route::rule('delFolder', 'index/Dir/delFolder', 'POST');
Route::rule('fileDel', 'index/Dir/fileDel', 'GET');

//警告中心
Route::rule('news', 'index/Message/news', 'GET');
Route::rule('newsAll', 'index/Message/newsAll', 'GET');
Route::rule('messageDel', 'index/Message/messageDel', 'POST');
Route::rule('messageRead', 'index/Message/messageRead', 'POST');
Route::rule('messageReadAll', 'index/Message/messageReadAll', 'POST');

//网站设置
Route::rule('updateConf', 'index/Conf/updateConf', 'POST');
Route::rule('updateFtp', 'index/Conf/updateFtp', 'POST');
Route::rule('getConf', 'index/Conf/getConf', 'GET');
Route::rule('getFtp', 'index/Conf/getFtp', 'GET');

//ping
Route::rule('ImportPingFtpData', 'index/Packet_internet_groper/ImportPingFtpData', 'POST');
Route::rule('UploadPingData', 'index/Packet_internet_groper/UploadPingData', 'POST');
Route::rule('chartsPing', 'index/Packet_internet_groper/chartsPing', 'GET');
Route::rule('chartsPingExceed', 'index/Packet_internet_groper/chartsPingExceed', 'GET');

//菜单
Route::rule('getMenu', 'index/Menu/getMenu', 'GET');

//性能指标排名接口
Route::rule('chartsStandard', 'index/index/chartsStandard', 'GET');
Route::rule('chartsInterface', 'index/index/chartsInterface', 'GET');
Route::rule('chartsCool', 'index/index/chartsCool', 'GET');


//月度指标
Route::rule('chartsMonthStandard', 'index/mail_month/chartsMonthStandard', 'GET');
Route::rule('chartsMonthInterface', 'index/mail_month/chartsMonthInterface', 'GET');
Route::rule('chartsMonthCool', 'index/mail_month/chartsMonthCool', 'GET');

//年度指标
Route::rule('chartsYearStandard', 'index/mail_year/chartsYearStandard', 'GET');
Route::rule('chartsYearInterface', 'index/mail_year/chartsYearInterface', 'GET');
Route::rule('chartsYearCool', 'index/mail_year/chartsYearCool', 'GET');

//邮箱指标
Route::rule('charts_139', 'index/Mail/charts_139', 'GET');
Route::rule('charts_163', 'index/Mail/charts_163', 'GET');
Route::rule('charts_189', 'index/Mail/charts_189', 'GET');
Route::rule('charts_qq', 'index/Mail/charts_qq', 'GET');
Route::rule('charts_sina', 'index/Mail/charts_sina', 'GET');

