<?php

class UserAction extends CommonAction {
    /**
     * 用户列表视图
     */

    public function index() {
        $User = M('user');
        $this->getInitData();
        $userinfo       = $User->select();
        $this->userInfo = $userinfo;
        $this->display();
    }

    /**
     * 管理员修改帐号信息
     *
     */
    public function changeUserInfo() {
        $model = D('User');
        $map   = $this->_search($model);
        if (isset($map['level']) && $map['level'] > session('level')) {
            $this->ajaxReturn('修改用户等级时不能大于自己的等级', '等级错误', 0);
        }
        $user = $model->find($map['id']);
        if (empty($user)) {
            $this->ajaxReturn('修改的用户不存在', '用户错误', 0);
        }
        if ($user['level'] >= session('level') && session('userid') != $user['id']) {
            $this->ajaxReturn('不能修改等级大于自己的用户', '权限错误', 0);
        }
        if (isset($map['password']) && !empty($map['password'])) {
            $map['password'] = md5($map['password']);
        }
        if ($model->where($map)->count()) {
            $this->ajaxReturn('修改用户成功', '成功', 1);
        }
        if ($model->save($map)) {
            // 如果修改自己的等级的时候. 更新session里的等级
            if (session('userid') == $map['id'] && !empty($map['level'])) {
                session('level', $map['level']);
            }
            $this->ajaxReturn('修改用户成功', '成功', 1);
        } else {
            $this->ajaxReturn('修改用户失败', '失败!', 0);
        }
    }

    /**
     * 增加角色
     *
     */
    public function addRoleHandler() {
        if (!IS_POST) {
            $this->error('该页面不存在');
        }

        $addRole  = M('roles');
        $rolename = I('rolename');
        if (!$addRole->where(array('rolename' => $rolename))->count()) {
            if ($addRole->add($_POST)) {
                $this->success('增加角色成功!');
            } else {
                $this->error('增加角色失败!');
            }
        } else {
            $this->error('该角色已经存在!');
        }
    }

    /**
     * 获取单用户信息
     * @return [type] [description]
     */
    public function userinfo() {
        $model = D('User');
        $pk    = $model->getPk();
        $map   = $this->_search($model);
        if (!isset($map[$pk])) {
            $this->ajaxReturn('参数错误', '错误', 0);
        }
        if ($user = $model->where($map)->find()) {
            $this->ajaxReturn($user, '', 1);
        } else {
            $this->ajaxReturn('用户不存在', '错误', 0);
        }

    }

    /**
     * 管理员删除帐号信息
     *   */

    public function deleteUser() {
        $url = U(APP_NAME . '/User/index');
        if (!IS_POST || !IS_AJAX) {
            $this->error('该页面不存在', $url);
        }

        $User  = M('user');
        $pk    = $User->getPk();
        $where = $this->_search($User);
        if (is_array($where[$pk])) {
            $where[$pk] = array('in', $where[$pk]);
        }
        $where['loginname'] = array('neq', 'admin');
        if ($User->where($where)->delete()) {
            $this->ajaxReturn('删除帐号成功!', '成功', 1);
        } else {
            $this->ajaxReturn('删除帐号失败!', '失败', 0);
        }
    }

    /**
     * 修改密码显示视图
     */
    public function manager() {
        $this->display();
    }
    /**
     * 修改密码处理
     */
    public function managerHandle() {
        if (!IS_POST) {
            $this->error("该页面不存!", U(APP_NAME . '/Search/search'));
        }

        $model = D('User');
        $map   = $this->_search($model);
        $pk    = $model->getPk();
        if (!array_key_exists($pk, $map)) {
            $this->ajaxReturn('数据错误', '失败', 0);
        }
        $user = $model->find($map[$pk]);

        if (empty($user)) {
            $this->ajaxReturn('用户不存在', '失败', 0);
        }
        if ($_POST['password'] != $_POST['password1']) {
            $this->ajaxReturn('两次密码不相同,请重新输入', '确认密码有误', 0);
        }
        if (md5($_POST['oldpassword']) != $user['password']) {
            $this->ajaxReturn('原密码有误', '错误', 0);
        }
        $data = array(
            'password' => md5($_POST['password']),
            $pk        => $map[$pk],
        );
        if ($model->save($data)) {
            $this->ajaxReturn('修改密码成功!', '成功', 1);
        } else {
            $this->ajaxReturn('修改密码失败!', '失败', 0);
        }

        // $url      = U(APP_NAME . '/User/manager');
        // $userinfo = $user->where(array('loginname' => I('loginname')))->find();
        // if ($userinfo) {
        //     if ($userinfo['password'] != md5(I('oldpassword'))) {
        //         $this->error('原密码不正确', $url);
        //     }
        //     if ($userinfo['password'] == md5(I('password'))) {
        //         $this->error('要修改的密码与原密码相同!', $url);
        //     }
        //     $result = $user->where(array('loginname' => I('loginname')))->save(array('password' => md5(I('password'))));
        //     if ($result) {
        //         $this->success('修改密码成功!', $url);
        //     } else {
        //         $this->error('修改密码失败!', $url);
        //     }
        // } else {
        //     $this->error('帐号不存在!', $url);
        // }

    }
    /**
     * 增加用户视图
     */
    public function adduser() {
        $this->display();
    }

    /**
     * 增加用户处理
     */
    public function adduserHandler() {
        if (!IS_POST) {
            $this->error("该页面不存!", U(APP_NAME . '/Index/index'));
        }

        $model = D('User');
        if ($mdata = $model->create() === false) {
            $this->ajaxReturn($model->getError(), '数据错误', 0);
        }
        if ($model->add()) {
            $this->ajaxReturn('增加用户成功!', '成功', 1);
        } else {
            $this->ajaxReturn('增加用户失败!', '失败', 1);
        }
    }
}
?>
