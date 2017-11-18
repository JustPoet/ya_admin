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
        if (!empty($session->get('admin'))
            && $this->_request->getServer('HTTP_X_PJAX') != 'true') {
            //TODO 获取权限，生成菜单
        }
    }

    public function out($data)
    {
        $response = $this->getResponse();
        $outBody = json_encode($data);
        $response->setHeader('Content-type', 'application/json;charset=utf8');
        $response->setBody($outBody);
    }
}