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

    public function getPermission($userId)
    {

    }

    public function save($data)
    {
        $id = empty($data['id']) ? false : $data['id'];
        unset($data['id']);
        if ($id) {
            return UserModel::create($data);
        } else {
            return UserModel::where('id', $id)->update($data);
        }
    }

    public function getPage($condition = [], Page $page)
    {
        $builder = UserModel::with('role', 'group')->orderBy('created_at');
        if (!empty($condition)) {
            $builder = $builder->where($condition);
        }

        return Pagination::paginate($builder, $page);
    }

    /**
     * 权限验证
     *
     * @param $id
     *
     * @return bool
     */
    public function auth($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return false;
        }
        if ($user->status == 9) {
            return false;
        }
        return true;
    }

    public function switchStatus($userId, $status)
    {
        return UserModel::where('id', $userId)->update(['status' => $status]);
    }
}