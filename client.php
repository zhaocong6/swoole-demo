<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 19-4-9
 * Time: 下午2:35
 */

//实例化swoole client
$client = new swoole_client(SWOOLE_SOCK_TCP);

//链接server
if (!$client->connect('127.0.0.1', 9501, -1)){
    exit('链接失败!');
}

//向server发送数据
$client->send('hello');

//打印server返回数据
echo $client->recv();

//关闭server链接
$client->close();
