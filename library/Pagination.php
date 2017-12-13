<?php

use Illuminate\Database\Eloquent\Builder;

/**
 * Pagination.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/13 13:17
 * 修改记录:
 *
 * $Id$
 */

class Pagination
{
    /**
     * 分页
     * @param  Builder   $builder
     * @param  Page|null $page
     * @return mixed
     */
    public static function paginate(Builder $builder, Page $page = null)
    {
        $page = $page ?: new Page(1);
        $page->totalCount = $builder->toBase()->getCountForPagination();
        $page->per = $page->per ?: $builder->getModel()->getPerPage();
        $items = $builder->skip($page->per * ($page->pageNo - 1))->take($page->per)->get();
        $page->total = ceil($page->totalCount / $page->per);

        return [
            'items' => $items ?? [],
            'page' => $page,
        ];
    }
}