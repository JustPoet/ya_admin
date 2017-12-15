<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Group.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/13 13:34
 * 修改记录:
 *
 * $Id$
 */
class GroupModel extends Model
{
    protected $table = 'group';

    protected $fillable = ['name'];
}