<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 18-7-12
 * Time: 下午2:32
 */

class Client
{
    private $client;

    public function __construct()
    {
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
    }

    public function connect()
    {
        if (!$this->client->connect('127.0.0.1', 9501, 1)){
            echo '服务器链接失败!';
            die;
        }
        $this->client->send(1);
        $msg = $this->client->recv();
        echo $msg;
    }
}

$client = new Client();
$client->connect();
