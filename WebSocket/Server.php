<?php

$server = new swoole_websocket_server('0.0.0.0', 9501);

$server->set([
    'reactor_num' => 2, //reactor thread num
    'worker_num' => 4,    //worker process num
    'backlog' => 128,   //listen backlog
    'task_worker_num'=>10,
    'max_request' => 50,
    'dispatch_mode' => 1,
]);

$server->on('open', function (swoole_websocket_server $server, $request) {
    echo 'ws链接开始: 来自'.$request->fd."\n";
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo 'ws客户端数据:'.$frame->data;

    $server->task('213');

    $server->tick(1000, function ($id)use ($server, $frame){
        if ($server->exist($frame->fd)){
            $server->push($frame->fd, '你好客户端');
        }else{
            $server->clearTimer($id);
            $server->close($frame->fd);
        }
    });
});

$server->on('task', function (swoole_server $serv, $task_id, $data){

    $serv->tick(1000, function (){
        file_put_contents('text.txt', "asd\n", FILE_APPEND);
    });
});

$server->on('finish', function (){});

$server->on('close', function ($ser, $fd) {
    echo "客户端 $fd 关闭链接";
});

$server->start();
