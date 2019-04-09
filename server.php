<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 19-4-9
 * Time: 下午2:30
 */

//实例化一个swolle serve
$server = new swoole_server('127.0.0.1', 9501);

//设置serve链接回调
$server->on('connect', function ($server, $fd){
    echo 'client connected'.PHP_EOL;
});

//设置serve接受数据回调
$server->on('receive', function ($server, $fd, $from_id, $data){
    echo 'client request msg:'.$data;
    $server->send($fd, 'ok!');
});

//设置关闭回调
$server->on('close', function ($serve, $fd){
    echo 'client closed';
});

//启动服务器
$server->start();
