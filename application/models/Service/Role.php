<?php
/**
 * Role.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/13 16:55
 * 修改记录:
 *
 * $Id$
 */

class Service_Role
{
    use Singleton;

    public function get($id = null)
    {
        if ($id) {
            return RoleModel::find($id);
        }

        return RoleModel::all();
    }
}