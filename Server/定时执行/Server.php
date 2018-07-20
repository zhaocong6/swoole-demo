<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 18-7-20
 * Time: 下午5:06
 */

class Server
{
    private $serve;

    public function __construct()
    {
        $this->serve = new \swoole_server('0.0.0.0', 9501, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

        $this->serve->set([
            'reactor_num'   =>  2,
            'worker_num'    =>  4,
            'backlog'       =>  128,
            'max_request'   =>  50,
            'max_conn'      =>  10000,
            'dispatch_mode' =>  1
//            'daemonize'     =>  true,//守护进程
        ]);
        $this->serve->on('Receive', [$this, 'onReceive']);
        $this->serve->start();
    }

    public function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        $server->tick(1000, function ()use($server, $fd){
            file_put_contents('./text.txt', "hello \n", FILE_APPEND);
        });

        $server->send($fd, '创建成功');
    }
}

$server = new Server();
