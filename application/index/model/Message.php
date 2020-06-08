<?php
/**
 * Created by PhpStorm.
 * User: v_luyan
 * Date: 2019/8/29
 * Time: 16:15
 */

namespace app\index\model;

use think\Model;

class Message extends Model
{
    /**
     * 批量插入数据
     * @param array $data
     * @return int
     * @throws
     */
    public function saveData($data)
    {
        return $this->insertAll($data);
    }

    /**
     * 批量删除数据
     * @param array $where
     * @return int
     * @throws
     */
    public function deleteData($where)
    {
        return $this->whereIn('id', $where)->delete();

    }

    /**
     * 批量修改数据 - 标记已读
     * @param array $where
     * @return $this
     * @throws
     */
    public function saveDataAll($where)
    {
        return $this->whereIn('id', $where)->update(['status' => 1]);
    }


    /**
     * 查询所有数据
     * @param array $limit
     * @return object
     * @throws
     */
    public function selectAll($limit)
    {
        return $this->limit($limit)->order('time DESC,status,competitor')->select();
    }

    /**
     * 全部标记已读
     * @return $this
     * @throws
     */
    public function updateAll()
    {
        return $this->where('status', 0)->update(['status' => 1]);
    }


    /**
     * 查询数据的数量
     * @param array $where
     * @return int
     * @throws
     */
    public function selectCount($where = [])
    {
        return Message::where($where)->count();
    }

}