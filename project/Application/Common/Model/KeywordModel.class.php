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
