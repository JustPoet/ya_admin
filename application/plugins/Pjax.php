<?php

use Symfony\Component\DomCrawler\Crawler;

/**
 * Pjax.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/30 11:23
 * 修改记录:
 *
 * $Id$
 */

class PjaxPlugin extends Yaf_Plugin_Abstract
{
    public function dispatchLoopShutdown(
        Yaf_Request_Abstract $request,
        Yaf_Response_Abstract $response
    ) {
        if ($request->getServer('HTTP_X_PJAX') === 'true') {
            $body = $this->filterResponse(
                $response,
                $request->getServer('HTTP_X_PJAX_CONTAINER')
            );
            if (!empty($body)) {
                $response->setHeader(
                    'X-PJAX-URL',
                    $request->getRequestUri()
                );
                $response->setBody($body);
            }
        }
    }

    protected function filterResponse(Yaf_Response_Abstract $response, $container)
    {
        $crawler = new Crawler($response->getBody());
        $content = $crawler->filter($container);

        if (!$content->count()) {
            return '';
        } else {
            return preg_replace_callback('/(&#[0-9]+;)/', function ($html) {
                return mb_convert_encoding($html[1], 'UTF-8', 'HTML-ENTITIES');
            }, $content->html());
        }
    }
}