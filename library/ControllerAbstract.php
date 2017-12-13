<?php
/**
 * ControllerAbstract.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/1 18:10
 * 修改记录:
 *
 * $Id$
 */

abstract class ControllerAbstract extends Yaf_Controller_Abstract
{
    public function init()
    {
        $session = Yaf_Session::getInstance();
        if (
            !empty($session->get('user'))
            && $this->_request->getServer('HTTP_X_PJAX') != 'true'
            && !$this->_request->isCli()
        ) {
            $menus = Service_Menu::getInstance()->get();
            $this->_view->assign('sideMenus', $menus);
        }
    }

    public function out($data)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $response = $this->getResponse();
        $outBody = json_encode($data);
        $response->setHeader('Content-type', 'application/json;charset=utf8');
        $response->setBody($outBody);
    }
}