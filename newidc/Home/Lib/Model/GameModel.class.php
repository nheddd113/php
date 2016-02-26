<?php
class GameModel extends CommonModel {
    protected $_validate = array(
        array('alias','require','游戏别名命名不符合规则!',1),
        array('name','require','游戏名称不能为空!',1,'unique'),
    );
}
?>
