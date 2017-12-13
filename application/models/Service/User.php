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

    public function login($username, $password, $captcha)
    {
        $session = Yaf_Session::getInstance();

        $result = mb_strtolower($captcha) == $session->get('captcha');
        $session->del('captcha');
        if (!$result) {
            return false;
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

    public function get($id)
    {
        return UserModel::find($id);
    }

    public function update($id, $data)
    {
        return UserModel::where('id', $id)->update($data);
    }

    public function getPermission($userId)
    {

    }

    public function create($userInfo)
    {

    }

    public function getPage($condition = [], Page $page)
    {
        $builder = UserModel::with('role', 'group')->orderBy('created_at');
        if (!empty($condition)) {
            $builder = $builder->where($condition);
        }

        return Pagination::paginate($builder, $page);
    }
}