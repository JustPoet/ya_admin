<?php

/**
 * @name ErrorController
 * @desc   错误控制器, 在发生未捕获的异常时刻被调用
 * @see    http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author zhengzean
 */
class ErrorController extends ControllerAbstract
{
    public function errorAction(Exception $exception)
    {
        $error = 'EXCEPTION:'.$exception->getMessage().',at '
            .$exception->getFile().',line:'.$exception->getLine();

        $uriArray = explode('/', $this->getRequest()->getRequestUri());
        $isApi = mb_strtolower($uriArray[1]) === 'api';
        $isAjax = $this->_request->isXmlHttpRequest();

        if ($isAjax || $isApi) {
            $this->out([
                'code'    => $exception->getCode(),
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
            return false;
        } else {
            $this->_view->assign('error', $error);
            return false;
        }
    }
}
