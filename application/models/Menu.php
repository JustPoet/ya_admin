<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Menu.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/30 14:17
 * 修改记录:
 *
 * $Id$
 */

class MenuModel extends Model
{
    protected $table = 'menu';

    protected $fillable = ['parent_id', 'desc', 'icon', 'uri', 'order'];

    public function sub()
    {
        return $this->hasMany(MenuModel::class, 'parent_id');
    }
}