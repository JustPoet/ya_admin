$.pjax.defaults.timeout = 5000;
$.pjax.defaults.maxCacheLength = 0;
$(document).pjax('a:not(a[target="_blank"])', {
    container: '#pjax-container'
});

NProgress.configure({ parent: '#pjax-container' });

$(document).on('pjax:timeout', function(event) { event.preventDefault(); })
$(document).on('pjax:error', function(event) { event.preventDefault(); })

$(document).on('submit', 'form[pjax-container]', function(event) {
    $.pjax.submit(event, '#pjax-container')
});

$(document).on("pjax:popstate", function() {

    $(document).one("pjax:end", function(event) {
        $(event.target).find("script[data-exec-on-popstate]").each(function() {
            $.globalEval(this.text || this.textContent || this.innerHTML || '');
        });
    });
});

$(document).on('pjax:send', function(xhr) {
    if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
        $submit_btn = $('form[pjax-container] :submit');
        if($submit_btn) {
            $submit_btn.button('loading')
        }
    }
    NProgress.start();
});

$(document).on('pjax:complete', function(xhr) {
    if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
        $submit_btn = $('form[pjax-container] :submit');
        if($submit_btn) {
            $submit_btn.button('reset')
        }
    }
    NProgress.done();
});

$(function(){
    $('.sidebar-menu li:not(.treeview) > a').on('click', function(){
        var $parent = $(this).parent().addClass('active');
        $parent.siblings('.treeview.active').find('> a').trigger('click');
        $parent.siblings().removeClass('active').find('li').removeClass('active');
    });

    $('[data-toggle="popover"]').popover();
});

var dataTableConfig = {
    "processing": true,
    "serverSide": true,
    "deferRender": true,
    "paging": true,
    "ordering": false,
    "searching": false,
    "ajax": "",
    "pageLength": 20,
    "lengthChange": false,
    "pagingType": "simple",
    "columns": [
    ],
    "fixedColumns": true,
    "language": {
        "sProcessing": "加载中...",
        "sLengthMenu": "显示 _MENU_ 条记录",
        "sZeroRecords": "未找到数据",
        "sInfo": "现在是第 _START_ 至 _END_ 条，共 _TOTAL_ 条",
        "sInfoEmpty": "显示第 0 至 0 条结果，共 0 条",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "没有数据",
        "sLoadingRecords": "加载中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        }
    }
}