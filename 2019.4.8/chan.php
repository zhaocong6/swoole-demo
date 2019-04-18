<?php

$chan = new chan(2);

/**
 * 协程1
 * 当chan为空时
 * 调用pop, 如果管道中没有数据将会堵塞, 直到chan有值后才会继续执行
 */
go (function () use ($chan) {
    $result = [];
    for ($i = 0; $i < 2; $i++)
    {
        $result += $chan->pop();
    }
    var_dump($result);
});

/**
 * 协程2
 * 请求第三方数据, 得到数据后将数据push到管道中
 */
go(function () use ($chan) {
    $cli = new Swoole\Coroutine\Http\Client('www.qq.com', 80);
    $cli->set(['timeout' => 10]);
    $cli->setHeaders([
        'Host' => "www.qq.com",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $ret = $cli->get('/');
    // $cli->body 响应内容过大，这里用 Http 状态码作为测试
    $chan->push(['www.qq.com' => $cli->statusCode]);
});

/**
 * 协程3
 * 请求第三方数据, 得到数据后将数据push到管道中
 */
go(function () use ($chan) {
    $cli = new Swoole\Coroutine\Http\Client('www.163.com', 80);
    $cli->set(['timeout' => 10]);
    $cli->setHeaders([
        'Host' => "www.163.com",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $ret = $cli->get('/');
    // $cli->body 响应内容过大，这里用 Http 状态码作为测试
    $chan->push(['www.163.com' => $cli->statusCode]);
});