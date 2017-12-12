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
        $menuService = Service_Menu::getInstance();
        $menus = $menuService->get();
        $all = $menuService->getAll();
        $this->_view->assign('menus', $menus);
        $this->_view->assign('allMenu', $all);
        return true;
    }

    public function editAction()
    {
        return false;
    }

    public function updateSortAction()
    {
        $menus = $this->_request->getPost('menus');
        $menus = json_decode($menus, true);
        Service_Menu::getInstance()->updateSort($menus);
        return false;
    }

    public function saveAction()
    {
        $param = $this->_request->getPost();
        $form = new Form_MenuModel($param);
        if (!$form->validate()) {

        }
        $id = $this->_request->getPost('menuId', 0);
        Service_Menu::getInstance()->save($form->getFieldValue(), $id);
        $this->redirect('show');
    }

    public function deleteAction()
    {
        $id = $this->_request->getPost('id');
        Service_Menu::getInstance()->del($id);
        return false;
    }
}