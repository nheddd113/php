<?php
$loginUrl = "http://idc.uqee.com/Login/verify";
$loginInfo = array('userName'=>'admin','userPass'=>'admin');
//do_post($loginUrl,$loginInfo);
$startUrl = 'http://idc.uqee.com/Amount/start';
do_get($startUrl);

function do_post($url,$data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $ret = curl_exec($ch);
    $info = curl_getinfo($ch);
    echo $ret ;
}
function do_get($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $ret = curl_exec($ch);
    $info = curl_getinfo($ch);
    echo $ret ;
}
?>
