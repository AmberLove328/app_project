<?php
/**
 * Created by PhpStorm.
 * User: v_luyan
 * Date: 2019/8/29
 * Time: 16:24
 */

namespace app\index\controller;

use app\index\controller\Api as api;
use app\lib\exception\ParamsException;
use think\Controller;
use think\Loader;

class Message extends Controller
{

    /**
     * 查看是否有新消息的接口
     * @throws
     */
    public function news()
    {
        $where = ['status' => ['=', '0']];
        $data = Loader::model('Message')->selectCount($where);
        $api = new api();
        return $api->result_data(['msg' => $data]);
    }

    /**
     * 所有消息的接口
     * @throws
     */
    public function newsAll()
    {
        $p = !empty($_GET['page']) ? $_GET['page'] : 1;
        $size = !empty($_GET['limit']) ? $_GET['limit'] : 10;
        $limit = ($p - 1) * $size . ',' . $size;
        $data = Loader::model('Message')->selectAll($limit);
        $count = Loader::model('Message')->selectCount();
        $api = new api();
        return $api->result_dataPage(collection($data)->toArray(), $count);
    }

    /**
     * 删除消息
     * @throws
     */
    public function messageDel()
    {
        $ids = [];
        $api = new api();
        if (!empty($_POST['data'])) {
            $arr = $_POST['data'];
            if (is_array($arr)) {
                foreach ($arr as $k => $v) {
                    array_push($ids, $v['id']);
                }
            }
            Loader::model('Message')->deleteData($ids);
        } else {
            throw new ParamsException(1001);
        }
        return $api->result_data();
    }

    /**
     * 标记已读
     * @throws
     */
    public function messageRead()
    {
        $ids = [];
        $api = new api();
        if (!empty($_POST['data'])) {
            $arr = $_POST['data'];
            if (is_array($arr)) {
                foreach ($arr as $k => $v) {
                    array_push($ids, $v['id']);
                }
            }
            Loader::model('Message')->saveDataAll($ids);
        } else {
            throw new ParamsException(1001);
        }
        return $api->result_data();

    }

    /**
     * 全部标记已读
     * @throws
     */
    public function messageReadAll()
    {
        Loader::model('Message')->updateAll();
        $api = new api();
        return $api->result_data();
    }


}