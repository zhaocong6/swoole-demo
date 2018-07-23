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
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
    }

    public function connect()
    {
        if (!$this->client->connect('127.0.0.1', 9502, 1)){
            echo '服务器链接失败!';
            die;
        }

        fwrite(STDOUT, '请输入:');

        $msg = trim(fgets(STDIN));
        $this->client->send($msg);

//        $msg = $this->client->recv();
//        echo $msg;

    }
}

$client = new Client();
$client->connect();
