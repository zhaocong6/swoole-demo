<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 18-7-20
 * Time: 下午2:10
 */

class Server2
{

    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server('0.0.0.0', 9501, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

        $this->serv->set([
            'reactor_num'   =>  2,
            'worker_num'    =>  2,
            'backlog'       =>  128,
            'max_request'   =>  50,
            'dispatch_mode' =>  1,
        ]);

        $this->serv->on('connect', function ($serv, $fd){
            echo $fd;
        });

        $this->serv->on('receive', function ($serv, $fd, $form_id, $data){


            $this->serv->send($fd, 'Swoole: '.$data);

            $serv->tick(1000, function ()use($serv, $fd){
                $serv->send($fd, 'hello world!');
            });

            $this->serv->close($fd);
        });

        $this->serv->on('close', function ($serv, $fd){
            echo "Client: Close.\n";
        });

        $this->serv->start();
    }
}

$server = new Server2();
