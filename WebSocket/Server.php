<?php

$server = new swoole_websocket_server('0.0.0.0', 9502);

$server->on('open', function (swoole_websocket_server $server, $request) {
    echo 'ws链接开始: 来自'.$request->fd."\n";
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo 'ws客户端数据:'.$frame->data;

    $server->tick(1000, function ()use ($server, $frame){
        $server->push($frame->fd, '你好客户端');
    });
});

$server->on('close', function ($ser, $fd) {
    echo "客户端 $fd 关闭链接";
});

$server->start();
