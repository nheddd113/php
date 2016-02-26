<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {
    public function index() {
        $initData = $this->getInitData();
        $count    = $initData['count'];
        $ret      = array();
        foreach ($count as $key => $value) {
            $v = number_format($value[0], 0, '.', '');
            $u = U('Search/search', $value[1]);
            if (substr($key, 0, 3) == 'not') {
                $ret['自有'][] = array($key, $v, $u);
            } else {
                $ret['非自有'][] = array($key, $v, $u);
            }
        }
        $this->assign('searchstart',date('Y-m-01'));
        $this->assign('searchend',date('Y-m-d'));
        // p($ret);die;
        $this->assign('count', $ret);
        // p($initData['count']);
        $this->display();
    }
}
