<?php

class HouseModel extends RelationModel {
	protected $_link = array(
		"Cupboard" => array(
			"mapping_type"=>HAS_MANY,
			"foreign_key"=>"houid",
			"mapping_name"=>"cupinfo"
		),
	);

}
?>