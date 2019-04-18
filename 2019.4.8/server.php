<?php

$serv = new swoole_server("127.0.0.1", 9501);

//设置异步任务的工作进程数量
$serv->set(array('task_worker_num' => 4));

$serv->on('receive', function($serv, $fd, $from_id, $data) {
    //投递异步任务
    $task_id = $serv->task($data);
    $serv->send($fd, '任务投递成功');
});

//处理异步任务
$serv->on('task', function ($serv, $task_id, $from_id, $data) {
    sleep(5);
    file_put_contents('./txt.txt', time().PHP_EOL);
});

//处理异步任务的结果
$serv->on('finish', function ($serv, $task_id, $data) {

});

$serv->start();