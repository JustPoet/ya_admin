<?php
/**
 * Auth.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/30 13:44
 * 修改记录:
 *
 * $Id$
 */

class AuthPlugin extends Yaf_Plugin_Abstract
{
    protected $except
        = [
            '/index/index/login',
            '/index/index/dologin',
            '/index/index/captcha',
        ];

    public function routerShutdown(
        Yaf_Request_Abstract $request,
        Yaf_Response_Abstract $response
    ) {
        if (!in_array(mb_strtolower($request->getRequestUri()),
            $this->except)) {
            $session = Yaf_Session::getInstance();
            if (empty($session->get('user'))) {
                $response->setRedirect('/index/index/login');
            }
        }
    }
}