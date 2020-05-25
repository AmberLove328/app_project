<?php
/**
 * Created by PhpStorm.
 * User: v_luyan
 * Date: 2019/7/19
 * Time: 15:58
 */

namespace app\index\model;
use think\Model;

class Ping extends Model
{
    /**
     * 批量插入数据
     * @param array $data
     * @return int
     * @throws
     */
    public function saveData($data){
        return $this->insertAll($data);
    }

    /**
     * 删除数据
     * @param string $where
     * @return int
     * @throws
     */
    public function deleteData($where){
        return $this->where($where)->delete();
    }

    /**
     * 查询数据
     * @param array $where
     * @param string $field
     * @return object
     * @throws
     */
    public function selectAll($where,$field){
        return Ping::where($where)->field($field)->order('pingtime')->select();

    }

    /**
     * 查询数据均值
     * @param array $where
     * @return float
     * @throws
     */
    public function pingAvg($where){
        return Ping::where($where)->avg('usetime');
    }


}