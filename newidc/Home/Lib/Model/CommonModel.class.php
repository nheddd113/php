<?php
class CommonModel extends Model{
    public function getData($name='',$order='',$map=array()){
        if(empty($name)){
            $name = $this->getModelName();
        }
        $model = D($name);
        $ret = $model->where($map)->order($order)->select();
        $data = array();
        foreach($ret as $line){
            $data[$line[$model->getPk()]] = $line;
        }
        return $data;
    }
}
?>
