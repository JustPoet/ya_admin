<?php
/**
 * User.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/30 14:08
 * 修改记录:
 *
 * $Id$
 */

class Service_User
{
    use Singleton;

    public function login($username, $password, $captcha = '')
    {
        $session = Yaf_Session::getInstance();
        if (!empty($captcha)) {
            $result = mb_strtolower($captcha) == $session->get('captcha');
            $session->del('captcha');
            if (!$result) {
                return false;
            }
        }

        $user = UserModel::where([
            'username' => $username,
        ])->first();

        if (password_verify($password, $user->password)) {
            $session->set('user', $user);
            return $user;
        } else {
            return false;
        }
    }

    public function changePassword($userId, $password)
    {

    }

    public function getPermission($userId)
    {

    }

    public function create($userInfo)
    {

    }
}