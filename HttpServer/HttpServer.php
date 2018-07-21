<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 18-7-20
 * Time: 上午10:08
 */

$http = new swoole_http_server('0.0.0.0', 8080, SWOOLE_BASE);

$http->set([
    'enable_static_handler' => true,
    'document_root'         => '/object/swoole/HttpServer/',
]);

$http->on('request', function (swoole_http_request $req, swoole_http_response $rep){
    $rep->write('hello world');
    $rep->end();
});

$http->start();
