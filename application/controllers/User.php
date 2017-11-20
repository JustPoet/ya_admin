<?php
/**
 * User.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/11/18 21:13
 * 修改记录:
 *
 * $Id$
 */

class UserController extends ControllerAbstract
{
    public function editAction()
    {
    }

    public function updateAction()
    {
        $uploader = new FileUpload();
        $uploader->set('path', APPLICATION_PATH . '/www/upload/avatar/');
        $uploader->upload('avatar');
        $avatar = $uploader->getFileName();
        $name = $this->_request->getPost('name');
        $password = $this->_request->getPost('password');
        $confirmPassword = $this->_request->getPost('confirm_password');
        $session = Yaf_Session::getInstance();
        $userId = $session->get('user')->id;

        $data = [
            'name' => $name,
            'avatar' => 'upload/avatar/' . $avatar
        ];
        if ($password == $confirmPassword && !empty($confirmPassword)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        }
        $userService = Service_User::getInstance();
        $success = $userService->update($userId, $data);
        if ($success) {
            $session->set('user', $userService->get($userId));
        }
        $this->redirect('/index/user/edit');
        return false;
    }
}