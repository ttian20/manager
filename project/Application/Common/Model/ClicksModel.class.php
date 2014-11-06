<?php
namespace Common\Model;
use Think\Model;
class ClicksModel extends Model {
    public function getRow($filter) {
        $row = $this->where($filter)->find();
        return $row;
    }   

    public function getAll($filter = array()) {
        $rows = $this->where($filter)->select();
        return $rows;
    } 

    public function getPager($filter, $pageNo, $pageSize, $orderBy) {
        return $this->join('keyword ON keyword.id = clicks.kid')->where($filter)->order($orderBy)->page($pageNo, $pageSize)->select();
    }

    public function getClicks($filter, $offset, $limit) {
        $sql = "SELECT c.* FROM clicks c "
             . "INNER JOIN keyword k ON k.id = c.kid ";

        $where = ' WHERE 1';
        if ($filter['appkey']) {
            $where .= " AND k.appkey = '{$filter['appkey']}'";
        }
        if ($filter['id']) {
            $where .= " AND k.id = '{$filter['id']}'";
        }
        if ($filter['date']) {
            $where .= " AND c.date = {$filter['date']}";
        }
        $sql = $sql . $where . ' LIMIT ' . $offset . ', ' . $limit;
        $rows = $this->query($sql);
        return $rows;
    }

    public function getClicksCount($filter) {
        $sql = "SELECT COUNT(c.kid) as total FROM clicks c "
             . "INNER JOIN keyword k ON k.id = c.kid";

        $where = ' WHERE 1';
        if ($filter['appkey']) {
            $where .= " AND k.appkey = '{$filter['appkey']}'";
        }
        if ($filter['id']) {
            $where .= " AND k.id = '{$filter['id']}'";
        }
        if ($filter['date']) {
            $where .= " AND c.date = {$filter['date']}";
        }
        $sql = $sql . $where;
        $rows = $this->query($sql);
        return $rows[0];
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
