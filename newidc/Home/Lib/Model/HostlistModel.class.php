<?php
class HostlistModel extends RelationModel{
	protected $_link = array(
		"house"=>array(
			"mapping_type"=>BELONGS_TO,
			"foreign_key"=>"houid",
			"mapping_fields"=>"houname",
			"as_fields"=>"houname"
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
