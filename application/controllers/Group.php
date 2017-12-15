<?php
/**
 * Group.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/14 16:45
 * 修改记录:
 *
 * $Id$
 */

class GroupController extends ControllerAbstract
{
    public function indexAction()
    {
        return true;
    }

    public function listAction()
    {
        $req = $this->_request->getRequest();
        $page = new Page($req['draw'], $req['length']);
        $result = Service_Group::getInstance()->get(0, $page);
        $data = [];
        foreach ($result['items'] as $index => $group) {
            $operations = $this->_view->render(
                '/group/table_operate.twig',
                ['group' => $group]
            );

            $data[] = [
                'index'   => $index + 1,
                'name'    => $group->name,
                'operate' => $operations,
            ];
        }

        $this->out([
            'draw'            => $req['draw'],
            'recordsTotal'    => $result['page']->totalCount,
            'recordsFiltered' => $result['page']->totalCount,
            'data'            => $data,
        ]);

        return false;
    }

    public function getAction()
    {
        $id = $this->_request->getQuery('id');
        $group = Service_Group::getInstance()->get($id);
        if ($group) {
            $this->out([
                'code' => 200,
                'message' => 'success',
                'data' => $group
            ]);
        } else {
            $this->out([
                'code' => 400,
                'message' => '未找到相关记录,请刷新重试'
            ]);
        }
    }

    public function saveAction()
    {
        $data = $this->_request->getPost();
        $result = Service_Group::getInstance()->save($data);
        if ($result) {
            $this->out([
                'code' => 200,
                'message' => '保存成功',
            ]);
        } else {
            $this->out([
                'code' => 500,
                'message' => '保存失败'
            ]);
        }
    }
}