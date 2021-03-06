<?php
namespace Common\Model;
use Think\Model;
class KeywordModel extends Model {
    public function getRow($filter) {
        $row = $this->where($filter)->find();
        return $row;
    }   

    public function getAll($filter = array()) {
        $rows = $this->where($filter)->select();
        return $rows;
    } 

    public function getClicks($filter, $offset, $limit) {
        $rows = $this->where($filter)->limit("{$offset},{$limit}")->select();
        return $rows;
    }

    public function getClicksCount($filter) {
        $count = $this->where($filter)->count('id');
        return $count;
    }

    public function getPager($filter, $pageNo, $pageSize, $orderBy) {
        return $this->where($filter)->order($orderBy)->page($pageNo, $pageSize)->select();
    }

    public function createNew($data) {
        $id = $this->add($data);
        if ($id) {
            $data['id'] = $id;
            return $data;
        }
        else {
            return false;
        }
    }   
}
