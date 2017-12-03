<?php
/**
 * Menu.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/11/22 10:48
 * 修改记录:
 *
 * $Id$
 */

class MenuController extends ControllerAbstract
{
    public function showAction()
    {
        $menus = Service_Menu::getInstance()->get();
        $this->_view->assign('menus', $menus);
        return true;
    }

    public function editAction()
    {
        return false;
    }
}