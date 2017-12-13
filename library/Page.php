<?php
/**
 * Page.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/13 12:17
 * 修改记录:
 *
 * $Id$
 */

class Page
{
    /**
     * 当前页
     * @var int
     */
    public $pageNo;

    /**
     * 每页记录数量
     * @var int
     */
    public $per;

    /**
     * 总页数
     * @var int
     */
    public $total;

    /**
     * 总记录数
     * @var int
     */
    public $totalCount;

    public function __construct($pageNo = 1, $per = 15, $total = null, $totalCount = null)
    {
        $this->pageNo = (int) $pageNo;
        $this->per = (int) $per;
        $this->total = (int) $total;
        $this->totalCount = (int) $totalCount;
    }
}