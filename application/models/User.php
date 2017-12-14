<?php

use Illuminate\Database\Eloquent\Model;

/**
 * User.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/30 14:11
 * 修改记录:
 *
 * $Id$
 */
class UserModel extends Model
{
    protected $table = 'user';

    protected $fillable
        = [
            'username',
            'name',
            'password',
            'avatar',
            'group_id',
            'role_id',
            'status'
        ];

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }

    public function group()
    {
        return $this->belongsTo(GroupModel::class, 'group_id');
    }
}