<?php
/**
 * Group.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/13 16:54
 * 修改记录:
 *
 * $Id$
 */

class Service_Group
{
    use Singleton;

    public function get($id = null)
    {
        if ($id) {
            return GroupModel::find($id);
        }
        return GroupModel::all();
    }
}