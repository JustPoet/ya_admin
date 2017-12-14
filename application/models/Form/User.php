<?php
/**
 * User.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/13 17:37
 * 修改记录:
 *
 * $Id$
 */

class Form_UserModel extends Form_AbstractModel
{
    protected $fields = [
        'username' => [
            'label' => '用户名',
            'name' => 'username',
            "validate" => [
                [
                    "type" => "string",
                    "min"  => "3",
                    "max"  => "16",
                    "msg"  => "用户名长度3到16位",
                ],
            ]
        ],
        'name' => [
            'label' => '姓名',
            'name' => 'name'
        ],
        'role_id' => [
            'label' => '权限组',
            'name' => 'role_id'
        ],
        'group_id' => [
            'label' => '用户组',
            'name' => 'group_id'
        ]
    ];
}