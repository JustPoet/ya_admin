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

    public function get($id, Page $page = null)
    {
        if ($id) {
            return GroupModel::find($id);
        }
        return Pagination::paginate(GroupModel::query(), $page);
    }

    public function save($data)
    {
        $id = empty($data['id']) ? false : $data['id'];
        unset($data['id']);
        if ($id) {
            return GroupModel::create($data);
        } else {
            return GroupModel::where('id', $id)->update($data);
        }
    }

    public function delete($id)
    {

    }
}