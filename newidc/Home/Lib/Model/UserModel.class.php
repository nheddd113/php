<?php
class UserModel extends CommonModel{
    protected $_auto = array(
        array('level',1),
        array('password','md5',1,'function'),
        array('lastlogintime','time',1,'function'),
    );
    protected $_validate = array(
        array('loginname','require','用户名必需填写并唯一!',1,'unique'),
        array('loginname','3,12','用户名应有3-12位!',1,'length'),
        array('loginname','english','用户名可使用英文字母和数字组合!',1),
        array('password','password1','两次密码不相同',1,'confirm',1),
        array('password','require','密码不能为空',1,'regex',1),
        array('realname','require','用户姓名必需填写',1),
        array('allowip','require','登陆允许Ip不能为空',1),
    );

    public function regex($value,$rule) {
        $validate = array(
            'require'   =>  '/.+/',
            'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
            'currency'  =>  '/^\d+(\.\d+)?$/',
            'number'    =>  '/^\d+$/',
            'zip'       =>  '/^\d{6}$/',
            'integer'   =>  '/^[-\+]?\d+$/',
            'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
            'english'   =>  '/^[A-Za-z0-9]+$/',
        );
        // 检查是否有内置的正则表达式
        if(isset($validate[strtolower($rule)]))
            $rule       =   $validate[strtolower($rule)];
        return preg_match($rule,$value)===1;
    }
}
?>
