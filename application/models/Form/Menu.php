<?php
/**
 * Menu.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/12 21:41
 * 修改记录:
 *
 * $Id$
 */

class Form_MenuModel extends Form_AbstractModel
{
    protected $fields
        = [
            'parent_id' => [
                'label'   => '父菜单id',
                'name'    => 'parent_id',
                'default' => 0,
            ],
            'desc'      => [
                'label'    => '标题',
                'name'     => 'desc',
                "validate" => [
                    [
                        "type" => "string",
                        "min"  => "1",
                        "max"  => "10",
                        "msg"  => "标题长度1到10位",
                    ],
                ],
            ],
            'icon'      => [
                'label'   => '图标',
                'name'    => 'icon',
                'default' => 'fa-bars',
            ],
            'uri'       => [
                'label'   => '路径',
                'name'    => 'uri',
                'default' => '#',
            ],
        ];
}