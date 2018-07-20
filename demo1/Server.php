<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 18-7-12
 * Time: 下午2:25
 */

class Server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server('0.0.0.0', 9501);

        $this->serv->set([
            'worker_num'    =>  8,
            'darmonize'     =>  false
        ]);

        $this->serv->on('Start',   [$this, 'onStart']);
        $this->serv->on('Connect', [$this, 'onConnect']);
        $this->serv->on('Receive', [$this, 'onReceive']);
        $this->serv->on('Close',   [$this, 'onClose']);

        $this->serv->start();
    }

    public function onStart($serv)
    {
        echo "服务开启 \n";
    }

    public function onConnect($serv, $fd, $from_id)
    {
        $serv->send($fd, '你好: 链接号码.'.$fd);
    }

    public function onReceive(swoole_server $server, $fd, $from_id, $data)
    {
        echo '完成链接.';

        $server->send($fd, $data);
    }

    public function onClose()
    {
        echo '关闭链接';
    }
}

$server = new Server();
