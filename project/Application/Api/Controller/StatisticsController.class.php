<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
class StatisticsController extends ApiController {
    public function getClicks() {
        $clicksMdl = D('Clicks'); 
        $filter = array('appkey' => $this->_params['appkey']);
        if (isset($this->_params['id'])) {
            $filter['id'] = $this->_params['id'];
        }

        if (isset($this->_params['date'])) {
            $filter['date'] = $this->_params['date'];
        }

        if (isset($this->_params['page_no']) && $this->_params['page_no']) {
            $pageNo = trim($this->_params['page_no']);
            if ($pageNo < 1) {
                $this->_error('500', '5001', 'page_no error');
            }
        }
        else {
            $pageNo = 1;
        }

        if (isset($this->_params['page_size']) && $this->_params['page_size']) {
            $pageSize = trim($this->_params['page_size']);
            if ($pageSize < 1 || $pageSize > 100) {
                $this->_error('500', '5001', 'page_size error');
            }
        }
        else {
            $pageSize = 100;
        }

        $offset = ($pageNo - 1) * $pageSize;
        $limit = $pageSize;
        $date = $filter['date'];

        if (isset($filter['date']) && ($filter['date'] == date('Ymd'))) {
            $kwdMdl = D('Keyword');
            unset($filter['date']);
            if (empty($filter['id'])) {
                unset($filter['id']);
            }
            $total = $kwdMdl->getClicksCount($filter);
            //echo $kwdMdl->getLastSql() . "\n";;
            $res = $kwdMdl->getClicks($filter, $offset, $limit);
            //echo $kwdMdl->getLastSql() . "\n";;
            $clicks = array();
            foreach ($res as $v) {
                $clicks[] = array(
                    'kid' => $v['id'],
                    'date' => $date,
                    'clicks' => $v['clicked_times'],
                );
            }
        }
        else {
            $total = $clicksMdl->getClicksCount($filter);
            $total = $total['total'];
            $clicks = $clicksMdl->getClicks($filter, $offset, $limit);
        }

        $data = array('total' => $total, 'clicks' => $clicks);

        $this->_success($data);
    }
}
