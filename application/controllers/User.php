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
        $uploader->set('path', APPLICATION_PATH.'/www/upload/avatar/');
        $uploader->upload('avatar');
        $avatar = $uploader->getFileName();
        $name = $this->_request->getPost('name');
        $password = $this->_request->getPost('password');
        $confirmPassword = $this->_request->getPost('confirm_password');
        $session = Yaf_Session::getInstance();
        $userId = $session->get('user')->id;

        $data = [
            'name'   => $name,
            'avatar' => 'upload/avatar/'.$avatar,
        ];
        if ($password == $confirmPassword && !empty($confirmPassword)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT,
                ['cost' => 12]);
        }
        $userService = Service_User::getInstance();
        $success = $userService->update($userId, $data);
        if ($success) {
            $session->set('user', $userService->get($userId));
        }
        $this->redirect('/index/user/edit');

        return false;
    }

    public function listAction()
    {
        $groups = Service_Group::getInstance()->get();
        $roles = Service_Role::getInstance()->get();
        $this->_view->assign(compact('groups', 'roles'));
        return true;
    }

    public function pageAction()
    {
        $req = $this->_request->getRequest();
        $page = new Page($req['draw'], $req['length']);
        $result = Service_User::getInstance()->getPage([], $page);
        $data = [];
        foreach ($result['items'] as $index => $user) {
            $operations = $this->_view->render('/user/table_operate.twig', ['user' => $user]);

            $data[] = [
                'index'    => $index + 1,
                'username' => $user->username,
                'name'     => $user->name,
                'group'    => $user->group->name,
                'role'     => $user->role->name,
                'operate'  => $operations,
            ];
        }

        $this->out([
            'draw'            => $req['draw'],
            'recordsTotal'    => $result['page']->totalCount,
            'recordsFiltered' => $result['page']->totalCount,
            'data'            => $data,
        ]);
    }

    public function getAction()
    {
        $userId = $this->_request->getQuery('id');
        $user = Service_User::getInstance()->get($userId);
        $this->out([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->username,
                'group_id' => $user->group_id,
                'role_id' => $user->role_id
            ]
        ]);
    }

    public function saveAction()
    {

    }
}