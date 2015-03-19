<?php

class CupboardModel extends RelationModel {
	protected $_link = array(
		"Seat" => array(
			"mapping_type"=>HAS_MANY,
			"foreign_key"=>"cupid",
			"mapping_name"=>"seatinfo"
		),
		"Hostlist" => array(
			"mapping_type"=>HAS_MANY,
			"foreign_key"=>"cupid",
			"mapping_name"=>"hostlist",
			"condition"=>"status>0"
		),
	);

}
?>