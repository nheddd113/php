<?php

class CupboardModel extends RelationModel {
    protected $_link = array(
        "Seat"     => array(
            "mapping_type" => HAS_MANY,
            "foreign_key"  => "cupid",
            "mapping_name" => "seatinfo",
        ),
        "Hostlist" => array(
            "mapping_type" => HAS_MANY,
            "foreign_key"  => "cupid",
            "mapping_name" => "hostlist",
            "condition"    => "status>0",
        ),
        "House"    => array(
            "mapping_type"   => BELONGS_TO,
            "foreign_key"    => "houid",
            "mapping_name"   => "house",
            "mapping_fields" => "houname",
            "as_fields"      => "houname",
        ),
    );
    protected $_validate = array(
        array('cupname', 'require', '机柜名称不能为空', 1),
        array('seatnum', 'number', '机柜的机位总数必须是一个数字', 1),
        array('houid', 'number', '机柜所在机房不正确', 1),
        array('price', 'number', '机柜单价格必须是数字', 1),
    );
    public function getData($name = '', $order = '', $map = array()) {
        if (empty($name)) {
            $name = $this->getModelName();
        }
        $model = D($name);
        $ret   = $model->where($map)->order($order)->select();
        $data  = array();
        foreach ($ret as $line) {
            $data[$line['houid']][$line['id']] = $line;
        }
        return $data;
    }

}
?>
