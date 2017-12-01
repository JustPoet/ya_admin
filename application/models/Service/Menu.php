<?php
/**
 * Menu.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/11/30 18:17
 * 修改记录:
 *
 * $Id$
 */

class Service_Menu
{
    use Singleton;

    public function get($id = null)
    {
        if (isset($id)) {
            return MenuModel::find($id);
        } else {
            return MenuModel::with('sub')
                ->where('parent_id', 0)
                ->orderBy('order', 'asc')
                ->get();
        }
    }

    public function update($data, $id)
    {
        MenuModel::where('id', $id)->update($data);
    }
}