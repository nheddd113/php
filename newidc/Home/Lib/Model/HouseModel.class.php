<?php

class HouseModel extends RelationModel {
	protected $_link = array(
		"Cupboard" => array(
			"mapping_type"=>HAS_MANY,
			"foreign_key"=>"houid",
			"mapping_name"=>"cupinfo"
		),
	);
    public function getData($name='',$order=''){
        if(empty($name)){
            $name = $this->getModelName();
        }
        $model = D($name);
        $ret = $model->order($order)->select();
        $data = array();
        foreach($ret as $line){
            $data[$line[$model->getPk()]] = $line;
        }
        return $data;
    }

}
?>
