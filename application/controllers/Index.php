<?php
/**
 * Index.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/23 19:03
 * 修改记录:
 *
 * $Id$
 */

class IndexController extends ControllerAbstract
{
    public function indexAction()
    {
        return true;
    }

    public function loginAction()
    {
        $error = $this->_request->getRequest('error', '');
        $this->_view->assign('error', $error);
        return true;
    }

    public function doLoginAction()
    {
        $username = $this->_request->getPost('username');
        $password = $this->_request->getPost('password');
        $user = Service_User::getInstance()->login($username, $password);
        if ($user) {
            $this->redirect('/');
        } else {
            $this->redirect('login?error=用户名或密码错误');
        }
        return true;
    }

    public function signOutAction()
    {
        session_unset();
        $this->getResponse()->setRedirect('/index/index/login');
        return false;
    }
}