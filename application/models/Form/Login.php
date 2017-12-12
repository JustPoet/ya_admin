<?php
/**
 * Login.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/12 14:52
 * 修改记录:
 *
 * $Id$
 */

class Form_LoginModel extends Form_AbstractModel
{
    protected $fields
        = [
            'username'   => [
                'label'    => '用户名',
                'name'     => 'username'
            ],
            'password' => [
                'label'    => '密码',
                'name'     => 'password',
                "validate" => [
                    [
                        "type" => "string",
                        "min"  => "6",
                        "max"  => "18",
                        "msg"  => "密码长度6到18位",
                    ],
                ],
            ],
            'captcha'   => [
                'label'    => '验证码',
                'name'     => 'captcha'
            ],
        ];
}