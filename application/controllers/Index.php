<?php

use Gregwar\Captcha\CaptchaBuilder;

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
        $captcha = $this->_request->getPost('captcha');
        $user = Service_User::getInstance()->login($username, $password, $captcha);
        if ($user) {
            $this->redirect('/');
        } else {
            $this->redirect('login?error=用户名或密码错误');
        }
        return false;
    }

    public function captchaAction()
    {
        $builder = new CaptchaBuilder;
        $builder->build(115, 34);
        Yaf_Session::getInstance()->set('captcha', mb_strtolower($builder->getPhrase()));
        header('Content-type: image/jpeg');
        $builder->output();
    }

    public function signOutAction()
    {
        session_unset();
        $this->getResponse()->setRedirect('/index/index/login');
        return false;
    }
}