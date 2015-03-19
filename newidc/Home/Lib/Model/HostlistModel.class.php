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
}
?>